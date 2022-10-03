<?php

declare(strict_types=1);

Route::post('atc-auth', 'AtcController@auth');
Route::post('call-receiving-data', 'AtcController@callReceivingData');
Route::post('connect-operator', 'AtcController@connectOperator');
Route::post('call-end', 'AtcController@callEnd');
Route::match(['get', 'post', 'put'], 'new_debt', 'AtcController@debtHook');
