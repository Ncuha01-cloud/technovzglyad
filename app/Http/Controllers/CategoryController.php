<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Список всех категорий с количеством опубликованных статей.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->withCount([
                'texts' => fn (Builder $query) => $query->published(), // Считаем только видимые статьи.
            ])
            ->orderBy('name') // Алфавитный порядок.
            ->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Статьи одной категории.
     */
    public function show(Category $category): View
    {
        $texts = $category->texts()
            ->published() // Скрываем черновики и заблокированные материалы.
            ->with('author') // Автор нужен в карточке статьи.
            ->latest() // Новые статьи выше.
            ->paginate(6); // Пагинация внутри категории.

        return view('categories.show', [
            'category' => $category,
            'texts' => $texts,
        ]);
    }
}
