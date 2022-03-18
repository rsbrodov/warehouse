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
            'description' => 'nullable|max:500',
            'icon' => 'nullable|max:150',
            'active_from' => 'nullable|date',
            'active_after' => 'nullable|date',
            'api_url' => ['required', 'alpha_dash', 'max:150',
                Rule::unique('type_contents')->ignore($this->id),
            ],
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
