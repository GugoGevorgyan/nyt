<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AuthApi;

use Src\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Src\Repositories\TemporaryPayCard\TemporaryPayCardContract;
use Src\Repositories\PayCard\PayCardContract;
use Src\Http\Requests\Webhooks\AcquiringRequest;

/**
 * Class AcquiringHookController
 * @package Src\Http\Controllers\AuthApi\AcquiringHookController
 */
class AcquiringHookController extends Controller
{
    /**
     * @var PayCardContract
     */
    protected PayCardContract $payCardContract;

    /**
     * @var TemporaryPayCardContract
     */
    protected TemporaryPayCardContract $temporaryPayCardContract;

    /**
     * AcquiringHookController constructor.
     */
    public function __construct()
    {
        $this->payCardContract = app(PayCardContract::class);
        $this->temporaryPayCardContract = app(TemporaryPayCardContract::class);
    }


    public function webhook(AcquiringRequest $request){
        $data = $request->validated();

        if('successful' !== $data['status']){
            return;
        }


        $temporary_pay_card = $this->temporaryPayCardContract->where('transaction_id', '=', $data['id'])->findFirst();

        $this->payCardContract->insert([
            'temporary_pay_card_id' => $temporary_pay_card->{$temporary_pay_card->getKeyName()},
            'owner_id' => $temporary_pay_card->owner_id,
            'owner_type' => $temporary_pay_card->owner_type,
            'card_number' => $data['payment_method']['account']
        ]);
    }
}
