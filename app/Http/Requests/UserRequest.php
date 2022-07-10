<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $rules = [
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255',
                //Rule::unique('users')->ignore($this->user()->id, 'id'), // как вариант можно попробовать так, если нижняя строка не работает:)
                Rule::unique('users')->ignore($this->id),
            ],
            'password' => 'required|confirmed|min:6',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['password'] = [];
        }
        return $rules;
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
            'name.required' => 'Пожалуйста, укажите имя',
            'email.required' => 'Пожалуйста, укажите адрес электронной почты',
            'email.email' => 'Пожалуйста, введите корректный адрес электронной почты',
            'email.max' => 'В поле "email" не может быть более 255 символов',
            'email.unique' => 'Такой email уже существует',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль не может быть короче 6 символов',
            'password.confirmed' => 'Пароли не совпадают'
        ];
    }
}
