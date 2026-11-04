<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTextRequest extends FormRequest
{
    /**
     * Права проверяет middleware ролей, поэтому здесь разрешаем запрос.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации новой статьи.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'heading' => ['required', 'string', 'min:5', 'max:255'], // Заголовок обязателен.
            'category_id' => ['required', 'integer', 'exists:categories,id'], // Категория должна существовать.
            'text' => ['required', 'string', 'min:80'], // Полный текст не короче 80 символов.
            'is_published' => ['sometimes', 'boolean'], // Флаг публикации (0 или 1).
        ];
    }

    /**
     * Русские сообщения об ошибках.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'heading.required' => 'Введите заголовок статьи.',
            'heading.min' => 'Заголовок должен содержать минимум 5 символов.',
            'heading.max' => 'Заголовок не должен превышать 255 символов.',
            'category_id.required' => 'Выберите категорию.',
            'category_id.exists' => 'Выбранной категории не существует.',
            'text.required' => 'Введите текст статьи.',
            'text.min' => 'Текст статьи должен содержать минимум 80 символов.',
        ];
    }
}
