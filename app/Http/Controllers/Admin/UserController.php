<?php

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
     * Список пользователей и форма смены роли.
     */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->with('role')->orderBy('login')->paginate(15), // По 15 пользователей.
            'roles' => Role::query()->orderBy('id')->get(), // Все доступные роли.
        ]);
    }

    /**
     * Меняет роль пользователя.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'], // Роль должна существовать.
        ], [
            'role_id.required' => 'Выберите роль.',
            'role_id.exists' => 'Такой роли не существует.',
        ]);

        $newRole = Role::query()->findOrFail($data['role_id']);

        // Защита от случайной потери доступа: админ не может снять роль с себя.
        if ($user->id === $request->user()->id && $newRole->name !== Role::ADMIN) {
            return back()->with('error', 'Нельзя снять роль администратора с самого себя.');
        }

        $user->update([
            'role_id' => $newRole->id,
        ]);

        return back()->with('success', 'Роль пользователя «'.$user->login.'» изменена на «'.$newRole->label.'».');
    }
}
