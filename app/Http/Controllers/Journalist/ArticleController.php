<?php

namespace App\Http\Controllers\Journalist;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTextRequest;
use App\Http\Requests\UpdateTextRequest;
use App\Models\Category;
use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Кабинет журналиста: список своих статей (админ видит все).
     */
    public function index(): View
    {
        $query = Text::query()
            ->with('category') // Категория нужна в таблице.
            ->latest(); // Новые статьи выше.

        if (! auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id()); // Журналист видит только свои материалы.
        }

        return view('journalist.index', [
            'texts' => $query->paginate(10),
        ]);
    }

    /**
     * Форма создания новой статьи.
     */
    public function create(): View
    {
        return view('journalist.form', [
            'text' => new Text(), // Пустая модель для общего шаблона формы.
            'categories' => Category::query()->orderBy('name')->get(), // Варианты категорий.
            'action' => route('journalist.articles.store'), // Куда отправлять форму.
            'method' => 'POST', // HTTP-метод создания.
        ]);
    }

    /**
     * Сохраняет новую статью в базу данных.
     */
    public function store(StoreTextRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Только проверенные поля.
        $data['is_published'] = $request->boolean('is_published'); // Превращаем чекбокс в bool.
        $data['user_id'] = $request->user()->id; // Автор — текущий пользователь.

        $text = Text::query()->create($data);

        return redirect()
            ->route('journalist.index')
            ->with('success', 'Статья «'.$text->heading.'» сохранена.');
    }

    /**
     * Форма редактирования статьи.
     */
    public function edit(Text $text): View
    {
        $this->ensureCanEdit($text); // Проверяем, что это статья текущего журналиста.

        return view('journalist.form', [
            'text' => $text,
            'categories' => Category::query()->orderBy('name')->get(),
            'action' => route('journalist.articles.update', $text),
            'method' => 'PUT',
        ]);
    }

    /**
     * Обновляет статью.
     */
    public function update(UpdateTextRequest $request, Text $text): RedirectResponse
    {
        $this->ensureCanEdit($text);

        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        // Поле is_blocked сюда не попадает: журналист не может снять блокировку.

        $text->update($data);

        return redirect()
            ->route('journalist.index')
            ->with('success', 'Статья «'.$text->heading.'» обновлена.');
    }

    /**
     * Удаляет статью.
     */
    public function destroy(Text $text): RedirectResponse
    {
        $this->ensureCanEdit($text);

        $heading = $text->heading; // Сохраняем заголовок для сообщения.
        $text->delete();

        return redirect()
            ->route('journalist.index')
            ->with('success', 'Статья «'.$heading.'» удалена.');
    }

    /**
     * Разрешает действие автору статьи или администратору.
     */
    private function ensureCanEdit(Text $text): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $user->id !== $text->user_id) {
            abort(403, 'Вы можете редактировать только свои статьи.');
        }
    }
}
