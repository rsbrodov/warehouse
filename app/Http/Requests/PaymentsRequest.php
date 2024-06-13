<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() // если тру, то можно без авторизации отправлять на сайт данные
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|max:150',
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'Сумма' // меняет name на имя в тексте ошибки
        ];
    }
    public function messages() // меняет вообще весь текст ошибки
    {
        return [
            'amount.required' => 'Поле "Сумма" является обязательным',
            'amount.max' => 'В поле "Сумма" не может быть более 150 символов',
        ];
    }
}
