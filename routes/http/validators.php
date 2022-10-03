<?php

declare(strict_types=1);


Route::post('unique', 'ValidatorsController@unique');
Route::post('custom-unique', 'ValidatorsController@customUnique');
Route::post('exists', 'ValidatorsController@exists');
Route::post('accept-code', 'ValidatorsController@existsClientAcceptCode');
Route::post('company_region_valid', 'ValidatorsController@companyHasRegion');
