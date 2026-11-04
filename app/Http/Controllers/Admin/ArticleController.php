<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUpdateTextRequest;
use App\Models\Category;
use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Показывает все статьи сайта.
     */
    public function index(): View
    {
        $texts = Text::query() // Начинаем запрос к таблице texts.
            ->with(['category', 'author']) // Загружаем категорию и автора.
            ->latest() // Сортируем от новых к старым.
            ->paginate(10); // По 10 статей на странице.

        return view('admin.articles.index', [ // Открываем шаблон списка статей.
            'texts' => $texts, // Передаём статьи в шаблон.
        ]);
    }

    /**
     * Показывает форму редактирования статьи.
     */
    public function edit(Text $text): View
    {
        return view('admin.articles.edit', [ // Открываем шаблон редактирования.
            'text' => $text, // Редактируемая статья.
            'categories' => Category::query()->orderBy('name')->get(), // Список категорий.
        ]);
    }

    /**
     * Сохраняет изменения статьи.
     */
    public function update(AdminUpdateTextRequest $request, Text $text): RedirectResponse
    {
        $data = $request->validated(); // Берём проверенные поля.
        $data['is_blocked'] = $request->boolean('is_blocked'); // Превращаем чекбокс в true/false.

        $text->update($data); // Сохраняем изменения в базе.

        return redirect() // Возвращаемся к списку статей.
            ->route('admin.articles.index')
            ->with('success', 'Статья «'.$text->heading.'» обновлена.'); // Показываем сообщение.
    }

    /**
     * Удаляет статью.
     */
    public function destroy(Text $text): RedirectResponse
    {
        $heading = $text->heading; // Запоминаем заголовок для сообщения.
        $text->delete(); // Удаляем статью из базы.

        return redirect() // Возвращаемся к списку статей.
            ->route('admin.articles.index')
            ->with('success', 'Статья «'.$heading.'» удалена.'); // Показываем сообщение.
    }

    /**
     * Блокирует или разблокирует статью.
     */
    public function toggleBlock(Text $text): RedirectResponse
    {
        $text->update([ // Обновляем только одно поле.
            'is_blocked' => ! $text->is_blocked, // Меняем значение на противоположное.
        ]);

        $message = $text->is_blocked // Проверяем новое состояние.
            ? 'Статья заблокирована.' // Если true — сообщение о блокировке.
            : 'Статья разблокирована.'; // Если false — сообщение о разблокировке.

        return back()->with('success', $message); // Возвращаемся на ту же страницу.
    }
}
