<?php

/*
|--------------------------------------------------------------------------
| Валидация статьи админом (app/Http/Requests/Admin/AdminUpdateTextRequest.php)
|--------------------------------------------------------------------------
| Администратор может менять текст и блокировать статью.
| Изменено: вместо is_published остался только is_blocked.
*/

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateTextRequest extends FormRequest
{
    /**
     * Доступ уже проверен middleware ролей.
     */
    public function authorize(): bool
    {
        return true; // Разрешаем запрос.
    }

    /**
     * Администратор может менять текст и блокировать статью.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'heading' => ['required', 'string', 'min:5', 'max:255'], // Заголовок от 5 до 255 символов.
            'category_id' => ['required', 'integer', 'exists:categories,id'], // Категория должна существовать.
            'text' => ['required', 'string', 'min:80'], // Текст не короче 80 символов.
            'is_blocked' => ['sometimes', 'boolean'], // Флаг блокировки (0 или 1).
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
            'heading.required' => 'Введите заголовок статьи.', // Заголовок не заполнен.
            'heading.min' => 'Заголовок должен содержать минимум 5 символов.', // Заголовок слишком короткий.
            'heading.max' => 'Заголовок не должен превышать 255 символов.', // Заголовок слишком длинный.
            'category_id.required' => 'Выберите категорию.', // Категория не выбрана.
            'category_id.exists' => 'Выбранной категории не существует.', // Категории нет в базе.
            'text.required' => 'Введите текст статьи.', // Текст не заполнен.
            'text.min' => 'Текст статьи должен содержать минимум 80 символов.', // Текст слишком короткий.
        ];
    }
}
