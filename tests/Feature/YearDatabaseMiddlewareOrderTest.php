<?php

namespace Tests\Feature;

use App\Http\Kernel;
use App\Http\Middleware\SwitchYearDatabase;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YearDatabaseMiddlewareOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_year_database_is_selected_before_authentication_resolves_user(): void
    {
        $priority = app(Kernel::class)->getMiddlewarePriority();

        $this->assertLessThan(
            array_search(AuthenticatesRequests::class, $priority, true),
            array_search(SwitchYearDatabase::class, $priority, true),
        );
    }
}
