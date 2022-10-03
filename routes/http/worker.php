<?php

declare(strict_types=1);


use Illuminate\Broadcasting\BroadcastController;

// Route for caching broadcast route auth
Route::match(['get', 'post'], 'broadcasting/auth', '\\'.BroadcastController::class.'@authenticate')->middleware(['web', 'auth:system_workers_web']);

/*Test*/
Route::get('test', 'TestController@index');
Route::get('test-driver-coords', 'TestController@driversCoordsUpdate');
Route::get('test-driver-status', 'TestController@driversStatusUpdate');
Route::get('test-order-common', 'TestController@orderCommon');
Route::get('test-driver-shipment/{driver_id}/{order_id}', 'TestController@driverShipment');
Route::get('test-driver-shipment-update/{shipment_id}', 'TestController@driverShipmentUpdate');
Route::get('test-order', 'TestController@ordersStatusUpdate');
Route::get('test-order/{order_id}', 'TestController@orderStatusUpdate');
Route::get('test-car', 'TestController@carUpdate');
Route::get('test-order-accept/{driver_id}/{order_id}', 'TestController@orderAccept');

/**
 *  LOGIN
 */
Route::middleware(['guest:system_workers_web'])->group(fn() => [
    Route::get('/', fn() => redirect('app/worker/login')),

    Route::get('login', 'AuthController@showLoginForm')->name('system-show-login-form'),
    Route::post('system-login', 'AuthController@login')->middleware(['assign.guard:system_workers_web'])->name('system-login'),
]);

/**
 *  DASHBOARD
 */
