<?php

declare(strict_types=1);

namespace Src\Http\Requests;


use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MessageSendRequest
 * @property mixed message_data
 * @property mixed participant_ids
 * @property mixed room_id
 * @package Src\Http\Requests
 */
class MessageSendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|user_chat_room',
            'message_data' => 'required',
        ];
    }
}
