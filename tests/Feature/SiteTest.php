<?php

/*
|--------------------------------------------------------------------------
| Функциональные тесты сайта (tests/Feature/SiteTest.php)
|--------------------------------------------------------------------------
| Проверяют страницы, роли, вход, регистрацию и действия админа.
| Изменено: тесты работают с PostgreSQL и базой news_site_testing.
*/

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase; // Перед каждым тестом создаём чистую базу.

    /**
     * Заполняем базу демо-данными.
     */
    protected function setUp(): void
    {
        parent::setUp(); // Выполняем стандартную подготовку Laravel.

        $this->seed(); // Запускаем сидеры.
    }

    /**
     * Создаёт пользователя с нужной ролью.
     */
    private function userWithRole(string $roleName): User
    {
        $role = Role::query()->where('name', $roleName)->firstOrFail(); // Находим роль.

        return User::factory()->create([ // Создаём пользователя.
            'role_id' => $role->id, // Назначаем найденную роль.
        ]);
    }

    /**
     * Главная страница доступна гостю и показывает статьи.
     */
    public function test_home_page_shows_articles(): void
    {
        $response = $this->get(route('home')); // Открываем главную страницу.

        $response->assertOk(); // Проверяем успешный ответ.
        $response->assertSee('Нейросети научились проектировать лекарства быстрее человека'); // Видим статью.
        $response->assertSee('Последние новости'); // Видим заголовок страницы.
    }

    /**
     * Заблокированная статья недоступна гостю.
     */
    public function test_blocked_article_is_not_available_to_guests(): void
    {
        $blocked = Text::query()->where('is_blocked', true)->firstOrFail(); // Находим заблокированную статью.

        $this->get(route('articles.show', $blocked))->assertNotFound(); // Гость получает 404.
    }

    /**
     * Администратор может просматривать заблокированную статью.
     */
    public function test_admin_can_view_blocked_article(): void
    {
        $admin = $this->userWithRole(Role::ADMIN); // Создаём администратора.
        $blocked = Text::query()->where('is_blocked', true)->firstOrFail(); // Находим заблокированную статью.

        $this->actingAs($admin) // Входим как администратор.
            ->get(route('articles.show', $blocked))
            ->assertOk() // Страница открывается.
            ->assertSee('Статья заблокирована администратором.'); // Видим предупреждение.
    }

    /**
     * Страница категории показывает только статьи этой категории.
     */
    public function test_category_page_shows_only_its_articles(): void
    {
        $category = Category::query()->where('slug', 'space')->firstOrFail(); // Берём категорию «Космос».

        $response = $this->get(route('categories.show', $category)); // Открываем страницу категории.

        $response->assertOk(); // Проверяем успешный ответ.
        $response->assertSee('Телескоп «Джеймс Уэбб» показал атмосферу экзопланеты'); // Видим статью категории.
        $response->assertDontSee('Нейросети научились проектировать лекарства быстрее человека'); // Чужой статьи нет.
    }

    /**
     * Гость не может попасть в кабинет журналиста.
     */
    public function test_guest_is_redirected_from_journalist_section(): void
    {
        $this->get(route('journalist.index')) // Гость открывает кабинет.
            ->assertRedirect(route('login')); // Его отправляют на вход.
    }

    /**
     * Читатель не может попасть в закрытые разделы.
     */
    public function test_reader_cannot_access_journalist_and_admin_sections(): void
    {
        $reader = $this->userWithRole(Role::USER); // Создаём читателя.

        $this->actingAs($reader)->get(route('journalist.index'))->assertForbidden(); // Кабинет запрещён.
        $this->actingAs($reader)->get(route('admin.dashboard'))->assertForbidden(); // Админка запрещена.
    }

    /**
     * Журналист может создать статью, но не может открыть админку.
     */
    public function test_journalist_can_create_article_but_cannot_open_admin(): void
    {
        $journalist = $this->userWithRole(Role::JOURNALIST); // Создаём журналиста.
        $category = Category::query()->firstOrFail(); // Берём первую категорию.

        $response = $this->actingAs($journalist)->post(route('journalist.articles.store'), [ // Отправляем форму.
            'heading' => 'Тестовая статья о Laravel', // Заголовок.
            'category_id' => $category->id, // Категория.
            'text' => str_repeat('Это достаточно длинный текст для проверки валидации. ', 4), // Текст.
        ]);

        $response->assertRedirect(route('journalist.index')); // Возврат в кабинет.

        $this->assertDatabaseHas('texts', [ // Проверяем запись в базе.
            'heading' => 'Тестовая статья о Laravel',
            'user_id' => $journalist->id,
        ]);

        $this->get(route('home'))->assertSee('Тестовая статья о Laravel'); // Статья видна на главной.
        $this->actingAs($journalist)->get(route('admin.dashboard'))->assertForbidden(); // Админка запрещена.
    }

    /**
     * Администратор может заблокировать статью.
     */
    public function test_admin_can_block_article(): void
    {
        $admin = $this->userWithRole(Role::ADMIN); // Создаём администратора.
        $text = Text::query()->where('is_blocked', false)->firstOrFail(); // Берём видимую статью.

        $this->actingAs($admin) // Входим как администратор.
            ->post(route('admin.articles.block', $text))
            ->assertRedirect(); // Проверяем возврат.

        $this->assertTrue($text->fresh()->is_blocked); // Статья стала заблокированной.

        $this->post(route('logout')); // Выходим из аккаунта.

        $this->get(route('articles.show', $text))->assertNotFound(); // Гость получает 404.
    }

    /**
     * Администратор может изменить роль пользователя.
     */
    public function test_admin_can_change_user_role(): void
    {
        $admin = $this->userWithRole(Role::ADMIN); // Создаём администратора.
        $reader = $this->userWithRole(Role::USER); // Создаём читателя.
        $journalistRole = Role::query()->where('name', Role::JOURNALIST)->firstOrFail(); // Находим роль журналиста.

        $this->actingAs($admin) // Входим как администратор.
            ->put(route('admin.users.role', $reader), [
                'role_id' => $journalistRole->id, // Новая роль.
            ])
            ->assertRedirect(); // Проверяем возврат.

        $this->assertSame($journalistRole->id, $reader->fresh()->role_id); // Роль изменилась.
    }

    /**
     * Администратор не может снять роль с самого себя.
     */
    public function test_admin_cannot_demote_himself(): void
    {
        $admin = $this->userWithRole(Role::ADMIN); // Создаём администратора.
        $readerRole = Role::query()->where('name', Role::USER)->firstOrFail(); // Находим роль читателя.

        $this->actingAs($admin) // Входим как администратор.
            ->put(route('admin.users.role', $admin), [
                'role_id' => $readerRole->id, // Пытаемся сменить роль.
            ])
            ->assertSessionHas('error'); // Получаем сообщение об ошибке.

        $this->assertSame(Role::ADMIN, $admin->fresh()->role->name); // Роль осталась прежней.
    }

    /**
     * Регистрация создаёт читателя и сразу авторизует его.
     */
    public function test_registration_creates_reader_and_logs_in(): void
    {
        $response = $this->post(route('register.store'), [ // Отправляем форму регистрации.
            'login' => 'newreader', // Новый логин.
            'password' => 'password123', // Пароль.
            'password_confirmation' => 'password123', // Повтор пароля.
        ]);

        $response->assertRedirect(route('home')); // Возврат на главную.

        $this->assertAuthenticated(); // Пользователь вошёл.

        $user = User::query()->where('login', 'newreader')->firstOrFail(); // Находим пользователя.

        $this->assertSame(Role::USER, $user->role->name); // Роль читателя.
    }

    /**
     * Вход работает по логину и паролю.
     */
    public function test_user_can_login_with_login_and_password(): void
    {
        $reader = $this->userWithRole(Role::USER); // Создаём читателя.

        $response = $this->post(route('login.store'), [ // Отправляем форму входа.
            'login' => $reader->login, // Логин.
            'password' => 'password', // Пароль фабрики.
        ]);

        $response->assertRedirect(route('home')); // Возврат на главную.
        $this->assertAuthenticatedAs($reader); // Пользователь вошёл.
    }

    /**
     * Неверный пароль не авторизует пользователя.
     */
    public function test_login_fails_with_wrong_password(): void
    {
        $reader = $this->userWithRole(Role::USER); // Создаём читателя.

        $this->from(route('login')) // Запоминаем страницу входа.
            ->post(route('login.store'), [
                'login' => $reader->login, // Логин.
                'password' => 'wrong-password', // Неверный пароль.
            ])
            ->assertRedirect(route('login')) // Возврат на форму.
            ->assertSessionHasErrors('login'); // Есть ошибка.

        $this->assertGuest(); // Пользователь не вошёл.
    }

    /**
     * Выход завершает сессию пользователя.
     */
    public function test_user_can_logout(): void
    {
        $reader = $this->userWithRole(Role::USER); // Создаём читателя.

        $this->actingAs($reader) // Входим как читатель.
            ->post(route('logout'))
            ->assertRedirect(route('home')); // Возврат на главную.

        $this->assertGuest(); // Пользователь вышел.
    }
}
