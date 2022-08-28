<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ElementContentRequest extends FormRequest
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
            'label' => 'required|max:255',
            'api_url' => 'required|max:255',
            'description' => 'nullable|max:500',
            'active_from' => 'nullable|date',
            'active_after' => 'nullable|date',
            'body' => 'nullable|max:1000',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['api_url'] = ['required', 'alpha_dash', 'max:150',
            ];
        }
        return $rules;
    }
    public function attributes()
    {
        return [
            'name' => 'Заголовок' // меняет name на имя в тексте ошибки
        ];
    }
    public function messages() // меняет вообще весь текст ошибки
    {
        return [
            'name.required' => 'Пожалуйста, укажите Заголовок',
            'name.max' => 'Вы ввели слишком длинное Заголовок',
            'description.max' => 'Вы ввели слишком длинное описание',
            
            'active_from.date' => 'Поле "Активен с..." должно содержать дату',
            'active_after.date' => 'Поле "Активен до..." должно содержать дату',
            'api_url.required' => 'Пожалуйста, введите символьный код',
            'api_url.alpha_dash' => 'Используйте только латинские символы',
            'api_url.max' => 'Символьный код не может быть длиннее 150 символов',
            'api_url.unique' => 'Такой символьный код уже существует',
            'body.max' => 'Body не может быть длиннее 1000 символов',
        ];
    }
}
