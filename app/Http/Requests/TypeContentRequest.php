<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypeContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => 'required|max:255',
            'apiUrl' => 'required|max:255',
            'description' => 'nullable|max:500',
            'icon' => 'nullable|max:150',
            'activeFrom' => 'nullable|date',
            'activeAfter' => 'nullable|date',
            'body' => 'nullable|max:1000',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['apiUrl'] = ['required', 'alpha_dash', 'max:150',
            ];
        }
        return $rules;
    }
    public function attributes()
    {
        return [
            'name' => 'Название' // меняет name на имя в тексте ошибки
        ];
    }
    public function messages() // меняет вообще весь текст ошибки
    {
        return [
            'name.required' => 'Пожалуйста, укажите название',
            'name.max' => 'Вы ввели слишком длинное название',
            'description.max' => 'Вы ввели слишком длинное описание',
            'icon.max' => 'С иконкой явно что-то не так...',
            'activeFrom.date' => 'Поле "Активен с..." должно содержать дату',
            'activeAfter.date' => 'Поле "Активен до..." должно содержать дату',
            'apiUrl.required' => 'Пожалуйста, введите символьный код',
            'apiUrl.alpha_dash' => 'Используйте только латинские символы',
            'apiUrl.max' => 'Символьный код не может быть длиннее 150 символов',
            'apiUrl.unique' => 'Такой символьный код уже существует',
            'body.max' => 'Body не может быть длиннее 1000 символов',
        ];
    }
}
