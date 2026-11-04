<?php

/*
|--------------------------------------------------------------------------
| Пользователи в админке (app/Http/Controllers/Admin/UserController.php)
|--------------------------------------------------------------------------
| Список пользователей и смена роли.
| Изменено: администратор не может снять роль администратора с себя.
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Показывает пользователей и форму смены роли.
     */
    public function index(): View
    {
        return view('admin.users.index', [ // Открываем шаблон пользователей.
            'users' => User::query()->with('role')->orderBy('login')->paginate(15), // По 15 пользователей.
            'roles' => Role::query()->orderBy('id')->get(), // Все роли для выпадающего списка.
        ]);
    }

    /**
     * Меняет роль пользователя.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([ // Проверяем выбранную роль.
            'role_id' => ['required', 'integer', 'exists:roles,id'], // Роль должна быть в базе.
        ], [
            'role_id.required' => 'Выберите роль.', // Русское сообщение об ошибке.
            'role_id.exists' => 'Такой роли не существует.', // Русское сообщение об ошибке.
        ]);

        $newRole = Role::query()->findOrFail($data['role_id']); // Находим роль по id.

        if ($user->id === $request->user()->id && $newRole->name !== Role::ADMIN) { // Админ не может снять роль с себя.
            return back()->with('error', 'Нельзя снять роль администратора с самого себя.');
        }

        $user->update([ // Сохраняем новую роль.
            'role_id' => $newRole->id,
        ]);

        return back()->with('success', 'Роль пользователя «'.$user->login.'» изменена на «'.$newRole->label.'».');
    }
}
