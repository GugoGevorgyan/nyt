<?php

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;

/**
 * ==========================================
 *              BROADCAST FOR CACHING
 * ==========================================
 */
Route::match(['get', 'post'], 'broadcasting/auth', '\\'.BroadcastController::class.'@authenticate')->middleware(['api', 'auth:clients_api']);

/**
 * ==========================================
 *              AUTH REQUEST
 * ==========================================
 */
Route::group(['middleware' => ['guest:clients_api', 'assign.guard:clients_api']], fn() => [
    Route::post('register', 'AuthController@register'),
    Route::post('auth', 'AuthController@login'),
    Route::post('_auth', 'AuthController@loginByData')
]);

/**
 * ==========================================
 *              APP REQUEST
 * ==========================================
 */
Route::middleware(['auth:clients_api'])->group(fn() => [
    Route::post('logout', 'AuthController@logout')
        ->name('mobile_app_logout'),

    Route::post('init_open', 'AppController@open')
        ->middleware(['detect_client_info', 'detect_franchise_region'])
        ->name('mobile_open_app'),

    Route::post('init_coin', 'AppController@getOrderPrice')
        ->middleware('detect_franchise_region')
        ->name('mobile_get_order_price'),

    Route::post('init_order', 'OrderController@createOrder')
        ->middleware('detect_franchise_region')
        ->name('mobile_create_order'),

    Route::post('cancel_order', 'OrderController@cancelOrder')
        ->name('client.api.order.cancel'),

    Route::post('cancel-accept-place', 'OrderController@cancelOrderAccept')
        ->name('accept_cancel_api'),

    Route::get('get_details_assessment/{assessment?}', 'OrderController@assessmentDetails')
        ->name('mobile_get_details_assessment'),

    Route::post('add_feedback', 'OrderController@addFeedbackOrder')
        ->name('add_mobile_client_feedback'),

    Route::get('real_state', 'AppController@getState')
        ->name('get_mobile_client_state'),

    Route::get('orders/{skip?}/{take?}', 'OrderController@getOrders')
        ->name('get_mobile_client_orders'),

    Route::get('order_detail/{order_id}', 'OrderController@orderDetail')
        ->name('get_mobile_client_order_detail'),

    Route::get('address/{favorite?}', 'AppController@getFavoriteAddress')
        ->name('get_mobile_client_favorite_address'),

    Route::post('address', 'AppController@addAddress')
        ->name('add_mobile_client_favorite_address'),

    Route::put('address/{address_id}', 'AppController@editAddress')
        ->name('edit_mobile_client_favorite_address'),

    Route::delete('address/{address_id}', 'AppController@deleteAddress')
        ->name('delete_mobile_client_favorite_address'),

    Route::get('preorders/{skip?}/{take?}', 'OrderController@getPreorders')
        ->name('get_mobile_client_preorders'),

    Route::delete('preorder/{order_id}', 'OrderController@deletePreorder')
        ->name('delete_mobile_client_preorders'),

    Route::get('transports_point/{city?}', 'AppController@getTransportsStations')
        ->name('client_mobile_get_transports_stations'),

    Route::get('c_settings', 'AppController@getSettings')
        ->name('client_mobile_get_settings'),

    Route::put('c_settings', 'AppController@editSettings')
        ->name('client_mobile_edit_settings'),

    Route::match(['post', 'put'], 'push_uid', 'AuthController@pushKeySet')
        ->name('client_mob_set_push_key'),

    Route::get('get_api_keys/{type}/{old_key?}', 'AppController@getApiKeys')
        ->name('client_mob_get_api_keys'),

    Route::match(['post', 'put'], 'push_uid', 'AuthController@setUpdatePush')
        ->name('client_set_update_push_key'),
]);
