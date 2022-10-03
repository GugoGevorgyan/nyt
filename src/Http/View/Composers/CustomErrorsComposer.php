<?php

declare(strict_types=1);


namespace Src\Http\View\Composers;


use Handler;
use Illuminate\View\View;

/**
 * Class CustomErrorsComposer
 * @package Src\Http\View\Composers
 */
class CustomErrorsComposer
{
    /**
     * @param  View  $view
     */
    public function compose(View $view): void
    {
        $errors = [];

        if (session()->has(Handler::danger())) {
            $errors[] = session()->get(Handler::danger());
        }

        if (session()->has(Handler::warning())) {
            $errors[] = session()->get(Handler::warning());
        }

        if (session()->has(Handler::info())) {
            $errors[] = session()->get(Handler::info());
        }

        if (session()->has(Handler::success())) {
            $errors[] = session()->get(Handler::success());
        }

        $view->with('c_errors', $errors);
    }
}
