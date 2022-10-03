<?php

declare(strict_types=1);


Route::middleware([])->group(fn()=>[
    Route::post('acquiring', 'AcquiringHookController@webhook')
    ->name('webhook_acquiring'),
]);
