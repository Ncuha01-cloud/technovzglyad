<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUpdateTextRequest;
use App\Models\Category;
use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Список всех статей с поиском и фильтром по статусу.
     */
    public function index(Request $request): View
    {
        $query = Text::query()
            ->with(['category', 'author']) // Связи для таблицы.
            ->latest(); // Новые статьи выше.

        $search = trim((string) $request->input('q'));

        if ($search !== '') {
            // Поиск по заголовку и тексту статьи.
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('heading', 'like', '%'.$search.'%')
                    ->orWhere('text', 'like', '%'.$search.'%');
            });
        }

        $status = (string) $request->input('status');

        match ($status) {
            'published' => $query->where('is_published', true)->where('is_blocked', false),
            'blocked' => $query->where('is_blocked', true),
            'draft' => $query->where('is_published', false),
            default => null, // Без фильтра показываем всё.
        };

        return view('admin.articles.index', [
            'texts' => $query->paginate(10)->withQueryString(), // Сохраняем параметры поиска в ссылках.
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Форма редактирования статьи администратором.
     */
    public function edit(Text $text): View
    {
        return view('admin.articles.edit', [
            'text' => $text,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Сохраняет изменения статьи, включая статусы публикации и блокировки.
     */
    public function update(AdminUpdateTextRequest $request, Text $text): RedirectResponse
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published'); // Чекбокс публикации.
        $data['is_blocked'] = $request->boolean('is_blocked'); // Чекбокс блокировки.

        $text->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Статья «'.$text->heading.'» обновлена.');
    }

    /**
     * Удаляет статью.
     */
    public function destroy(Text $text): RedirectResponse
    {
        $heading = $text->heading; // Запоминаем заголовок до удаления.
        $text->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Статья «'.$heading.'» удалена.');
    }

    /**
     * Быстро блокирует или разблокирует статью.
     */
    public function toggleBlock(Text $text): RedirectResponse
    {
        $text->update([
            'is_blocked' => ! $text->is_blocked, // Инвертируем текущее значение.
        ]);

        $message = $text->is_blocked
            ? 'Статья «'.$text->heading.'» заблокирована.'
            : 'Статья «'.$text->heading.'» разблокирована.';

        return back()->with('success', $message);
    }
}