Route::middleware(['auth:system_workers_web', 'guard_detect', 'worker_in_session'])->group(fn() => [
    Route::get('/', fn() => redirect('app/worker/dashboard')),

    Route::post('check_pwd', 'AuthController@checkPassword')->name('system_worker_check_password'),
    Route::post('system-logout', 'AuthController@logout')->middleware(['assign.guard:system_workers_web'])->name('system-logout'),
    Route::get('dashboard', 'DashboardController@showDashboardPage')->name('get_dashboard_page'),
    Route::post('password_confirm', 'AuthController@passwordConfirm'),
    Route::post('check/unique', 'ValidatorController@unique'),

    // GETTERS
    Route::group(['prefix' => 'get'], fn() => [
        Route::get('franchise-entities-ie', 'GetController@franchiseEntitiesIe')->name('get_franchise_entities_ie'),
        Route::get('franchise-entities-not-ie', 'GetController@franchiseEntitiesNotIe')->name('get_franchise_entities_not_ie'),
        Route::get('entity-types', 'GetController@entityTypes')->name('get_entity_types'),
        Route::get('countries', 'GetController@countries')->name('get_countries'),
        Route::get('regions/{country_id?}', 'GetController@regions')->name('get_regions'),
        Route::get('cities/{region_id}', 'GetController@cities')->name('worker_get_cities'),
        Route::get('park-managers', 'GetController@parkManagers')->name('get_park_managers'),
        Route::get('car-options', 'GetController@carOptions')->name('get_car_options'),
        Route::get('payment-methods', 'GetController@paymentTypes')->name('get_payment_methods'),
        Route::get('car-classes', 'GetController@carClasses')->name('get_car_classes'),
        Route::get('driver-statuses', 'GetController@driverStatuses')->name('get_driver_statuses'),
        Route::get('order-statuses', 'GetController@orderStatuses')->name('get_order_statuses'),
        Route::get('order-types', 'GetController@orderTypes')->name('get_order_types'),
        Route::get('railway-stations', 'GetController@railwayStations')->name('get_railway_stations'),
        Route::get('metros', 'GetController@metros')->name('get_metros'),
        Route::get('airports', 'GetController@airports')->name('get_airports'),
        Route::get('franchise-modules', 'GetController@franchiseModules')->name('get_franchise_modules'),
        Route::get('franchise-module-roles/{module_id}', 'GetController@franchiseModuleRoles')->name('get_franchise_module_roles'),
        Route::get('permissions/{role_id}', 'GetController@permissions')->name('get_permissions'),
    ]),

    // ORDER
    Route::get('order/info/{order_id}', 'OrderController@info')->name('get_order_info'),

    // PROFILE
    Route::group(['prefix' => 'profile'], fn() => [
        Route::get('/', 'ProfileController@index')->name('get_profile'),
        Route::get('complaints', 'ProfileController@complaints')->name('profile_complaints'),
        Route::put('update/info', 'ProfileController@update')->name('profile_update'),
        /*Route::get('profile/{worker_id}', 'ProfileController@viewProfile')->name('view_profile'),*/
    ]),

    // DRIVER CANDIDATES
    Route::group(['prefix' => 'driver-candidates'], fn() => [
        Route::get('/', 'DriverCandidateController@index')->name('get_driver_candidates'),
        Route::post('check-license', 'DriverCandidateController@checkLicense')->name('candidates_check_license'),
        Route::get('paginate', 'DriverCandidateController@paginate')->name('candidates-paginate'),
        Route::get('create', 'DriverCandidateController@create')->name('driver_candidates_create'),
        Route::post('store', 'DriverCandidateController@store')->name('driver_candidates_store'),
        Route::get('edit/{candidate_id}', 'DriverCandidateController@edit')->name('driver_candidates_edit'),
        Route::put('update/{candidate_id}', 'DriverCandidateController@update')->name('driver_candidates_update'),
        Route::post('driver-create', 'DriverCandidateController@candidateCreateDriver')->name('candidate_create_driver'),
        Route::delete('delete/{candidate_id}', 'DriverCandidateController@delete')->name('driver_candidates_delete'),
        Route::post('deletes', 'DriverCandidateController@deleteMany')->name('driver_candidates_delete_many'),
        Route::get('download-contract-passport-scan/{info_id}', 'DriverCandidateController@downloadPassportScan')->name('driver_candidates_download_psp_scan'),
    ]),

    // PARKS
    Route::group(['prefix' => 'parks'], fn() => [
        Route::get('/', 'ParkController@index')->name('get_parks'),
        Route::get('paginate', 'ParkController@paginate')->name('show.parks'),
        Route::post('save-create', 'ParkController@store')->name('save_create_parks'),
        Route::put('edit-update/{park_id}', 'ParkController@update')->name('personal-park-edit-update'),
        Route::delete('delete/{park_id}', 'ParkController@delete')->name('personal-park-edit-delete'),
    ]),

    // SYSTEM WORKERS
    Route::group(['prefix' => 'workers'], fn() => [
        Route::get('/', 'WorkerController@index')->name('system_workers_index'),
        Route::get('paginate', 'WorkerController@paginate')->name('system_workers_paginate'),
        Route::get('create', 'WorkerController@create')->name('system_workers_create'),
        Route::post('store', 'WorkerController@store')->name('system_workers_store'),
        Route::get('edit/{system_worker_id}', 'WorkerController@edit')->name('system_workers_edit'),
        Route::put('update/{system_worker_id}', 'WorkerController@update')->name('system_workers_update'),
        Route::post('destroy', 'WorkerController@destroy')->name('system_workers_destroy'),
        Route::post('destroy-multiple', 'WorkerController@destroyMultiple')->name('system_workers_destroy_multiple'),
    ]),

    // Traffic safety
    Route::group(['prefix' => 'traffic-safety'], fn() => [
        Route::get('/', 'TrafficSafetyController@index')->name('traffic_safety'),
        Route::get('paginate', 'TrafficSafetyController@paginate')->name('traffic_safety_paginate'),
        Route::get('create', 'TrafficSafetyController@create')->name('traffic_safety_create'),
        Route::post('store', 'TrafficSafetyController@store')->name('traffic_safety_store'),
        Route::get('edit/{car_id}', 'TrafficSafetyController@edit')->name('traffic_safety_edit'),
        Route::put('update/{car}', 'TrafficSafetyController@update')->name('traffic_safety_update'),
        Route::post('inspection/update', 'TrafficSafetyController@updateInspection')->name('traffic_safety_update_inspection'),
        Route::post('insurance/update', 'TrafficSafetyController@updateInsurance')->name('traffic_safety_update_insurance'),
        Route::post('park/update', 'TrafficSafetyController@updatePark')->name('traffic_safety_update_park'),
        Route::post('status/update', 'TrafficSafetyController@updateStatus')->name('traffic_safety_update_status'),
        Route::get('crashes/{car}', 'TrafficSafetyController@getCrashes')->name('traffic_safety_get_crashes'),
        Route::post('crash/create', 'TrafficSafetyController@createCrash')->name('traffic_safety_crate_crash'),
        Route::delete('crash/delete/{crash}', 'TrafficSafetyController@deleteCrash')->name('traffic_safety_delete_crash'),
        Route::get('download-scan/{car_id}/{type}', 'TrafficSafetyController@downloadScan')->name('traffic_safety_download_scans'),
    ]),

    // Park Management route
    Route::group(['prefix' => 'park-management'], fn() => [
        Route::get('/', 'ParkManagementController@index')->name('park_management'),
        Route::get('paginate', 'ParkManagementController@paginate')->name('park_management_paginate'),
        Route::post('free-drivers', 'ParkManagementController@freeDrivers')->name('park_management_free_drivers'),
        Route::post('status/update', 'ParkManagementController@statusUpdate')->name('park_management_status_update'),
        Route::post('print-contract', 'ParkManagementController@printContract')->name('park_management_print_contract'),
        Route::post('remove_driver/{driver_id}/{car_id}', 'ParkManagementController@removeDriver')->name('park_management_remove_driver'),
        Route::post('sign-contract', 'ParkManagementController@signContract')->name('park_management_signing_sign'),
        Route::get('download-contract/{contract_id}', 'ParkManagementController@downloadContract')->name('park_management_download_sign'),
//        Route::post('/update-driver-car', 'ParkManagementController@updateDriverCar')->name('park-management_update_driver_car'),
    ]),

    // Waybills
    Route::group(['prefix' => 'waybills'], fn() => [
        Route::get('/', 'WaybillController@getWaybillsIndex')->name('get_waybills'),
        Route::get('paginate', 'WaybillController@getWaybillsPaginate')->name('get_waybills_paginate'),
        Route::get('info/{waybill_id}', 'WaybillController@getWaybillInfo')->name('get_waybill_info'),
        Route::get('images/{waybill_id}', 'WaybillController@getWaybillImages')->name('get_waybill_images'),
        Route::put('annul/{waybill_id}', 'WaybillController@annulWaybill')->name('annul_waybill'),
        Route::get('print/{waybill_id}', 'WaybillController@printWaybill')->name('print_waybill'),
        Route::get('search-drivers/{search}', 'WaybillController@searchDrivers')->name('search_drivers'),
        Route::post('create', 'WaybillController@create')->name('waybill_create'),
        Route::put('checked/{waybill_id}/{checked}', 'WaybillController@waybillToggleChecked')->name('waybill_toggle_checked'),
        Route::post('restore-current', 'WaybillController@restoreCurrent')->name('waybill_restore_current'),
    ]),

    // FEEDBACKS
    Route::group(['prefix' => 'feedbacks'], fn() => [
        Route::get('/', 'FeedbackController@index')->name('feedbacks_index'),
        Route::get('paginate', 'FeedbackController@paginate')->name('feedbacks_paginate'),
    ]),

    // DRIVERS
    Route::group(['prefix' => 'drivers'], fn() => [
        Route::get('/', 'DriverController@index')->name('drivers_index'),
        Route::get('paginate', 'DriverController@paginate')->name('drivers_paginate'),
        Route::post('block', 'DriverController@blockDriver')->name('drivers_block'),
        Route::post('un_block', 'DriverController@unBlockDriver')->name('drivers_unblock'),
        Route::post('update_driver/{driver_id}/{driver_info_id}', 'DriverController@updateDriver')->name('update_driver'),
        Route::get('dr_road_by_date/{driver_id}/{date?}', 'DriverController@getRoadDriver')->name('drivers_road_by_day'),
    ]),

    // COMPANY
    Route::group(['prefix' => 'company'], fn() => [
        Route::get('/', 'CompanyController@showIndex')->name('show_company_index'),
        Route::get('paginate', 'CompanyController@companyPaginate')->name('company_paginate'),
        Route::get('create', 'CompanyController@companyCreate')->name('company_create'),
        Route::get('edit/{company_id}', 'CompanyController@companyEdit')->name('company_edit'),
        Route::put('update/{company_id}', 'CompanyController@companyUpdate')->name('company_update'),
        Route::post('store', 'CompanyController@companyStore')->name('company_store'),
        Route::delete('destroy/{company_id}', 'CompanyController@deleteCompany')->name('company_delete'),
        Route::get('get-tariffs', 'CompanyController@getTariffs')->name('company_get_tariffs'),
        Route::post('set-tariffs', 'CompanyController@setTariff')->name('company_set_tariffs'),
    ]),

    // Legal entity
    Route::group(['prefix' => 'legal-entity'], fn() => [
        Route::get('/', 'LegalEntityController@index')->name('legal_entity_index'),
        Route::get('paginate', 'LegalEntityController@paginate')->name('legal_entity_paginate'),
        Route::get('create', 'LegalEntityController@create')->name('legal_entity_create'),
        Route::post('store', 'LegalEntityController@store')->name('legal_entity_store'),
        Route::get('edit/{entity_id}', 'LegalEntityController@edit')->name('legal_entity_edit'),
        Route::put('update/{entity_id}', 'LegalEntityController@update')->name('legal_entity_update'),
        Route::delete('delete/{entity_id}', 'LegalEntityController@destroy')->name('legal_entity_destroy'),
        Route::post('bank/store', 'LegalEntityController@storeBank')->name('legal_entity_store_bank'),
        Route::put('bank/update/{entity_bank_id}', 'LegalEntityController@updateBank')->name('legal_entity_update_bank'),
        Route::delete('bank/delete/{entity_bank_id}', 'LegalEntityController@destroyBank')->name('legal_entity_destroy_bank'),
    ]),

    /*Contract signing*/
//        Route::get('contract-signing', 'ContractSigningController@index')->name('contract_signing_index'),
//        Route::get('contract-signing/paginate', 'ContractSigningController@paginate')->name('contract_signing_paginate'),
//        Route::post('contract-signing/print-contract', 'ContractSigningController@printContract')->name('contract_signing_print_contract'),
//        Route::put('contract-signing/sign-contract/{driver_contract_id}', 'ContractSigningController@sign')->name('contract_signing_sign'),

    // Schedules
    Route::group(['prefix' => 'schedule'], fn() => [
        Route::get('/', 'ScheduleController@index')->name('schedule_index'),
        Route::get('paginate', 'ScheduleController@paginate')->name('schedule_paginate'),
        Route::post('update', 'ScheduleController@update')->name('schedule_update'),
    ]),

    // Driver types
    Route::group(['prefix' => 'driver-types'], fn() => [
        Route::get('/', 'DriverTypeController@index')->name('driver_types_index'),
        Route::post('update', 'DriverTypeController@updateFranchiseOptionals')->name('driver_types_update_optionals'),
    ]),

    // Worker complaint
    Route::group(['prefix' => 'complaint'], fn() => [
        Route::get('/', 'ComplaintController@index')->name('complaint_index'),
        Route::get('paginate', 'ComplaintController@paginate')->name('complaint_paginate'),
        Route::get('comments/{complaint_id}', 'ComplaintController@comments')->name('complaint_comments'),
        Route::post('comment/create', 'ComplaintController@commentCreate')->name('complaint_comment_create'),
        Route::post('status', 'ComplaintController@statusUpdate')->name('complaint_comment_status'),
        Route::get('workers', 'ComplaintController@getWorkers')->name('complaint_get_workers'),
        Route::post('create', 'ComplaintController@store')->name('complaint_create_workers'),
    ]),

    // Driver contracts
    Route::group(['prefix' => 'driver-contracts'], fn() => [
        Route::get('/', 'DriverContractController@index')->name('driver_contracts_index'),
        Route::get('paginate', 'DriverContractController@paginate')->name('driver_contracts_paginate'),
        Route::post('update/contract_price', 'DriverContractController@editDriverContractPrice')->name('driver_contract_price'),
        Route::post('terminate', 'DriverContractController@terminate')->name('driver_contracts_terminate'),
    ]),

    // Operators && call center
    Route::middleware(['role:dispatcher_web|operator_web|head_call_center_web'])->prefix('call-center')->group(fn() => [
        Route::get('/', 'CallCenterController@index')->name('get_operator_index'),
        Route::get('paginate', 'CallCenterController@paginate')->name('operator_paginate'),
        Route::get('pending-orders', 'CallCenterController@getPendingOrders')->name('operator_pending_orders'),
        Route::get('get-drivers', 'CallCenterController@getDrivers')->name('call_center_get_drivers'),
        Route::post('order/create', 'CallCenterController@orderCreate')->name('call_center_order_create')->middleware('detect_franchise_region'),
        Route::get('get-coordinates/{address}', 'CallCenterController@getCoordinates')->name('call_center_get_coordinates'),
        Route::post('driver-add-order', 'CallCenterController@driverAddOrder')->name('call_center_driver_add_order'),
        Route::put('update-atc-logged', 'CallCenterController@updateAtcLogged')->name('call_center_update_atc_logged'),
        Route::post('get-calls', 'CallCenterController@getCalls')->name('call_center_get_calls'),
        Route::post('client/create', 'CallCenterController@createClient')->name('call_center_create_client'),
        Route::put('client/update/{client_id}', 'CallCenterController@updateClient')->name('call_center_update_client'),
        Route::post('check-client-exists', 'CallCenterController@checkClientExists')->name('call_center_check_client_exists'),
        Route::post('check-passenger-exists', 'CallCenterController@checkPassengerExists')->name('call_center_check_passenger_exists'),
        Route::post('connect-worker', 'CallCenterController@connectWorker')->name('call_center_connect_worker'),
        Route::post('call-start', 'CallCenterController@callStart')->name('call_center_call_start'),
        Route::post('call-answered', 'CallCenterController@callAnswered')->name('call_center_call_answered'),
        Route::post('call-end', 'CallCenterController@callEnd')->name('call_center_call_end'),
        Route::get('get-coordinates-address/{lat}/{lut}', 'CallCenterController@coordinatesAddress')->name('call_center_coordinates_address'),
        Route::get('get-client-companies/{client_id}', 'CallCenterController@clientCompanies')->name('call_center_client_companies'),
        Route::post('get-order-price', 'CallCenterController@getOrderPrice')->name('call_center_get_order_price')->middleware('detect_franchise_region'),
        Route::get('order-history/{order_id}', 'CallCenterController@getOrderHistory')->name('call_center_get_order_history'),
        Route::post('slip-update', 'CallCenterController@slipUpdate')->name('call_center_slip_update'),
        Route::put('order-cancel/{order_id}', 'CallCenterController@cancelOrder')->name('call_center_order_cancel'),
        Route::post('order-comment/create', 'CallCenterController@orderCommentCreate')->name('call_center_order_comment_create'),
        Route::get('order-feedback/workers', 'CallCenterController@orderFeedbackWorkers')->name('call_center_order_feedback_workers'),
        Route::post('order-feedback/create', 'CallCenterController@orderFeedbackCreate')->name('call_center_order_feedback_create'),
        Route::post('find-companies', 'CallCenterController@findCompanies')->name('call_center_find_companies'),
        Route::post('attach-foreign-board', 'CallCenterController@findCompanies')->name('call_center_foreign_attach'),
        Route::post('send-driver-ntf', 'CallCenterController@sendDriverNotification')->name('send-driver-notification'),
        Route::post('send-client-ntf', 'CallCenterController@sendClientNotification')->name('send-client-notification'),
        Route::put('dist-order/{order_id}/{driver_id?}/{date?}/{now?}', 'CallCenterController@changePreorder')->name('change-order-data'),
        Route::post('send-list', 'CallCenterController@sendList')->name('send-order-to-list'),
        Route::get('drivers-for-edit/{order_id}/{type?}/{radius?}', 'CallCenterController@driverForEdit')->name('drivers-for-edit'),
    ]),

    //dispatcher
    Route::group(['prefix' => 'call-center-dispatcher', 'middleware' => ['role:dispatcher_web|head_call_center_web']], fn() => [
        Route::get('/', 'CallCenterDispatcherController@index')->name('get_dispatcher_index'),
        Route::get('paginate', 'CallCenterDispatcherController@ordersPaginate')->name('dispatcher_paginate'),
        Route::get('pending-orders', 'CallCenterDispatcherController@getPendingOrders')->name('dispatcher_pending_orders'),
        Route::get('operators', 'CallCenterDispatcherController@operatorsPaginate')->name('dispatcher_operators'),
        Route::get('calls', 'CallCenterDispatcherController@callsPaginate')->name('dispatcher_calls'),
        Route::get('boards', 'CallCenterDispatcherController@boardsPaginate')->name('dispatcher_boards'),
        Route::get('get-operators', 'CallCenterDispatcherController@getOperators')->name('dispatcher_get_operators'),
        Route::post('operator-attach-order', 'CallCenterDispatcherController@operatorAttachOrder')->name('dispatcher_operator_attach_order'),
        Route::put('od-re-calc/{order_id}', 'CallCenterDispatcherController@operatorReCalcOrder')->name('dispatcher_operator_re_calc_order'),
        Route::put('ord_re_manual/{order_id}', 'CallCenterDispatcherController@changeOrderDistToManual')->name('dispatcher_operator_ord_remanual'),
        Route::put('dr_ord_unpin/{driver_id}', 'CallCenterDispatcherController@driverOrderUnpin')->name('dispatcher_operator_ord_driver_unpin'),
        Route::post('send/message', 'CallCenterDispatcherController@sendMessage')->name('dispatcher_operator_send_message'),
    ]),

    // Accounting Department
    Route::group(['prefix' => 'bookkeeping', 'middleware' => ['role:accountant_web']], fn() => [
        Route::get('/', 'BookkeepingController@index')->name('bookkeeping_index'),
        Route::get('/companies', 'BookkeepingController@redirectCompanies')->name('bookkeeping_companies'),
        Route::get('/drivers', 'BookkeepingController@redirectDrivers')->name('bookkeeping_drivers'),
        Route::get('/drivers/paginate', 'BookkeepingController@getDrivers')->name('bookkeeping_get_drivers'),
        Route::get('driver-debt/{driver_id}', 'BookkeepingController@getDriverDebt')->name('bookkeeping_get_driver_debt'),
        Route::get('all/paginate', 'BookkeepingController@bookkeepingPaginate')->name('bookkeeping_orders'),
        Route::get('all/details/{transaction_id}', 'BookkeepingController@bookkeepingDetails')->name('bookkeeping_details'),
        Route::post('all/create_transaction', 'BookkeepingController@addTransaction')->name('bookkeeping_create_transaction'),
        Route::post('all/print_transactions', 'BookkeepingController@printTransaction')->name('bookkeeping_print_transaction'),
        Route::get('companies/company-orders/paginate', 'BookkeepingController@companyOrdersPaginate')->name('bookkeeping_get_company-orders'),
        Route::get('companies/company-orders/download', 'BookkeepingController@companyOrdersDownload')->name('bookkeeping_get_company-orders-download'),
        Route::get('companies/company-orders-report/paginate', 'BookkeepingController@companyOrdersReportPaginate')->name('bookkeeping_get_company-orders-report'),
        Route::get('companies/company-orders-report/download', 'BookkeepingController@companyOrdersReportDownload')->name('bookkeeping_get_company-orders-report-download'),

        Route::get('{any}', 'BookkeepingController@index')->where('any', '.*'),
    ]),

    Route::group(['prefix' => 'clients'], fn() => [
        Route::get('/', 'ClientController@index')->name('get_clients'),
        Route::get('pager', 'ClientController@pager')->name('get_clients_pager'),
    ]),

    Route::group(['prefix' => 'penalties'], fn() => [
        Route::get('/', 'PenaltyController@index')->name('get_penalties'),
        Route::get('pager', 'PenaltyController@pager')->name('get_penalties_pager'),
        Route::get('firm_to_paid/{debt_id}/{value}', 'PenaltyController@payDebtToFirm')->name('pay_debt_off_firm'),
    ]),

    // GLOBAL
    Route::post('stop-session', 'DashboardController@stopSession')->name('worker_dashboard_stop_session'),
    Route::post('start-session', 'DashboardController@startSession')->name('worker_dashboard_start_session'),
]);
