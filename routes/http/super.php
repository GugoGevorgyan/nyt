<?php

declare(strict_types=1);

use Illuminate\Broadcasting\BroadcastController;

// Route for caching broadcast route auth
Route::match(['get', 'post'], 'broadcasting/auth', '\\'.BroadcastController::class.'@authenticate')->middleware(['web', 'auth:admin_super_web']);

Route::middleware(['guest:admin_super_web'])->group(fn() => [
    Route::get('/', fn() => redirect('admin/super/login')),

    Route::get('/login', 'AuthController@showLoginForm')
        ->middleware('check_login_form')
        ->name('show.admin.super.login.form'),

    Route::post('login', 'AuthController@login')
        ->middleware('assign.guard:admin_super_web')
        ->name('admin.super.login'),

]);

Route::middleware(['auth:admin_super_web'])->group(fn() => [
    Route::post('logout', 'AuthController@logout')->middleware(['assign.guard:admin_super_web'])->name('admin.super.logout'),
    Route::get('dashboard', 'DashboardController@index')->name('admin.super.dashboard'),

    /*get*/
    Route::group(['prefix' => 'get'], fn() => [
        Route::get('countries', 'DashboardController@getCountries')->name('admin.super.getCountries'),
        Route::get('regions/{country_id}', 'DashboardController@getRegions')->name('admin.super.getRegions'),
        Route::get('cities/{region_id}', 'DashboardController@getCities')->name('admin.super.getCities'),
        Route::get('roles/{module_id}', 'DashboardController@getRoles')->name('admin.super.getRoles'),
        Route::get('payment-types', 'DashboardController@getPaymentTypes')->name('admin.super.getPaymentTypes'),
        Route::get('tariff-types', 'DashboardController@getTariffTypes')->name('admin.super.getTariffTypes'),
        Route::get('car-classes', 'DashboardController@getCarClasses')->name('admin.super.getCarClasses'),
        Route::get('areas/{country_id?}/{region?}', 'DashboardController@getAreas')->name('admin.super.getAreas'),
        Route::get('regions-cities-tariffs', 'DashboardController@getRegionsCitiesTariffs')->name('admin.super.getRegionsCitiesTariffs'),
        Route::get('tariffs', 'DashboardController@getTariffs')->name('admin.super.getTariffs'),
        Route::get('modules', 'DashboardController@getModules')->name('admin.super.getModules'),
        Route::get('rent-times','DashboardController@getAllRentTimes')->name('admin.super.getAllRentTimes'),
        Route::get('area-regions/{area_id}','DashboardController@getAreaRegions')->name('admin.super.getAreaRegions')
    ]),

    /* Regions */
    Route::group(['prefix' => 'regions'], fn() => [
        Route::get('/', 'RegionsController@index')->name('roles.regions'),
        Route::get('paginate', 'RegionsController@paginate')->name('regions.paginate'),
        Route::post('/', 'RegionsController@store')->name('roles.region.save'),
        Route::put('/{region}', 'RegionsController@update')->name('regions.update'),
        Route::delete('/{region}', 'RegionsController@destroy')->name('regions.destroy'),
        Route::delete('multiple', 'RegionsController@destroyMultiple')->name('regions.destroy.multiple'),
    ]),

    // Cities
    Route::group(['prefix' => 'cities'], fn() => [
        Route::get('/', 'RegionsController@cities')->name('admin.super.cities'),
        Route::get('pager', 'RegionsController@citiesPager')->name('admin.super.cities.pager'),
    ]),

    /* Modules */
    Route::group(['prefix' => 'modules'], fn() => [
        Route::delete('multiple', 'ModulesController@destroyMultiple')->name('modules.destroy.multiple'),
        Route::options('dissociate/role/{role}', 'ModulesController@dissociateRole')->name('module.dissociate.role'),
        Route::options('module}/associate/role/{role}', 'ModulesController@associateRole')->name('module.associate.role'),
        Route::resource('/', 'ModulesController')->except('CreateComponents', 'edit', 'show'),
    ]),

    /* Roles */
    Route::group(['prefix' => 'roles'], fn() => [
        Route::get('/', 'RolesController@index')->name('roles.index'),
        Route::get('paginate', 'RolesController@paginate')->name('roles.paginate'),
        Route::post('', 'RolesController@store')->name('roles.store'),
        Route::put('/{role}', 'RolesController@update')->name('roles.update'),
        Route::delete('multiple', 'RolesController@destroyMultiple')->name('roles.destroy.multiple'),
        Route::delete('/{role}', 'RolesController@destroy')->name('roles.destroy'),
    ]),

    /* Permissions */
    Route::group(['prefix' => 'permissions'], fn() => [
        Route::get('/', 'PermissionsController@index')->name('permissions.index'),
        Route::get('paginate', 'PermissionsController@paginate')->name('permissions.paginate'),
        Route::post('/', 'PermissionsController@store')->name('permissions.store'),
        Route::put('/{permission}', 'PermissionsController@update')->name('permissions.update'),
        Route::delete('/{permission}', 'PermissionsController@destroy')->name('permissions.destroy'),
        Route::delete('multiple', 'PermissionsController@destroyMultiple')->name('permissions.destroy.multiple'),
    ]),

    /* Franchises */
    Route::group(['prefix' => 'franchises'], fn() => [
        Route::get('/', 'FranchisesController@index')->name('franchises.index'),
        Route::get('paginate', 'FranchisesController@paginate')->name('franchises.paginate'),
        Route::get('create', 'FranchisesController@create')->name('franchises.create'),
        Route::post('/', 'FranchisesController@store')->name('franchises.store'),
        Route::delete('/{franchise_id}', 'FranchisesController@destroy')->name('franchises.delete'),
        Route::get('/{franchise_id}/edit', 'FranchisesController@edit')->name('franchises.edit'),
        Route::put('/{franchise_id}', 'FranchisesController@update')->name('franchises.update'),
        Route::post('legal-entity', 'FranchisesController@legalEntityStore')->name('franchises.legalEntityStore'),
    ]),

    /*Franchise admins*/
    Route::group(['prefix' => 'franchise-admins'], fn() => [
        Route::post('/', 'FranchisesController@adminStore')->name('franchises.adminStore'),
        Route::put('/{system_worker_id}', 'FranchisesController@adminUpdate')->name('franchises.adminUpdate'),
        Route::delete('/system_worker_id}', 'FranchisesController@adminDelete')->name('franchises.adminDelete'),
    ]),

    /*Franchise phones*/
    Route::delete('franchise-phones/{franchise_phone_id}', 'FranchisesController@phoneDelete')->name('franchises.phoneDelete'),
    Route::delete('franchise-sub-phones/{franchise_sub_phone_id}', 'FranchisesController@subPhoneDelete')->name('franchises.subPhoneDelete'),

    /* Tariffs */
    Route::group(['prefix' => 'tariff'], fn() => [
        Route::get('/', 'TariffController@index')->name('tariff.index'),
        Route::get('paginate', 'TariffController@paginate')->name('tariff.paginate'),
        Route::get('create', 'TariffController@create')->name('tariff.create'),
        Route::post('store', 'TariffController@store')->name('tariff.store'),
        Route::get('edit/{tariff_id}', 'TariffController@edit')->name('tariff.edit'),
        Route::put('update/{tariff_id}', 'TariffController@update')->name('tariff.update'),
        Route::delete('destroy/{tariff_id}', 'TariffController@destroy')->name('tariff.destroy'),
        Route::post('copy/{tariff_id}', 'TariffController@copy')->name('tariff.copy'),
        Route::get('alt_tariffs/{car_class_id}/{country_id}','DashboardController@getAltTariffs')->name('admin.super.getAltTariffs')
    ]),

    /* Areas */
    Route::group(['prefix' => 'area'], fn() => [
        Route::get('/', 'AreaController@index')->name('area.index'),
        Route::post('create', 'AreaController@create')->name('area.create'),
        Route::put('update/{area_id}', 'AreaController@update')->name('area.update'),
        Route::delete('destroy/{area_id}', 'AreaController@destroy')->name('area.destroy'),
    ]),

    /*Stations*/
    Route::group(['prefix' => 'station', 'namespace' => 'Stations'], fn() => [
        Route::get('airports/paginate', 'AirportController@getAirports')->name('admin_super_airports_paginate'),
        Route::post('airport/create', 'AirportController@createAirport')->name('admin_super_airport_create'),
        Route::put('airport/update/{airport}', 'AirportController@updateAirport')->name('admin_super_airport_update'),
        Route::delete('airport/delete/{airport}', 'AirportController@deleteAirport')->name('admin_super_airport_delete'),

        Route::get('metros/paginate', 'MetroController@getMetros')->name('admin_super_metros_paginate'),
        Route::post('metro/create', 'MetroController@createMetro')->name('admin_super_metro_create'),
        Route::put('metro/update/{metro}', 'MetroController@updateMetro')->name('admin_super_metro_update'),
        Route::delete('metro/delete/{metro}', 'MetroController@deleteMetro')->name('admin_super_metro_delete'),

        Route::get('railways/paginate', 'RailwayController@getRailways')->name('admin_super_railway_paginate'),
        Route::post('railway/create', 'RailwayController@createRailway')->name('admin_super_railway_create'),
        Route::put('railway/update/{metro}', 'RailwayController@updateRailway')->name('admin_super_railway_update'),
        Route::delete('railway/delete/{railway}', 'RailwayController@deleteRailway')->name('admin_super_railway_delete'),

        Route::get('/{any}', 'StationController@index')->name('admin_super_station_airport_show')->where('any', '.*'),
    ]),
]);
