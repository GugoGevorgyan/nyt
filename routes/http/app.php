<?php

/** @noinspection DuplicatedCode */

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;

// Route for caching broadcast route auth
Route::match(['get', 'post'], 'broadcasting/auth', '\\' . BroadcastController::class . '@authenticate')->middleware([
    'web',
    'auth:clients_web,before_clients_web'
]);

Route::group(['middleware' => ['set_client_middleware']], fn() => [
    Route::get('/', 'IndexController@showIndexPage')
        ->middleware(['detect_client_info', /*, 'auth:before_clients_web,clients_web'*/])
        ->name('homepage'),

    Route::post('online', 'IndexController@online')
        ->name('web_app_online'),

    Route::get('init/{lat?}/{lut?}', 'IndexController@getOrderInfo')
        ->name('orderInfo'),
]);

/**
 * ==========================================
 *              AUTH ROUTES GET
 * ==========================================
 */
Route::group([], fn() => [
    Route::match(['get', 'head'], 'login-client', 'AuthController@showLoginForm')
        ->middleware(['clean.numbers', 'check_login_form', 'guest:clients_web'])
        ->name('login_client'),

    Route::match(['get', 'head'], 'login-corporate', 'AuthController@showLoginForm')
        ->middleware(['clean.numbers', 'check_login_form', 'guest:admin_corporate_web'])
        ->name('login_corporate'),

    Route::match(['get', 'head'], 'm/auth', 'AuthController@showMobileLoginForm')
        ->middleware(['check_login_form', 'clean.numbers', 'guest:clients_web'])
        ->name('login_client_mobile'),
]);

/**
 * ==========================================
 *              AUTH REQUESTS
 * ==========================================
 */
Route::middleware(['guest:clients_web', 'clean.numbers'])->group(fn() => [
    Route::post('send_sms_code_login', 'AuthController@sendSmsCode')
        ->name('sendSmsCodeLogin'),

    Route::post('app_login_by_phone', 'AuthController@loginByPhone')
        ->middleware(['assign.guard:clients_web'])
        ->name('appLoginByPhone'),

    Route::post('app_login_by_name', 'AuthController@loginByEmail')
        ->middleware(['assign.guard:clients_web'])
        ->name('appLoginByEmail'),
]);

/**
 * CLIENT AUTH ROUTES
 */
Route::middleware(['set_client_middleware', 'detect_client_info'])->group(fn() => [
    /**
     * PROFILE
     */
    Route::group(['middleware' => ['auth:clients_web'], 'prefix' => 'profile'], fn() => [
        Route::get('/', 'ClientController@profile')
            ->name('client_profile'),

        Route::get('client/info', 'ClientController@clientInfo')
            ->name('clientInfo'),

        Route::get('client/phoneMask', 'ClientController@getclientPhoneMask')
            ->name('clientPhoneMask'),

        Route::get('client/info/created_at', 'ClientController@getClientCreatedInfo')
            ->name('clientInfoCreatedAt'),

        Route::get('client/orders', 'OrderController@getOrders')
            ->name('getOrders'),

        Route::get('client/favorite-drivers', 'ClientController@getFavoriteDrivers')
            ->name('getFavoriteDrivers'),

        Route::get('client/companies', 'ClientController@getCompanies')
            ->name('getCompanies'),

        Route::get('client/addresses', 'ClientController@getAddresses')
            ->name('getAddresses'),

        Route::get('client/preorders', 'ClientController@getPreOrders')
            ->name('getPreOrders'),

        Route::put('change-pre-date/{order_id}/{date}', 'ClientController@changePreOrders')
            ->name('changePreOrders'),

        Route::delete('cancel-pred/{order_id}', 'CLientController@cancelPreOrder')
            ->name('deletePreOrder'),

        Route::get('client/notifications', 'ClientController@getAddresses')
            ->name('getNotifications'),

        Route::put('client/info/{id}', 'ClientController@updateClientInfo')
            ->name('updateClientInfo'),

        Route::post('/client/add/address', 'ClientController@createAddress')
            ->name('createAddress'),

        Route::post('/client/address/{client_id}/{address_id}', 'ClientController@addressDelete')
            ->name('addressDelete'),

        Route::post('/client/update/password', 'ClientController@updateClientPassword')
            ->name('updateClientPassword'),

        Route::post('/client/add/password', 'ClientController@addClientPassword')
            ->name('addClientPassword'),

        Route::put('/client/update/address', 'ClientController@updateAddress')
            ->name('updateAddress'),

        Route::put('/client/address/favorite', 'ClientController@makeAddressFavorite')
            ->name('makeAddressFavorite'),

        Route::get('/get-coordinates/{address}', 'ClientController@getCoordinates')
            ->name('getCoordinates'),

        Route::get('/{any}', 'ClientController@profile')->where('any', '.*'),

    ]),

    /**
     * TAXIMETER
     */
    Route::group(['middleware' => ['auth:clients_web,before_clients_web']], fn() => [
        Route::post('init_coin', 'OrderController@getOrderPrice')
            ->middleware('detect_franchise_region')
            ->name('get_order_price'),

        Route::post('order', 'OrderController@createOrder')
            ->middleware('detect_franchise_region')
            ->name('createOrder'),

        Route::get('tariffs', 'TariffController@index')
            ->name('client_get_tariffs'),

        Route::get('transports', 'IndexController@getTransports')
            ->name('client_get_transports'),
    ]),

    /**
     * TAXIMETER AUTH
     */
    Route::group(['middleware' => 'auth:clients_web'], fn() => [
        Route::post('cancel-order', 'OrderController@cancelOrder')
            ->name('client_web_order_cancel'),

        Route::post('cancel-accept-place', 'OrderController@cancelOrderAccept')
            ->name('accept_cancel_web'),

        Route::get('get-details-assessment/{assessment}', 'OrderController@assessmentDetails')
            ->name('get_details_assessment'),

        Route::post('add_feedback', 'OrderController@addFeedbackOrder')
            ->name('add_client_feedback'),

        Route::get('continue_order/{order_id}', 'OrderController@continueOrder')
            ->name('clinet_continue_order'),

        Route::post('logout', 'AuthController@logout')
            ->middleware(['assign.guard:clients_web'])
            ->name('logoutClient'),

        Route::post('add_card', 'ClientController@addCard')
            ->name('client_add_card'),
    ]),

// MOBILE WEB
    Route::group(['prefix' => 'm', 'middleware' => []], fn() => [
        Route::get('/', 'IndexController@mobileIndex')
            ->middleware(['auth:clients_web,before_clients_web'])
            ->name('mobile_index'),

        Route::get('auth', 'IndexController@mobileAuth')
            ->middleware(['guest:clients_web,before_clients_web'])
            ->name('mobile_auth'),

        Route::post('init_coin', 'OrderController@getMobileOrderPrice')
            ->middleware('detect_franchise_region')
            ->name('get_mobile_order_price'),

        Route::get('preorders', 'OrderController@showPreorders')
            ->name('client_mobile_show_preorders'),

        Route::get('get_preorders', 'OrderController@getPreorders')
            ->name('client_mobile_get_preorders'),

        Route::delete('preorder/{order_id}', 'OrderController@deletePreorder')
            ->name('client_mobile_delete_preorder'),

        Route::get('/{any}', 'IndexController@mobileIndex')->where('any', '.*'),
    ]),
]);
