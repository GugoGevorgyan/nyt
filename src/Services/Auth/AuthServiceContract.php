<?php

declare(strict_types=1);


namespace Src\Services\Auth;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JsonException;
use ServiceEntity\Contract\BaseContract;
use Src\Exceptions\Lexcept;
use Src\Models\Client\Client;

/**
 * Class AuthServiceContract
 * @package Src\Services\AuthService
 */
interface AuthServiceContract extends BaseContract
{
    /**
     * @param $phoneNumber
     * @return bool
     */
    public function checkHasClientPhone($phoneNumber): ?bool;

    /**
     * @param $phoneNumber
     * @return mixed
     */
    public function loginClientByPhone($phoneNumber);

    /**
     * @param $phone
     * @param $accept_code
     * @return mixed
     */
    public function authDriverByPhone($phone, $accept_code);

    /**
     * @param $phone
     * @return mixed
     */
    public function sendSmsAcceptCode($phone);

    /**
     * @param $phone
     * @return Collection|null
     */
    public function sendSmsAcceptCodeToDriverMobile($phone): ?Collection;

    /**
     * @param $name
     * @param $password
     * @return mixed
     */
    public function loginClientByName($name, $password);

    /**
     * @param $login
     * @param $password
     * @return mixed
     */
    public function hasExistsPersonalAdmin($login, $password);

    /**
     * @param  int  $system_worker_id
     * @return mixed
     */
    public function getSystemWorkerDataById(int $system_worker_id);

    /**
     * @param $login
     * @param $password
     * @return mixed
     */
    public function hasExistsWorker($login, $password);

    /**
     * @param  string  $login
     * @param  string  $password
     * @return array
     * @throws JsonException
     * @throws Lexcept
     */
    public function authDriver(string $login, string $password): array;

    /**
     * @param $user_data
     * @return mixed
     */
    public function getDriverToken($user_data);

    /**
     * @param  string  $model
     * @param  array  $find
     * @param  string  $role
     * @return bool
     */
    public function addRole(string $model, array $find, string $role): bool;

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function logoutDriver(Request $request): ?bool;

    /**
     * @param  string  $password
     * @return mixed
     */
    public function passwordConfirm(string $password);

    /**
     * @param  Request  $request
     * @param  array  $priority
     * @return Collection|null
     */
    public function detectUser($request, array $priority = []): ?Collection;

    /**
     * @param $request
     * @return mixed
     */
    public function atcAuth($request);

    /**
     * @return void
     */
    public function clientHasAuthGenerateHash(): void;

    /**
     * @param $phone
     * @return Collection|mixed
     * @throws Exception
     */
    public function clientMobileAuthenticate($phone);

    /**
     * @param $phone
     * @param $accept_code
     * @param $secret_id
     * @param $secret_key
     * @return Collection|mixed|null
     */
    public function clientMobileAuthAccept($phone, $accept_code);

    /**
     * DELETE OLD BEFORE AUTH TEMPORARY CLIENT
     *
     * @param  Request  $request
     * @param $client_id
     * @return void
     */
    public function beforeAuthClientRegenerate($request, $client_id = null): void;

    /**
     * @param  Client  $client
     * @return mixed
     */
    public function clientMobileLogout(Client $client);

    /**
     * @param $name
     * @param $password
     * @return mixed
     */
    public function authTerminal($name, $password): ?Collection;

    /**
     * @param  string  $nickname
     * @param  string  $pwd
     * @param  int  $terminal_id
     * @return mixed
     */
    public function authTerminalDriver(string $nickname, string $pwd, int $terminal_id);

    /**
     * @param $worker_data
     * @return array|null
     */
    public function workerBearer($worker_data): ?array;

    /**
     * @param $id
     * @param $password
     * @return bool
     */
    public function validWorkerIdPwd($id, $password): bool;

    /**
     * @param  int  $client_id
     * @param  string  $client_type
     * @param  string  $key
     * @return mixed
     */
    public function setUpdatePushKey(int $client_id, string $client_type, string $key);

    /**
     * @param  string  $email
     * @param  string  $password
     * @return Collection|null
     */
    public function clientAuthByEmail(string $email, string $password): ?Collection;
}
