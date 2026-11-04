<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Перед каждым тестом заполняем базу демо-данными.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * Создаёт пользователя с нужной ролью.
     */
    private function userWithRole(string $roleName): User
    {
        $role = Role::query()->where('name', $roleName)->firstOrFail();

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    /**
     * Главная страница доступна гостю и показывает опубликованные статьи.
     */
    public function test_home_page_shows_published_articles(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Нейросети научились проектировать лекарства быстрее человека');
        $response->assertSee('Последние новости');
    }

    /**
     * Черновик и заблокированная статья недоступны гостю.
     */
    public function test_hidden_articles_are_not_available_to_guests(): void
    {
        $draft = Text::query()->where('is_published', false)->firstOrFail();
        $blocked = Text::query()->where('is_blocked', true)->firstOrFail();

        $this->get(route('articles.show', $draft))->assertNotFound();
        $this->get(route('articles.show', $blocked))->assertNotFound();
    }

    /**
     * Администратор может просматривать скрытые статьи.
     */
    public function test_admin_can_view_hidden_articles(): void
    {
        $admin = $this->userWithRole(Role::ADMIN);
        $draft = Text::query()->where('is_published', false)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('articles.show', $draft))
            ->assertOk()
            ->assertSee('Черновик');
    }

    /**
     * Страница категории показывает только статьи этой категории.
     */
    public function test_category_page_shows_only_its_articles(): void
    {
        $category = Category::query()->where('slug', 'space')->firstOrFail();

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertSee('Телескоп «Джеймс Уэбб» показал атмосферу экзопланеты');
        $response->assertDontSee('Нейросети научились проектировать лекарства быстрее человека');
    }

    /**
     * Гость не может попасть в кабинет журналиста.
     */
    public function test_guest_is_redirected_from_journalist_section(): void
    {
        $this->get(route('journalist.index'))
            ->assertRedirect(route('login'));
    }

    /**
     * Читатель не может попасть в закрытые разделы.
     */
    public function test_reader_cannot_access_journalist_and_admin_sections(): void
    {
        $reader = $this->userWithRole(Role::USER);

        $this->actingAs($reader)->get(route('journalist.index'))->assertForbidden();
        $this->actingAs($reader)->get(route('admin.dashboard'))->assertForbidden();
    }

    /**
     * Журналист может писать статьи, но не может открыть админку.
     */
    public function test_journalist_can_create_article_but_cannot_open_admin(): void
    {
        $journalist = $this->userWithRole(Role::JOURNALIST);
        $category = Category::query()->firstOrFail();

        $response = $this->actingAs($journalist)->post(route('journalist.articles.store'), [
            'heading' => 'Тестовая статья о Laravel',
            'category_id' => $category->id,
            'text' => str_repeat('Это достаточно длинный текст для проверки валидации. ', 4),
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('journalist.index'));

        $this->assertDatabaseHas('texts', [
            'heading' => 'Тестовая статья о Laravel',
            'user_id' => $journalist->id,
            'is_published' => true,
        ]);

        $this->get(route('home'))->assertSee('Тестовая статья о Laravel');
        $this->actingAs($journalist)->get(route('admin.dashboard'))->assertForbidden();
    }

    /**
     * Администратор может заблокировать статью, и она исчезает с сайта.
     */
    public function test_admin_can_block_article(): void
    {
        $admin = $this->userWithRole(Role::ADMIN);
        $text = Text::query()->published()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.articles.block', $text))
            ->assertRedirect();

        $this->assertTrue($text->fresh()->is_blocked);

        // Выходим из аккаунта администратора, чтобы проверить сайт глазами гостя.
        $this->post(route('logout'));

        // Гость больше не может открыть заблокированную статью.
        $this->get(route('articles.show', $text))->assertNotFound();
    }

    /**
     * Администратор может изменить роль пользователя.
     */
    public function test_admin_can_change_user_role(): void
    {
        $admin = $this->userWithRole(Role::ADMIN);
        $reader = $this->userWithRole(Role::USER);
        $journalistRole = Role::query()->where('name', Role::JOURNALIST)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.users.role', $reader), [
                'role_id' => $journalistRole->id,
            ])
            ->assertRedirect();

        $this->assertSame($journalistRole->id, $reader->fresh()->role_id);
    }

    /**
     * Администратор не может снять роль администратора с самого себя.
     */
    public function test_admin_cannot_demote_himself(): void
    {
        $admin = $this->userWithRole(Role::ADMIN);
        $readerRole = Role::query()->where('name', Role::USER)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.users.role', $admin), [
                'role_id' => $readerRole->id,
            ])
            ->assertSessionHas('error');

        $this->assertSame(Role::ADMIN, $admin->fresh()->role->name);
    }

    /**
     * Регистрация создаёт читателя и сразу авторизует его.
     */
    public function test_registration_creates_reader_and_logs_in(): void
    {
        $response = $this->post(route('register.store'), [
            'login' => 'newreader',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $user = User::query()->where('login', 'newreader')->firstOrFail();

        $this->assertSame(Role::USER, $user->role->name);
    }

    /**
     * Вход работает по логину и паролю.
     */
    public function test_user_can_login_with_login_and_password(): void
    {
        $reader = $this->userWithRole(Role::USER);

        $response = $this->post(route('login.store'), [
            'login' => $reader->login,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($reader);
    }

    /**
     * Неверный пароль не авторизует пользователя.
     */
    public function test_login_fails_with_wrong_password(): void
    {
        $reader = $this->userWithRole(Role::USER);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'login' => $reader->login,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    /**
     * Выход завершает сессию пользователя.
     */
    public function test_user_can_logout(): void
    {
        $reader = $this->userWithRole(Role::USER);

        $this->actingAs($reader)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
