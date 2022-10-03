<?php

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;

// Route for caching broadcast route auth
Route::match(['get', 'post'], 'broadcasting/auth', '\\'.BroadcastController::class.'@authenticate')
    ->middleware(['web', 'auth:admin_corporate_web']);


Route::middleware(['assign.guard:admin_corporate_web', 'guest:admin_corporate_web'])->group(
    static function () {
        Route::post('corporate-admin-login', 'AuthController@login')
            ->name('loginCorporateAdmin');
    }
);


// Controllers For Corporate Admins
Route::middleware(['auth:admin_corporate_web'])->group(
    static function () {
        Route::post('logout-personal-admin', 'AuthController@logout')
            ->middleware(['assign.guard:admin_corporate_web'])
            ->name('logoutPersonalAdmin');

        Route::get('/', 'IndexController@indexShow')
            ->name('admin_corporate_show_index');

        Route::get('get-car-classes/{company_id}/{client_id?}', 'IndexController@getCarClasses');

        Route::get('get-car-options', 'IndexController@getCarOptions')
            ->name('getCarOptions');

        Route::get('get-payment-types', 'IndexController@getPaymentTypes')
            ->name('getPaymentTypes');

        Route::get('get-coordinates/{address}', 'IndexController@getCoordinates')
            ->name('corporate_get_coordinates');

        Route::get('get-company-entities', 'IndexController@companyEntities')->name('get_entity_types_company');

        Route::post('check-client', 'IndexController@checkClientExists')
            ->name('check.client');

        //Company
        Route::get('company/info', 'CompanyController@getCompany')
            ->name('getCompany');

        Route::put('company/{id}', 'CompanyController@updateCompany')
            ->name('updateCompany');

        Route::get('get-company-phoneMask/{company_id}', 'CompanyController@getCompanyPhoneMask')
            ->name('getPhoneMask');


        //Clients
        Route::get('company/clients', 'CorporateClientController@takeClients')
            ->name('getClients');

        Route::get('company/clients/{id}', 'CorporateClientController@getClient')
            ->name('getClientInfo');

        Route::post('company/client/create', 'CorporateClientController@createCorporateClient')
            ->name('createClient');

        Route::put('company/client/update/{id}', 'CorporateClientController@updateCorporateClient')
            ->name('updateClient');

        Route::delete('company/client/delete', 'CorporateClientController@deleteCorporateClient')
            ->name('deleteClient');


        /*ClientMessage address*/
        Route::post('company/client/address/create', 'CorporateClientController@createAddress')
            ->name('corporate_create_address');

        Route::put('company/client/address/update/{address_id}', 'CorporateClientController@updateAddress')
            ->name('updatedAddress');

        Route::delete('company/client/address/delete/{address_id}', 'CorporateClientController@deleteAddress')
            ->name('deleteAddress');

        Route::get('company/clients/order-info/{id}', 'OrderController@getOrderInfo')
            ->name('getOrderInfo');

        Route::post('company/clients/scheduled-orders', 'OrderController@scheduledCorporateOrders')
            ->name('scheduledCorporateOrders')
            ->middleware('company_have_user_scheduled');

        Route::post('company/order/create', 'OrderController@createCorporateOrder')
            ->middleware('clean.numbers', 'detect_franchise_region')
            ->name('createOrderCorporate');

        //Order
        Route::post('open_order_card', 'OrderController@openOrderModal')
            ->name('corporate_open_order_modal');

        Route::post('close_order_card', 'OrderController@closeOrderModal')
            ->name('corporate_close_order_modal');

        Route::get('order/paginate', 'OrderController@getCompanyOrders')
            ->name('getCompanyOrders');

        Route::post('init_coin', 'OrderController@initCoin')
            ->middleware('detect_franchise_region')
            ->name('company_order_init_coin');

        Route::get('cities', 'OrderController@getCities')
            ->name('corporate_admin_get_cities');

        Route::get('airports', 'OrderController@getAirports')
            ->name('corporate_admin_get_airports');

        Route::get('stations', 'OrderController@getStations')
            ->name('corporate_admin_get_stations');

        Route::get('metros', 'OrderController@getMetros')
            ->name('corporate_admin_get_metros');

        Route::post('generate_excel', 'OrderController@generateExcel')
            ->name('corporate_admin_excel_create');

        Route::post('print_excel', 'OrderController@printExcel')
            ->name('corporate_admin_print_excel_create');

        Route::put('cancel_order/{order_id}/{client_id}', 'OrderController@cancelOrder')
            ->name('corporate_admin_cancel_order');

        Route::get('/{any}', 'IndexController@indexShow')->where('any', '.*');
    }
);
