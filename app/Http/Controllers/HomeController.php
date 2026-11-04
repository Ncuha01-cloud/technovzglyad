<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Text;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Главная страница: все опубликованные статьи от новых к старым.
     */
    public function __invoke(): View
    {
        $texts = Text::query()
            ->published() // Только опубликованные и не заблокированные статьи.
            ->with(['category', 'author']) // Заранее загружаем связи, чтобы избежать N+1 запросов.
            ->latest() // Сортировка по created_at DESC.
            ->paginate(6); // По 6 карточек на страницу.

        return view('home', [
            'texts' => $texts,
            'categoriesCount' => Category::query()->count(), // Количество категорий для блока статистики.
        ]);
    }
}
