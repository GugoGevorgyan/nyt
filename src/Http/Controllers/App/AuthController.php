<?php

declare(strict_types=1);

namespace Src\Http\Controllers\App;

use Auth;
use Eloquent;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Src\Http\Controllers\BaseAuthController;
use Src\Http\Requests\Client\ClientLoginByName;
use Src\Http\Requests\Client\ClientLoginByPhone;
use Src\Http\Requests\Client\ClientPhoneNumberRequest;
use Src\Services\Auth\AuthServiceContract;

use function route;

/**
 * Class AuthController
 *
 * @package Src\Http\Controllers\ClientMessage
 */
class AuthController extends BaseAuthController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating clients for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect clients after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var Eloquent
     */
    protected $authContract;

    /**
     * Create a new controller instance.
     *
     * @param  AuthServiceContract  $authClientContract
     */
    public function __construct(AuthServiceContract $authClientContract)
    {
        $this->authContract = $authClientContract;
    }

    /**
     * @return Factory|View
     */
    public function showLoginForm()
    {
        return view('app.login');
    }

    /**
     * @return Factory|View
     */
    public function showMobileLoginForm()
    {
        return view('app.mobile.auth');
    }

    /**
     * @param  ClientPhoneNumberRequest  $request
     * @return bool|ResponseFactory|Response
     */
    public function sendSmsCode(ClientPhoneNumberRequest $request)
    {
        $phone = $request->input('phone');

        $send_sms = $this->authContract->sendSmsAcceptCode($phone);

        if (!$send_sms) {
            return false;
        }

        return response(['message' => trans('messages.accept_code', ['phone' => $phone])]);
    }

    /**
     * @param  ClientLoginByPhone  $request
     * @return Application|RedirectResponse|Redirector
     */
    public function loginByPhone(ClientLoginByPhone $request): Redirector|Application|RedirectResponse
    {
        url()->previous() === route('mobile_auth') ?: $this->redirectTo = '/m';

        if (!$request->validated()) {
            return redirect()->back();
        }

        $client = $this->authContract->loginClientByPhone($request->validated()['phone']);

        if (!$client) {
            return redirect()->back();
        }

        $this->authContract->beforeAuthClientRegenerate($request, $client);

        return redirect($this->redirectPath());
    }

    /**
     * @param  ClientLoginByName  $request
     * @return JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function loginByEmail(ClientLoginByName $request): JsonResponse|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        url()->previous() === route('mobile_auth') ?: $this->redirectTo = '/m';

        if (!$request->validated()) {
            return redirect()->back();
        }

        $this->authContract->beforeAuthClientRegenerate($request);

        if ($this->guard()->attempt(['email' => $request->email, 'password' => $request->password], true)) {
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
     * Log the user out of the application.w
     *
     * @param  Request  $request
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request)/*: Response*/
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
