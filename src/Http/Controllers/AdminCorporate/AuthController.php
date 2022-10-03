<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminCorporate;

use Src\Http\Controllers\BaseAuthController;
use Src\Http\Requests\AdminCorporate\AdminCorporateLoginRequest;
use Src\Services\Auth\AuthServiceContract;
use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 *
 * @package Src\Http\Controllers\ClientMessage
 */
class AuthController extends BaseAuthController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect clients after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/corporate';
    /**
     * @var AuthServiceContract
     */
    protected $authService;
    /**
     * @var string
     */
    protected $username = 'email';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->authService = app(AuthServiceContract::class);
    }

    /**
     * @param  AdminCorporateLoginRequest  $request
     * @return Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws ValidationException
     */
    public function login(AdminCorporateLoginRequest $request)
    {
        if (!$request->validated()) {
            return redirect()->back();
        }

        $request->request->add(['remember' => true]);

        if ($this->attemptLogin($request)) {
            $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse|Redirector
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
    public function guard(): StatefulGuard
    {
        return Auth::guard((string)session('assigned_guard'));
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
            $this->username() => $request->input('email'),
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
