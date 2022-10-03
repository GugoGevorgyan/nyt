<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Src\Exceptions\ExceptHelpers;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\SystemWorkerLoginRequest;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Services\Auth\AuthServiceContract;
use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 * @package Src\Http\Controllers\SystemWorker
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected string $username = 'nickname';

    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authContract;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = 'app/worker/profile';

    /**
     * @var SystemWorkerContract
     */
    protected SystemWorkerContract $workerContract;

    /**
     * AuthController constructor.
     * @param  AuthServiceContract  $authContract
     * @param  SystemWorkerContract  $systemWorkerContract
     */
    public function __construct(
        AuthServiceContract $authContract,
        SystemWorkerContract $systemWorkerContract
    ) {
        $this->authContract = $authContract;
        $this->workerContract = $systemWorkerContract;
    }

    /**
     * @return Factory|View
     */
    public function showLoginForm(): Factory|View
    {
        return view('system-worker.login');
    }

    /**
     * @param  SystemWorkerLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse|RedirectResponse|Response|void
     * @throws ValidationException
     */
    public function login(SystemWorkerLoginRequest $request)
    {
        if (!$request->validated()) {
            return redirect()->back()->with(ExceptHelpers::DANGER, [ExceptHelpers::DANGER => 'Data error']);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->guard()->attempt(['nickname' => $request->nickname, 'password' => $request->password], true)) {
            $worker = $this->workerContract->where('nickname', '=', $request->nickname)->findFirst();
            $worker->update(['logged' => 1, 'in_session' => true]);
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard((string)session('assigned_guard'));
    }

    /**
     * @param  SystemWorkerLoginRequest  $request
     * @return array
     */
    public function credentials(SystemWorkerLoginRequest $request): array
    {
        return [
            $this->username() => $request->nickname,
            'password' => $request->password
        ];
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     * @return Application|RedirectResponse|\Illuminate\Http\Response|Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('app/worker/login');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function passwordConfirm(Request $request)
    {
        return $this->authContract->passwordConfirm($request->password)?
            response(['message' => 'ok']):
            response(['message' => 'No Correct Password'], 422);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function checkPassword(Request $request)
    {
        return $this->authContract->validWorkerIdPwd($request->id, $request->password)?
            response(['message' => 'Valid']):
            response(['message' => 'Invalid data'],422);
    }
}
