<?php

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;

// Route for caching broadcast route auth
Route::match(['get', 'post'], 'broadcasting/auth', '\\'.BroadcastController::class.'@authenticate')->middleware(['api', 'auth:drivers_api']);

Route::middleware(['guest:drivers_api', 'assign.guard:drivers_api'])->group(fn() => [
    Route::post('auth/accept_code', 'AuthController@sendSmsAcceptCode')
        ->name('drivers_auth_send_sms'),

    Route::post('auth/phone', 'AuthController@authByPhone')
        ->name('auth-by-phone'),

    Route::post('auth', 'AuthController@auth')
        ->name('get-current-user'),

    Route::post('refresh-token', 'AuthController@refreshToken')
        ->name('refresh-token'),
]);

Route::middleware(['auth:drivers_api'])->group(fn() => [
    Route::post('logout', 'AuthController@logout'),

    Route::put('online', 'DriverController@changeOnline')
        ->name('driver_online_changer'),

// PROFILE
    Route::get('initial_info', 'DriverController@profileInitialInfo')
        ->name('driver_profile_initial_info'),

    Route::put('franchise/select', 'FranchisesController@update')
        ->name('show-update-profile'),

    Route::put('profile/update/{driver_id}', 'DriverController@updateProfile')
        ->name('update-profile'),

    Route::post('image_load', 'DriverController@profileImageUpload')
        ->name('driver_profile_image_upload'),

    Route::delete('delete/{driver_id}', 'DriverController@delete')
        ->name('delete-driver'),

    Route::post('toggle_car_class', 'DriverController@toggleCarClass')
        ->name('driver_toggle_car_class'),

    Route::post('toggle_car_option', 'DriverController@toggleCarOption')
        ->name('driver_toggle_car_option'),

// Orders Manipulate
    Route::get('order_list/route/{completed_order_id}', 'OrderController@getOrderTrajectory')
        ->name('driver_get_completed_order_trajectory'),

    Route::get('order_list/{take?}/{skip?}', 'OrderController@getOrders')
        ->name('driver_order_list'),

    Route::get('day_orders_info', 'OrderController@getDaysOrderInfo')
        ->name('driver_day_orders_info'),

    Route::post('add_favorite_address', 'DriverController@addFavoriteAddress')
        ->name('driver_add_favorite_address'),

    Route::post('select_favorite', 'DriverController@selectFavorite')
        ->name('driver_select_favorite'),

// DRIVER AFTER ORDER ACCEPTED
    Route::post('order_late', 'OrderController@lateOrder')
        ->name('driver_order_late'),

    Route::get('order_reject_options/{order_id}', 'OrderController@rejectOptions')
        ->name('driver_get_reject_options'),

    Route::post('order_reject', 'OrderController@orderReject')
        ->name('driver_order_reject'),

    Route::post('order_ready', 'DriverController@driverReady')
        ->middleware(['check_waybill'])
        ->name('driverOrderReady'),

    Route::post('prepare_common_order', 'OrderController@prepareCommonOrder')
        ->name('driver_prepare_common_order'),

    Route::get('common_order_acceptance/{order_id}/{hash}/{accept}', 'OrderController@selectCommonOrder')
        ->name('driver.select.common.order'),

    Route::get('order_acceptance/{order_id}/{hash}/{accept?}', 'OrderController@responseShippedOrder')
        ->name('driver.response.shipped.order'),

    Route::get('order_on_way/{order_id}/{hash}/{selected_route?}/{accept?}', 'OrderController@responseGoToOrder')
        ->name('driver.response.on.way.order'),

    Route::get('order_in_place/{order_id}/{hash}', 'OrderController@responseInPlaceOrder')
        ->name('driver.response.in_place.order'),

    Route::get('order_on_start_select_route/{order_id}/{hash}/{selected_route}', 'OrderController@responseInStartSelectRoute')
        ->name('driver.response.on_start.select.route'),

    Route::get('order_on_start/{order_id}/{hash}/{route_or_to_lat?}|{to_lut?}', 'OrderController@responseInStartOrder')
        ->middleware('detect_franchise_region')
        ->name('driver.response.on.start.order'),

    Route::get('order_on_end/{order_id}/{hash}', 'OrderController@responseOrderOnEnd')
        ->name('driver.response.on.end.order'),

    Route::get('get_assessment_types/{order_id}/{assessment}', 'OrderController@getAssessmentType')
        ->name('driver_order_get_assessment'),

    Route::post('add_feedback', 'OrderController@orderFeedback')
        ->name('driver_order_feedback'),

    Route::post('order_on_pause', 'DriverController@orderPause')
        ->name('driver_order_pause'),

    Route::get('real_state', 'DriverController@state')
        ->name('driver_real_state'),

    Route::get('transports_point/{city?}', 'DriverController@getTransportsStations')
        ->name('driver_mobile_get_transports_stations'),

    Route::get('commons', 'DriverController@getCommonOrders')
        ->name('driver_get_common_orders'),

    Route::get('commons_armor', 'DriverController@getCommonArmorsOrders')
        ->name('driver_get_armor_orders'),

    Route::put('common_cancel/{order_id}', 'DriverController@commonOrderCancel')
        ->name('driver_cancel_common_orders'),

    Route::match(['post', 'put'], 'push_uid', 'AuthController@setUpdatePush')
        ->name('driver_set_update_push_key'),

    Route::get('get_api_keys/{type}/{old_key?}', 'DriverController@getApiKeys')
        ->name('driver_mob_get_api_keys'),
]);
