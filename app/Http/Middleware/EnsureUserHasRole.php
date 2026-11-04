<?php

/*
|--------------------------------------------------------------------------
| Проверка роли (app/Http/Middleware/EnsureUserHasRole.php)
|--------------------------------------------------------------------------
| Не пускает в закрытые разделы пользователей без нужной роли.
| Изменено: гость перенаправляется на вход, а не получает 403.
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Пропускает запрос только пользователям с одной из разрешённых ролей.
     *
     * Пример использования в маршрутах: ->middleware('role:admin,journalist')
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user(); // Текущий авторизованный пользователь.

        if (! $user) {
            // Гостя отправляем на страницу входа, а не показываем 403.
            return redirect()->route('login')->with('error', 'Для доступа к разделу нужно войти в аккаунт.');
        }

        if (! $user->hasAnyRole($roles)) {
            // Пользователь вошёл, но его роль не входит в список разрешённых.
            abort(403, 'У вас нет прав для доступа к этому разделу.');
        }

        return $next($request); // Роль подходит — передаём запрос дальше.
    }
}
