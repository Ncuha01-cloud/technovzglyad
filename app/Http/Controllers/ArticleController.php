<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Показывает полный текст статьи.
     */
    public function show(Text $text): View
    {
        if ($text->is_blocked) { // Проверяем, заблокирована ли статья.
            $user = auth()->user(); // Получаем текущего пользователя.

            // Заблокированную статью видят только админ и её автор.
            if (! $user || (! $user->isAdmin() && $user->id !== $text->user_id)) {
                abort(404); // Остальным показываем ошибку 404.
            }
        }

        $text->load(['category', 'author']); // Загружаем связи для шаблона.

        return view('articles.show', [ // Открываем шаблон статьи.
            'text' => $text, // Передаём статью в шаблон.
        ]);
    }
}
