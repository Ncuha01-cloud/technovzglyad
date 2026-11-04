<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Показывает главную страницу со списком статей.
     */
    public function __invoke(): View
    {
        $texts = Text::query() // Начинаем запрос к таблице texts.
            ->visible() // Берём только незаблокированные статьи.
            ->with(['category', 'author']) // Сразу загружаем категорию и автора.
            ->latest() // Сортируем от новых к старым.
            ->paginate(6); // Показываем по 6 статей на странице.

        return view('home', [ // Открываем шаблон главной страницы.
            'texts' => $texts, // Передаём статьи в шаблон.
        ]);
    }
}
