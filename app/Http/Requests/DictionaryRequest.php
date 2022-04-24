<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DictionaryRequest extends FormRequest
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
            'name' => 'required|max:150',
            'code' => 'required',
            'archive' => 'boolean',
            'description' => 'nullable',
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
            'name.required' => 'Поле "Название" является обязательным',
            'name.max' => 'В поле "Название" не может быть более 150 символов',
            'code.required' => 'Поле "Код" является обязательным',
            'archive.boolean' => 'Поле "Код" может содержать только числовые значения',
        ];
    }
}
