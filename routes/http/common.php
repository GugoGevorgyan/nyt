<?php

declare(strict_types=1);


/**
 * ==========================================
 *         VERSIONING FOR ALL SYSTEM
 * ==========================================
 */
Route::group(['prefix' => 'versioning', 'middleware' => '_versioning'], fn() => [
    Route::get('version', 'VersioningController@getVersion'),
    Route::put('version', 'VersioningController@editVersion'),
]);


/**
 * ==========================================
 *           TEST WRITERS && RIDERS
 * ==========================================
 */
Route::group(['prefix' => 'write_test', 'middleware' => ['auth.drivers_api,clients_web']], fn() => [
    Route::get('tester', 'Controller@gets'),
    Route::post('tester', 'Controller@stores'),
    Route::delete('tester', 'Controller@deletes'),
]);
