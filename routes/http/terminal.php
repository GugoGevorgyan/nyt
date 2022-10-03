<?php

declare(strict_types=1);

// GET ROUTE


Route::middleware(['api', 'assign.guard:api_terminals'])->group(fn() => [
    Route::post('auth', 'AuthController@auth')
        ->name('terminal_auth'),
]);

Route::middleware(['auth:api_terminals', 'guest:drivers_api'])->group(fn() => [
    Route::post('auth_driver', 'AuthController@authDriver')
        ->name('terminal_auth_driver'),
]);


Route::middleware(['auth:drivers_api'])->group(fn() => [
    Route::get('get_debts', 'DebtController@getDebts')
        ->name('terminal_get_debts'),

    Route::post('add_cash', 'CashController@addedCash')
        ->name('terminal_add_cash'),

    Route::post('pay_balance', 'CashController@payBalance')
        ->name('terminal_pay_balance'),

    Route::post('pay_debt', 'DebtController@payDebt')
        ->name('terminal_pay_debt'),

    Route::post('pay_waybill', 'WaybillController@payWaybill')
        ->name('terminal_pay_waybill'),

    Route::post('upload_waybill', 'WaybillController@upload')
        ->name('terminal_upload_waybill'),

    Route::get('selected_waybills_price', 'WaybillController@getSelectedWaybillDaysPrice')
        ->name('terminal_selected_waybills_price'),
]);
