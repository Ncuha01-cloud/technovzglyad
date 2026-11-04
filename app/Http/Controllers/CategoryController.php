<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Показывает список всех категорий.
     */
    public function index(): View
    {
        $categories = Category::query() // Начинаем запрос к таблице categories.
            ->withCount([ // Считаем статьи в каждой категории.
                'texts' => fn (Builder $query) => $query->visible(), // Учитываем только видимые статьи.
            ])
            ->orderBy('name') // Сортируем категории по названию.
            ->get(); // Получаем все категории.

        return view('categories.index', [ // Открываем шаблон списка категорий.
            'categories' => $categories, // Передаём категории в шаблон.
        ]);
    }

    /**
     * Показывает статьи одной категории.
     */
    public function show(Category $category): View
    {
        $texts = $category->texts() // Берём статьи выбранной категории.
            ->visible() // Скрываем заблокированные статьи.
            ->with('author') // Загружаем автора для каждой статьи.
            ->latest() // Сортируем от новых к старым.
            ->paginate(6); // Показываем по 6 статей на странице.

        return view('categories.show', [ // Открываем шаблон категории.
            'category' => $category, // Передаём категорию.
            'texts' => $texts, // Передаём статьи.
        ]);
    }
}
