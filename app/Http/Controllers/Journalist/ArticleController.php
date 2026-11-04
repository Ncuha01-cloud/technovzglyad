<?php

/*
|--------------------------------------------------------------------------
| Кабинет журналиста (app/Http/Controllers/Journalist/ArticleController.php)
|--------------------------------------------------------------------------
| Создание, редактирование и удаление собственных статей.
| Изменено: убраны черновики, осталась только блокировка статьи админом.
*/

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
     * Показывает список статей журналиста.
     */
    public function index(): View
    {
        $query = Text::query() // Начинаем запрос к таблице texts.
            ->with('category') // Загружаем категорию для таблицы.
            ->latest(); // Сортируем от новых к старым.

        if (! auth()->user()->isAdmin()) { // Если это не администратор...
            $query->where('user_id', auth()->id()); // ...показываем только его статьи.
        }

        return view('journalist.index', [ // Открываем кабинет журналиста.
            'texts' => $query->paginate(10), // По 10 статей на странице.
        ]);
    }

    /**
     * Показывает форму создания статьи.
     */
    public function create(): View
    {
        return view('journalist.form', [ // Открываем общий шаблон формы.
            'text' => new Text(), // Пустая модель для новой статьи.
            'categories' => Category::query()->orderBy('name')->get(), // Список категорий.
            'action' => route('journalist.articles.store'), // Куда отправить форму.
            'method' => 'POST', // Метод создания записи.
        ]);
    }

    /**
     * Сохраняет новую статью в базу данных.
     */
    public function store(StoreTextRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Берём только проверенные поля.
        $data['user_id'] = $request->user()->id; // Автором будет текущий пользователь.

        $text = Text::query()->create($data); // Создаём статью в базе.

        return redirect() // Переходим обратно в кабинет.
            ->route('journalist.index')
            ->with('success', 'Статья «'.$text->heading.'» сохранена.'); // Показываем сообщение.
    }

    /**
     * Показывает форму редактирования статьи.
     */
    public function edit(Text $text): View
    {
        $this->ensureCanEdit($text); // Проверяем права на статью.

        return view('journalist.form', [ // Открываем общий шаблон формы.
            'text' => $text, // Редактируемая статья.
            'categories' => Category::query()->orderBy('name')->get(), // Список категорий.
            'action' => route('journalist.articles.update', $text), // Куда отправить форму.
            'method' => 'PUT', // Метод обновления записи.
        ]);
    }

    /**
     * Обновляет статью в базе данных.
     */
    public function update(UpdateTextRequest $request, Text $text): RedirectResponse
    {
        $this->ensureCanEdit($text); // Проверяем права на статью.

        $text->update($request->validated()); // Сохраняем проверенные изменения.

        return redirect() // Возвращаемся в кабинет.
            ->route('journalist.index')
            ->with('success', 'Статья «'.$text->heading.'» обновлена.'); // Показываем сообщение.
    }

    /**
     * Удаляет статью.
     */
    public function destroy(Text $text): RedirectResponse
    {
        $this->ensureCanEdit($text); // Проверяем права на статью.

        $heading = $text->heading; // Запоминаем заголовок для сообщения.
        $text->delete(); // Удаляем статью из базы.

        return redirect() // Возвращаемся в кабинет.
            ->route('journalist.index')
            ->with('success', 'Статья «'.$heading.'» удалена.'); // Показываем сообщение.
    }

    /**
     * Разрешает действие автору статьи или администратору.
     */
    private function ensureCanEdit(Text $text): void
    {
        $user = auth()->user(); // Текущий пользователь.

        if (! $user->isAdmin() && $user->id !== $text->user_id) { // Чужая статья запрещена.
            abort(403, 'Вы можете редактировать только свои статьи.');
        }
    }
}
