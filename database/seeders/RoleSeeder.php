<?php

/*
|--------------------------------------------------------------------------
| Сидер ролей (database/seeders/RoleSeeder.php)
|--------------------------------------------------------------------------
| Создаёт три роли: admin, journalist, user.
| Изменено: сидер можно запускать повторно без ошибок.
*/

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Создаёт три базовые роли системы.
     */
    public function run(): void
    {
        $roles = [
            Role::ADMIN,
            Role::JOURNALIST,
            Role::USER,
        ];

        foreach ($roles as $name) {
            // updateOrCreate делает сидер безопасным для повторного запуска.
            Role::query()->updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }
}
