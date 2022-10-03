<?php

declare(strict_types=1);


namespace Src\Services\Auth;

use Auth;
use DB;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;
use Mail;
use Repository\Contracts\BaseRepositoryContract;
use ServiceEntity\BaseService;
use Src\Broadcasting\Mail\ClientRegisterCodeMail;
use Src\Core\Additional\Guzzle;
use Src\Core\Enums\ConstRedis;
use Src\Core\Traits\OauthTrait;
use Src\Exceptions\Lexcept;
use Src\Models\Client\Client;
use Src\Repositories\AdminCorporate\AdminCorporateContract;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\CarOption\CarOptionContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientSetting\ClientSettingContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\FcmClient\FcmClientContract;
use Src\Repositories\OauthClient\OauthClientContract;
use Src\Repositories\SuperAdmin\SuperAdminContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\Terminal\TerminalContract;
use Src\Services\Oauth\OauthServiceContract;
use Src\Support\Facades\Sms;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

use function in_array;

/**
 * Class AuthService
 * @package Src\Services\AuthService
 */
class AuthService extends BaseService implements AuthServiceContract
{
    use OauthTrait;
    use AuthenticatesUsers;

    /**
     * AuthService constructor.
     * @param  ClientContract  $clientContract
     * @param  SystemWorkerContract  $workerContract
     * @param  DriverContract  $driverContract
     * @param  Guzzle  $client
     * @param  BaseRepositoryContract  $baseContract
     * @param  AdminCorporateContract  $adminCorporateContract
     * @param  SuperAdminContract  $superAdminContract
     * @param  BeforeAuthClientContract  $beforeAuthContract
     * @param  OauthServiceContract  $oauthService
     * @param  OauthClientContract  $oauthContract
     * @param  TerminalContract  $terminalContract
     * @param  CarOptionContract  $carOptionContract
     * @param  FcmClientContract  $fcmContract
     * @param  ClientSettingContract  $clientSettingContract
     */
    public function __construct(
        protected ClientContract $clientContract,
        protected SystemWorkerContract $workerContract,
        protected DriverContract $driverContract,
        protected Guzzle $client,
        protected BaseRepositoryContract $baseContract,
        protected AdminCorporateContract $adminCorporateContract,
        protected SuperAdminContract $superAdminContract,
        protected BeforeAuthClientContract $beforeAuthContract,
        protected OauthServiceContract $oauthService,
        protected OauthClientContract $oauthContract,
        protected TerminalContract $terminalContract,
        protected CarOptionContract $carOptionContract,
        protected FcmClientContract $fcmContract,
        protected ClientSettingContract $clientSettingContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function checkHasClientPhone($phoneNumber): ?bool
    {
        return $this->clientContract->findBy('phone', $phoneNumber) ? true : null;
    }

    /**
     * @inheritDoc
     */
    public function loginClientByPhone($phoneNumber)
    {
        $client = $this->clientContract->where('phone', '=', $phoneNumber)->findFirst(['client_id']);

        if (!$client) {
            $client = $this->clientContract->create(['phone' => $phoneNumber]);
            $this->clientSettingContract->create(['client_id' => $client->client_id]);
        }

        $this->guard()->login($client, true);
        $this->redis()->hDel(ConstRedis::accept_code('client', $phoneNumber), ...['phone', 'accept_code', 'created_at']);
        $this->guard()->login($client, true);

        return $client->client_id ?? null;
    }

    /**
     * @return Guard|StatefulGuard
     */
    public function guard()
    {
        return Auth::guard((string)session('assigned_guard'));
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sendSmsAcceptCode($phone)
    {
        $key = random_int(111111, 999999);
        Sms::send($phone, "Код Подтверждения: $key");

        Mail::to(config('nyt.fictive_mail'))->queue(new ClientRegisterCodeMail($key));

        if (Mail::failures()) {
            return false;
        }

        $this->redis()->hMSet(
            ConstRedis::accept_code('client', $phone),
            [
                'phone' => $phone,
                'accept_code' => $key,
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
        );

        $this->redis()->expire(ConstRedis::accept_code('client', $phone), config('nyt.accept_code_expired'));

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sendSmsAcceptCodeToDriverMobile($phone): ?Collection
    {
        if (!$this->driverContract->where('phone', '=', $phone)->exists()) {
            return null;
        }

        $key = random_int(111111, 999999);
        Sms::send($phone, "Код Подтверждения: $key");

        $this->redis()->hmset(
            ConstRedis::accept_code('driver', $phone),
            [
                'phone' => $phone,
                'accept_code' => $key,
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
        );

        $this->redis()->expire(ConstRedis::accept_code('driver', $phone), config('nyt.accept_code_expired'));

        return $this->parseResult(
            $instance,
            ['phone', 'expired'],
            [$phone, config('nyt.accept_code_expired') / 60]
        );
    }

    /**
     * @param $name
     * @param $password
     * @return mixed|void
     */
    public function loginClientByName($name, $password)
    {
        return $this->hasPerson('client', 'name', $name, $password);
    }

    /**
     * @inheritDoc
     */
    public function hasExistsPersonalAdmin($login, $password)
    {
        return $this->hasPerson('adminCorporate', 'email', $login, $password);
    }

    /**
     * @inheritDoc
     */
    public function getSystemWorkerDataById(int $system_worker_id): ?bool
    {
        if (!$this->workerContract->find($system_worker_id)) {
            return null;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function hasExistsWorker($login, $password)
    {
        return $this->hasPerson('systemWorker', 'nickname', $login, $password);
    }

    /**
     * @inheritDoc
     * @throws GuzzleException
     */
    public function authDriver(string $login, string $password): array
    {
        $has_driver = $this->driverContract
            ->where('nickname', '=', $login)
            ->with([
                'active_contract' => fn(HasOne $query) => $query
                    ->where('signed', '=', true)
                    ->select(['driver_contracts.driver_contract_id', 'driver_contracts.driver_id', 'driver_contracts.active', 'driver_contracts.signed']),
            ])
            ->findFirst([
                'driver_id',
                'current_franchise_id',
                'nickname',
                'phone',
                'password',
            ]);

        if (!$has_driver || !Hash::check($password, $has_driver->password)) {
            throw new Lexcept('Password not valid', 422);
        }

        if (!$has_driver->active_contract) {
            throw new Lexcept(trans('messages.not_contract'), 500);
        }

        $driver = $this->driverLoadMissingData($login);
        $driver->options = $this->carOptionContract->findAll();

        $client = $this->oauthContract->where('name', '=', 'DRIVERS')->where('provider', '=', 'drivers')->findFirst();
        $token_data = [
            'username' => $login,
            'password' => $password,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'guard' => 'drivers_api',
            'provider' => 'drivers',
        ];
        $bearer_token = $this->getOauthTokenByPasswordGrantType($token_data);
        Auth::setUser($driver);
        $this->driverContract->update($has_driver->driver_id, ['online' => true, 'logged' => true]);

        return [$driver, $bearer_token];
    }

    /**
     * @param  string  $nickname
     * @return mixed
     */
    protected function driverLoadMissingData(string $nickname): mixed
    {
        return $this->driverContract
            ->where('nickname', '=', $nickname)
            ->with([
                'fcm' => fn($query) => $query->select(['fcm_client_id', 'client_id', 'client_type', 'key']),
                'type' => fn($query) => $query
                    ->where('worked', '=', 1)
                    ->select([
                        'driver_types.driver_type_id',
                        'driver_types.type',
                        'driver_types.name',
                        'driver_types.description',
                        'driver_types.image'
                    ]),
                'current_franchise' => fn(BelongsTo $query) => $query->select(['franchise_id', 'name']),
                'driver_info' => fn($query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic', 'photo', 'email']),
                'car' => fn(BelongsTo $query) => $query
                    ->with([
                        'classes' => fn(BelongsToJson $query) => $query->select(['car_class_id', 'class_name', 'image'])
                    ])
                    ->select(['car_id', 'mark', 'model', 'color', 'state_license_plate', 'class', 'option']),
                'graphic' => fn(HasOneDeep $query) => $query->select([
                    'driver_graphics.driver_graphic_id',
                    'name',
                    'working_days_count',
                    'weekend_days_count',
                    'week'
                ]),
                'addresses' => fn(HasMany $query) => $query->select(['driver_address_id', 'driver_id', 'target', 'address', 'lat', 'lut']),
                'waybills' => fn(HasMany $query) => $query
                    ->where('start_time', '<=', now()->format('Y-m-d H:i:s'))
                    ->where('end_time', '>=', now()->format('Y-m-d H:i:s'))
                    ->where('verified', '=', true)
                    ->where('signed', '=', true)
                    ->select(['waybill_id', 'car_id', 'driver_id', 'start_time', 'end_time', 'signed', 'verified'])
            ])
            ->findFirst([
                'driver_id',
                'car_id',
                'current_franchise_id',
                'nickname',
                'phone',
                'password',
                'selected_class',
                'selected_option',
                'driver_info_id'
            ]);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws GuzzleException
     */
    public function authDriverByPhone($phone, $accept_code)
    {
        $driver = $this->driverContract->where('phone', '=', $phone)->findFirst(['driver_id']);

        if (!$driver) {
            return null;
        }

        $this->driverContract->update($driver->driver_id, ['password_code' => $accept_code]);

        $this->redis()->set("oauth_password$accept_code", $accept_code);
        $oauth_client = $this->oauthContract->where('name', '=', 'DRIVERS')->where('provider', '=', 'drivers')->findFirst();

        $user_data = [
            'username' => $driver->nickname,
            'password' => $accept_code,
            'client_id' => $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'guard' => 'drivers_api',
            'provider' => 'drivers',
        ];

        $bearer_token = $this->getOauthTokenByPasswordGrantType($user_data);
        Auth::setUser($driver);
        $this->redis()->hDel(ConstRedis::accept_code('driver', $phone), ...['phone', 'accept_code', 'created_at']);

        return [$driver, $bearer_token];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function getDriverToken($user_data)
    {
        $driver = $this->driverContract->where('nickname', '=', $user_data['username'])->findFirst();

        $bearer_token = $this->refreshOauthToken($user_data);

        return [$driver, $bearer_token];
    }

    /**
     * @param  string  $model
     * @param  array  $find
     * @param  string  $role
     * @return bool
     */
    public function addRole(string $model, array $find, string $role): bool
    {
        $this->baseContract->setModel($model);
        $person = $this->baseContract->findBy(array_keys($find)[0], array_values($find)[0]);

        if (!$person->hasRole($role)) {
            $person->assignRole($role);
            return true;
        }

        return true;
    }

    /**
     * @param  Request  $request
     * @return bool|null
     */
    public function logoutDriver($request): ?bool
    {
        if (Auth::check()) {
            $request->user()->token()->revoke();
            $this->driverContract->update(get_user_id(), ['logged' => false, 'online' => false]);

            return true;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function passwordConfirm($password)
    {
        $user = DB::table(user()->getTable())
            ->where(user()->getKeyName(), get_user_id(), $password)
            ->first([user()->getKeyName(), 'password']);

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function detectUser($request, array $priority = []): ?Collection
    {
        $guards = array_keys(config('auth.guards'));
        $auth_sessions = $request->getSession()->all();
        $current = [];
        $client_data = [];

        $iterate = 0;
        foreach ($auth_sessions as $key => $value) {
            ++$iterate;

            if (Str::is('*_web_*', $key)) {
                $guard = str_replace('login_', '', substr($key, 0, strpos($key, '_web_')).'_web');
                $current[$iterate] = ['key' => $value, 'value' => $guard];
                break;
            }
        }

        if (!$current) {
            return $this->parseResult($instance);
        }

        foreach ($current as $cur) {
            $result = in_array($cur['value'], $guards, true);

            if (!$result) {
                break;
            }

            if ($priority && !in_array($cur['value'], $priority, true)) {
                return $this->parseResult($instance);
            }

            $this->switchCurrentUserdataBySession($client_data, $cur);
        }

        if (!$client_data) {
            return null;
        }

        $class_name = array_first($client_data)->getMap();
        $class_key_name = array_key_first($client_data);

        if ($class_name === (new Client())->getMap()) {
            $client_data[array_key_first($client_data)]
                ->load([
                    'corporateCompanies' => fn(HasManyDeep $corporate_query) => $corporate_query->select([
                        'corporate_clients.company_id',
                        'corporate_clients.name'
                    ])
                ]);
        }

        return $this->parseResult($instance,
            ['client', 'companies'],
            [$client_data[$class_key_name]->getAttributes(), $client_data[$class_key_name]->corporateCompanies]
        );
    }

    /**
     * @param $data
     * @param $cur
     */
    protected function switchCurrentUserdataBySession(&$data, $cur): void
    {
        switch ($cur['value']) {
            case 'clients_web':
                $detected_client = $this->clientContract->find($cur['key'], ['client_id', 'phone', 'in_order']);

                if ($detected_client) {
                    $data['client'] = $detected_client;
                }

                break;
            case 'before_clients_web':
                $detected_client = $this->beforeAuthContract->find($cur['key'], [$this->beforeAuthContract->getKeyName(), 'hash', 'client_id']);

                if ($detected_client) {
                    $data['client'] = $detected_client;
                }

                break;
            case 'system_workers_web':
                $worker = $this->workerContract->find($cur['key']);

                if ($worker) {
                    $data['worker'] = $worker;
                }

                break;
            case 'admin_corporate_web':
                $adminCorporate = $this->adminCorporateContract->find($cur['key']);
                if ($adminCorporate) {
                    $data['admin_corporate'] = $adminCorporate;
                }

                break;
            case 'admin_super_web':
                $adminSuper = $this->superAdminContract->find($cur['key']);

                if ($adminSuper) {
                    $data['admin_super'] = $adminSuper;
                }

                break;
            default:
                $data['client'] = $this->clientContract->find($cur['key'], ['client_id', 'phone', 'in_order']);
        }
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws GuzzleException
     */
    public function atcAuth($request)
    {
        $client = $this->oauthService->getSecretBySecret($request['secret']);

        if (!$client) :
            return response(['message' => 'Wrong secret'], 422);
        endif;

        return $this->createTokenBySecret($client->id, $client->secret);
    }

    /**
     * @inheritDoc
     */
    public function clientHasAuthGenerateHash(): void
    {
        $client_id = user() && Auth::guard('clients_web')->check() ? user()->{user()->getKeyName()} : null;

        if ($client_id) :
            return;
        endif;

        if (!isset($_COOKIE['humanoid'])) {
            $this->createBeforeClient();
            return;
        }

        if (!$this->beforeAuthContract->where('hash', '=', $_COOKIE['humanoid'])->where('client_id', '=', null)->findFirst()) {
            $this->createBeforeClient();
        }
    }

    protected function createBeforeClient(): void
    {
        $hash = get_token(128);
        $hash_name = get_token();

        $create = $this->beforeAuthContract->create(compact('hash', 'hash_name'));

        session([$hash_name => $hash]);
        Auth::guard('before_clients_web')->loginUsingId($create->before_auth_client_id, true);
    }

    //////////////////////////////////////////////////CLIENT API MOBILE//////////////////////////////////////////

    /**
     * @inheritDoc
     */
    public function clientMobileAuthenticate($phone)
    {
        $key = random_int(111111, 999999);
        Sms::send($phone, "Код Подтверждения: $key");

        Mail::to('manukyan.artak.89@gmail.com')->queue(new ClientRegisterCodeMail($key));

        $this->redis()->hMSet(
            ConstRedis::accept_code('client', $phone),
            [
                'phone' => $phone,
                'accept_code' => $key,
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
        );

        $this->redis()->expire(ConstRedis::accept_code('client', $phone), config('nyt.accept_code_expired'));

        return $this->parseResult($instance, ['phone', 'expired'], [$phone, config('nyt.accept_code_expired') / 60]);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws GuzzleException
     */
    public function clientMobileAuthAccept($phone, $accept_code)
    {
        $this->redis()->hDel(ConstRedis::accept_code('client', $phone), ...['phone', 'accept_code', 'created_at']);

        $ordered_client = $this->clientContract->where('phone', '=', $phone)->findFirst();

        if ($ordered_client) {
            $this->clientContract->update($ordered_client->{$ordered_client->getKeyName()},
                ['logged' => true, 'online' => true, 'password' => Hash::make($accept_code)]);
        } else {
            $ordered_client = $this->clientContract->create(['logged' => true, 'online' => true, 'phone' => $phone, 'password' => Hash::make($accept_code)]);
            $this->clientSettingContract->create(['client_id' => $ordered_client->{$ordered_client->getKeyName()}]);
        }

        $secret = $this->oauthContract->where('name', '=', 'CLIENTS')->where('provider', '=', 'clients')->findFirst();

        $user_data = [
            'client_id' => $secret->id,
            'client_secret' => $secret->secret,
            'username' => $phone,
            'password' => $accept_code,
            'guard' => 'clients_api',
            'provider' => 'clients'
        ];

        $bearer = $this->getOauthTokenByPasswordGrantType($user_data);
        $this->guard()->setUser($ordered_client);

        return $this->parseResult(
            $instance,
            ['type', 'expires', 'token', 'client_id'],
            [$bearer['token_type'], $bearer['expires_in'], $bearer['access_token'], $ordered_client->{$ordered_client->getKeyName()}]
        );
    }

    /**
     * @inheritDoc
     */
    public function clientMobileLogout(Client $client)
    {
        $this->oauthService->clientTokensRevoke($client, true, true);
        $this->clientContract->update($client->{$client->getKeyName()}, ['logged' => 0, 'online' => 0, 'in_order' => 0]);
    }

    /**
     * @inheritDoc
     */
    public function beforeAuthClientRegenerate($request, $client_id = null): void
    {
        if (!Auth::guard('before_clients_web')) :
            return;
        endif;

        $before_user = Auth::guard('before_clients_web')->user();
        Auth::guard('before_clients_web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($client_id && $before_user) :
            $before_user->update(['client_id' => $client_id]);
        endif;
    }
    //////////////////////////////////////////////////CLIENT API MOBILE END//////////////////////////////////////////

    /**
     * @param $name
     * @param $password
     * @return Collection|null
     * @throws JsonException
     */
    public function authTerminal($name, $password): ?Collection
    {
        $terminal = $this->terminalContract->firstWhere(['name', '=', $name]);

        if (!Hash::check($password, $terminal->password)) {
            return null;
        }

        $id = $this->oauthContract->where('name', '=', 'TERMINAL')->where('provider', '=', 'terminals')->findFirst(['secret', 'id', 'name']);

        if (!$id) {
            return null;
        }

        $client = $this->oauthContract->where('name', '=', 'TERMINAL')->where('provider', '=', 'terminals')->findFirst();
        $terminal_data = [
            'username' => $name,
            'password' => $password,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'guard' => 'api_terminals',
            'provider' => 'terminals',
        ];

        $token = $this->getOauthTokenByPasswordGrantType($terminal_data);

        return $this->parseResult($token) ?: null;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function authTerminalDriver(string $nickname, string $pwd, int $terminal_id)
    {
        $driver = $this->driverContract->where('nickname', '=', $nickname)->findFirst();

        if (!$driver || !Hash::check($pwd, $driver->password)) {
            return null;
        }

        $client = $this->oauthContract
            ->where('name', '=', 'DRIVERS_TERMINAL')
            ->where('provider', '=', 'drivers')
            ->findFirst();

        $user_data = [
            'username' => $nickname,
            'password' => $pwd,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'guard' => 'drivers_api',
            'provider' => 'drivers',
        ];

        $token = $this->getOauthTokenByPasswordGrantType($user_data);
        $this->terminalContract->update($terminal_id, ['auth_driver_id' => $driver->{$driver->getKeyName()}]);

        return $this->parseResult($token) ?: null;
    }


    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function workerBearer($worker_data): ?array
    {
        $worker = $this->workerContract
            ->where('nickname', '=', $worker_data->username)
            ->findFirst(['system_worker_id', 'nickname', 'password', 'name', 'surname', 'patronymic', 'email']);

        if (!$worker) {
            return null;
        }

        if (!\Illuminate\Support\Facades\Hash::check($worker_data->password, $worker->password)) {
            return null;
        }

        $client = $this->oauthContract->firstWhere(['name', '=', 'SYSTEM_WORKERS'], ['name', 'secret', 'id']);

        $auth_data = [
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $worker_data->username,
            'password' => $worker_data->password,
            'guard' => 'system_workers_api',
            'provider' => 'system_workers',
        ];

        $bearer_token = $this->getOauthTokenByPasswordGrantType($auth_data);
        $worker->update(['logged' => true, 'in_session' => true]);

        return collect($bearer_token)->merge($worker)->all();
    }

    /**
     * @inheritDoc
     * @throws Lexcept
     */
    public function validWorkerIdPwd($id, $password): bool
    {
        $worker = $this->workerContract->find($id, [$this->workerContract->getKeyName(), 'password']);

        if (!$worker) {
            throw new Lexcept('Invalid Worker Id', 422);
        }

        if (!Hash::check($password, $worker->password)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setUpdatePushKey(int $client_id, string $client_type, string $key)
    {
        $this->fcmContract->updateOrCreate(
            ['client_id', '=', $client_id, 'client_type', '=', $client_type],
            compact('client_id', 'client_type', 'key')
        );
    }

    /**
     * @inheritDoc
     * @param  string  $email
     * @param  string  $password
     * @return Collection|null
     * @throws GuzzleException
     * @throws JsonException
     */
    public function clientAuthByEmail(string $email, string $password): ?Collection
    {
        $has_client = $this->clientContract->where('email', '=', $email)->findFirst();

        if (!$has_client) {
            return null;
        }

        if (!Hash::check($password, $has_client->password)) {
            return null;
        }

        $this->clientContract->update($has_client->{$has_client->getKeyName()}, ['logged' => true, 'online' => true]);

        $secret = $this->oauthContract->where('name', '=', 'CLIENTS')->where('provider', '=', 'clients')->findFirst();

        $user_data = [
            'client_id' => $secret->id,
            'client_secret' => $secret->secret,
            'username' => $has_client->phone,
            'password' => $password,
            'guard' => 'clients_api',
            'provider' => 'clients'
        ];

        $bearer = $this->getOauthTokenByPasswordGrantType($user_data);
        $this->guard()->setUser($has_client);

        return $this->parseResult(
            $instance,
            ['type', 'expires', 'token', 'client_id'],
            [$bearer['token_type'], $bearer['expires_in'], $bearer['access_token'], $has_client->{$has_client->getKeyName()}]
        );
    }
}
