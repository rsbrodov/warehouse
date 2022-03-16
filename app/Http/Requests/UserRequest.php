<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|string|email|max:255',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Имя' // меняет name на имя в тексте ошибки
        ];
    }
    public function messages() // меняет вообще весь текст ошибки
    {
        return [
            'name.required' => 'Поле "Имя" является обязательным',
            'email.required' => 'Поле "Email" является обязательным',
            'email.email' => 'То, что введено в поле "email" не похоже на email',
            'email.max' => 'В поле "email" не может быть более 255 символов',
            'password.required' => 'Поле "Пароль" является обязательным',
            'password.min' => 'Пароль не может быть короче 6 символов',
            'password.confirmed' => 'Пароли не совпадают'
        ];
    }
}
