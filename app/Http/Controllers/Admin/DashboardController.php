<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Text;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Главная страница админки со сводной статистикой.
     */
    public function __invoke(): View
    {
        $stats = [
            'users' => User::query()->count(), // Всего пользователей.
            'texts' => Text::query()->count(), // Всего статей.
            'published' => Text::query()->published()->count(), // Видимых статей.
            'blocked' => Text::query()->where('is_blocked', true)->count(), // Заблокированных статей.
        ];

        $latestTexts = Text::query()
            ->with(['category', 'author'])
            ->latest()
            ->take(5) // Последние пять статей.
            ->get();

        $latestUsers = User::query()
            ->with('role')
            ->latest()
            ->take(5) // Последние пять пользователей.
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'latestTexts' => $latestTexts,
            'latestUsers' => $latestUsers,
        ]);
    }
}
