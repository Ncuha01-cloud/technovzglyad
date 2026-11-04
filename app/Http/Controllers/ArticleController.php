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
        if (! $text->isVisible()) {
            $user = auth()->user(); // Текущий пользователь (может быть гостем).

            // Черновик или заблокированную статью видят только админ и её автор.
            if (! $user || (! $user->isAdmin() && $user->id !== $text->user_id)) {
                abort(404);
            }
        }

        $text->load(['category', 'author']); // Подгружаем связи для шаблона.

        return view('articles.show', [
            'text' => $text,
        ]);
    }
}
