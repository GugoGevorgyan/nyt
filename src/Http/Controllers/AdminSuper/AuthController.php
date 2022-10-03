<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\AdminSuperLoginRequest;
use Src\Services\SuperAdmin\SuperAdminServiceContract;
use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class AuthController
 * @package Src\Http\Controllers\SuperFranchiser
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var SuperAdminServiceContract
     */
    protected SuperAdminServiceContract $superAdminService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/admin/super/dashboard';

    /**
     * @var string
     */
    protected string $username = 'name';

    /**
     * AuthController constructor.
     * @param  SuperAdminServiceContract  $superFranchiserServiceContract
     */
    public function __construct(SuperAdminServiceContract $superFranchiserServiceContract)
    {
        $this->superAdminService = $superFranchiserServiceContract;
    }

    /**
     * @return Factory|View
     */
    public function showLoginForm()
    {
        return view('admin-super.auth.login');
    }

    /**
     * @param  AdminSuperLoginRequest  $request
     * @return void
     * @throws ValidationException
     */
    public function auth(AdminSuperLoginRequest $request)
    {
        $this->login($request);
    }

    /**
     * @param $request
     * @return RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws ValidationException
     */
    public function login(AdminSuperLoginRequest $request)
    {
        if (!$request->validated()) {
            return redirect()->back();
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $admin = $this->superAdminService
            ->checkComparePassword($request->input('name'), $request->input('password'));

        if (!$admin) {
            return $this->sendFailedLoginResponse($request);
        }

        $request->request->add(['remember' => true]);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(session()->get('assigned_guard'));
    }

    /**
     * @return string
     */
    public function redirectTo(): string
    {
        return $this->redirectTo;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  Request  $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return [
            $this->username() => $request->input('name'),
            'password' => $request->input('password')
        ];
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }
}
