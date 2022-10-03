<?php

declare(strict_types=1);


return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    //

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'rules' => [
            'You do not have access to this order' => 'У вас нет доступа к этому заказу',
            'You do not have time for this order' => 'Вы уже не успейте к данному заказу',
            'This order is no longer available' => 'Данный заказ больше не доступен ',
            'This phone already has an account' => 'По данному телефону уже есть аккаунт',
            'Invalid confirmation code' => 'Неверный код подтверждения',
            'Missing verification code' => 'Отсутствует код подтверждения',
            'Your confirmation key has expired' => 'Время ваше ключа подтверждения истёк',
            'This is either not your number or you are already registered' => 'Это или не ваш номер или вы уже зарегистрированы в другом телефоне для того чтобы сменить телефон сбросьте через',
            'Phone number required' => 'Номер телефона обязателен',
            'Wrong number' => 'Неправильный номер',
            'common_order_driver_access' => 'У вас нет  доступа к этому заказу',
            'order_not_exists' => 'Заказ не существует',
            'driver_reject_order' => 'Вы уже не можете отменить заказ.',
            'preorder_limit' => 'Ваш лимит предварительных заказов истёк'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
