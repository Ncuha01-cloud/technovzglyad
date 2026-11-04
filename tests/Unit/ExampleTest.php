<?php

/*
|--------------------------------------------------------------------------
| Простейший модульный тест (tests/Unit/ExampleTest.php)
|--------------------------------------------------------------------------
| Проверяет, что PHPUnit вообще запускается.
| Этот тест не обращается к базе данных и приложению.
*/

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Проверяет очевидное условие: true равно true.
     */
    public function test_that_true_is_true(): void
    {
        // Утверждение всегда проходит — это проверка работы PHPUnit.
        $this->assertTrue(true);
    }
}
