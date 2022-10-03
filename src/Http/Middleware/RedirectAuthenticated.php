<?php

declare(strict_types=1);

namespace Src\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Src\Repositories\AdminCorporate\AdminCorporateContract;
use Src\Repositories\BeforeAuthClient\BeforeAuthClientContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\SuperAdmin\SuperAdminContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;

use function in_array;

/**
 * Class RedirectAuthenticated
 * @package Src\Http\Middleware
 */
class RedirectAuthenticated
{
    /**
     * AuthService constructor.
     * @param  ClientContract  $clientContract
     * @param  SystemWorkerContract  $workerContract
     * @param  DriverContract  $driverContract
     * @param  AdminCorporateContract  $adminCorporateContract
     * @param  SuperAdminContract  $superAdminContract
     * @param  BeforeAuthClientContract  $beforeAuthContract
     */
    public function __construct(
        protected ClientContract $clientContract,
        protected SystemWorkerContract $workerContract,
        protected DriverContract $driverContract,
        protected AdminCorporateContract $adminCorporateContract,
        protected SuperAdminContract $superAdminContract,
        protected BeforeAuthClientContract $beforeAuthContract
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null w $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null): mixed
    {
        if ($guard) {
            return $next($request);
        }

        $contain_guard = $this->getCurrentContainsGuard($request);

        if ($contain_guard) {
            return $this->redirect($guard, $next, $request, $contain_guard);
        }

        return $this->redirect($guard, $next, $request);
    }

    /**
     * @param  Request  $request
     * @return null|string
     */
    protected function getCurrentContainsGuard(Request $request): ?string
    {
        $guards = array_keys(config('auth.guards'));
        $auth_sessions = $request->getSession()->all();
        $current = [];
        $data = [];

        foreach ($auth_sessions as $key => $value) {
            if (Str::is('*_web_*', $key)) {
                $guard = str_replace('login_', '', substr($key, 0, strpos($key, '_web_')).'_web');
                $current[$value] = $guard;
            }
        }

        foreach ($current as $key => $cur) {
            $result = in_array($cur, $guards, true);

            if (!$result) {
                break;
            }

            switch ($cur) {
                case ('system_workers_web' && Str::contains(url()->current(), 'app/worker')):
                    $worker = $this->workerContract->where($this->workerContract->getKeyName(), '=', $key)->exists();
                    if ($worker) {
                        $data['guard'] = 'system_workers_web';
                    }

                    break;
                case ('admin_corporate_web' && Str::contains(url()->current(), 'admin/corporate')):
                    $adminCorporate = $this->adminCorporateContract->where($this->adminCorporateContract->getKeyName(), '=', $key)->exists();
                    if ($adminCorporate) {
                        $data['guard'] = 'admin_corporate_web';
                    }

                    break;
                case ('admin_super_web' && Str::contains(url()->current(), 'admin/super')):
                    $adminSuper = $this->superAdminContract->where($this->superAdminContract->getKeyName(), '=', $key)->exists();
                    if ($adminSuper) {
                        $data['guard'] = 'admin_super_web';
                    }

                    break;
                case ('clients_web' && Str::contains(url()->current(), '')):
                    $client = $this->clientContract->where($this->clientContract->getKeyName(), '=', $key)->exists();
                    if ($client) {
                        $data['guard'] = 'clients_web';
                    }

                    break;
                case ('before_clients_web' && Str::contains(url()->current(), '')):
                    $client = $this->beforeAuthContract->where($this->beforeAuthContract->getKeyName(), '=', $key)->exists();

                    if ($client) {
                        $data['guard'] = 'before_clients_web';
                    }

                    break;
                default:
            }
        }

        if (empty($data)) {
            return null;
        }

        return $data['guard'];
    }

    /**
     * @param $guard
     * @param $next
     * @param $request
     * @param  null  $contain
     * @return mixed
     * @noinspection MultipleReturnStatementsInspection
     */
    protected function redirect($guard, $next, $request, $contain = null)
    {
        if ($contain) {
            switch ($guard) {
                case ('system_workers_web' && 'system_workers_web' === $contain && Str::contains(url()->current(), 'app/worker')):
                    return redirect()->route('get_dashboard_page');
                case ('admin_corporate_web' && 'admin_corporate_web' === $contain && Str::contains(url()->current(), 'admin/corporate')):
                    return redirect()->route('admin_corporate_show_index');
                case ('admin_super_web' && 'admin_super_web' === $contain && Str::contains(url()->current(), 'admin/super')):
                    return redirect()->route('admin.super.dashboard');
                case ('before_clients_web' || ('clients_web' && 'before_clients_web' === $contain) || 'clients_web' === $contain):
                    return redirect()->route('homepage');
                default:
            }

            return $next($request);
        }

        switch ($guard) {
            case ('system_workers_web' && $contain && Str::contains(url()->current(), 'app/worker')):
                return redirect()->route('get_dashboard_page');
            case ('admin_corporate_web' && $contain && Str::contains(url()->current(), 'admin/corporate')):
                return redirect()->route('admin_corporate_show_index');
            case ('admin_super_web' && $contain && Str::contains(url()->current(), 'admin/super')):
                return redirect()->route('admin.super.dashboard');
            case (('before_clients_web' || 'clients_web') && $contain && Str::contains(url()->current(), '/')):
                return redirect()->route('homepage');
            default:
        }

        return $next($request);
    }
}
