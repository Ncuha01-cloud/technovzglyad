<?php

/*
|--------------------------------------------------------------------------
| Главная админки (app/Http/Controllers/Admin/DashboardController.php)
|--------------------------------------------------------------------------
| Показывает короткую статистику и последние записи.
| Изменено: статистика сокращена до пользователей, статей и блокировок.
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Text;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Показывает главную страницу админ-панели.
     */
    public function __invoke(): View
    {
        $stats = [ // Собираем небольшую статистику для админа.
            'users' => User::query()->count(), // Всего пользователей.
            'texts' => Text::query()->count(), // Всего статей.
            'blocked' => Text::query()->where('is_blocked', true)->count(), // Заблокированных статей.
        ];

        $latestTexts = Text::query() // Запрос к статьям.
            ->with(['category', 'author']) // Загружаем связи.
            ->latest() // Сортируем от новых к старым.
            ->take(5) // Берём только пять статей.
            ->get();

        $latestUsers = User::query() // Запрос к пользователям.
            ->with('role') // Загружаем роль.
            ->latest() // Сортируем от новых к старым.
            ->take(5) // Берём только пять пользователей.
            ->get();

        return view('admin.dashboard', [ // Открываем шаблон админки.
            'stats' => $stats, // Передаём статистику.
            'latestTexts' => $latestTexts, // Передаём последние статьи.
            'latestUsers' => $latestUsers, // Передаём новых пользователей.
        ]);
    }
}
