<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstGuards;

class CallCenterOrderFeedbackCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'type' => 'string',
        'assessment' => 'string',
        'complaint' => 'string',
        'text' => 'string',
        'subject' => 'string',
        'driver_id' => 'string',
        'client_id' => 'string',
        'recipient_id' => 'string',
        'order_id' => 'string'
    ])] public function rules(): array
    {
        $data = $this->request->all();
        $rules = ['type' => 'required|in:client,driver,worker'];

        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'driver':
                    $rules['order_id'] = 'required|exists:orders,order_id';
                    $rules['driver_id'] = 'required|exists:drivers,driver_id';
                    $rules['text'] = 'required|min:1|max:2500';
                    $rules['assessment'] = 'required|min:1|max:5';
                    break;
                case 'client':
                    $rules['order_id'] = 'required|exists:orders,order_id';
                    $rules['client_id'] = 'required|exists:clients,client_id';
                    $rules['text'] = 'required|min:7|max:2500';
                    $rules['assessment'] = 'required|min:1|max:5';
                    break;
                case 'worker':
                    $rules['order_id'] = 'required|exists:orders,order_id';
                    $rules['recipient_id'] = 'required|exists:system_workers,system_worker_id';
                    $rules['subject'] = 'required|min:1|max:2500';
                    $rules['complaint'] = 'required|min:1|max:2500';
                    break;
                default:
            }
        }

        return $rules;
    }
}
