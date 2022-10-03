<?php

declare(strict_types=1);


Route::middleware(['guest:system_workers_api'])->group(fn() => [
    Route::post('auth', 'AuthController@login')->name('auth'),
]);

Route::middleware(['api', 'auth:system_workers_api', 'role:mechanic_api'])->group(fn() => [
    Route::post('logout', 'AuthController@logoutWorker')->middleware(['assign.guard:workers_api'])->name('logout'),

    Route::get('questions', 'CarReportController@getQuestions')->name('getQuestions'),
    Route::post('report', 'CarReportController@report')->name('report'),
    Route::put('worker_info', 'CarReportController@workerInfo')->name('workerInfo'),
]);
