<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminI18nCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_coverage_reports_missing_and_extra_keys(): void
    {
        $user = User::factory()->create(['role' => 'translator']);
        Sanctum::actingAs($user);

        Translation::create([
            'locale' => 'en',
            'key' => 'app.footer',
            'value' => 'Footer draft',
            'approved' => false,
            'approved_at' => null,
        ]);

        $res = $this->getJson('/api/admin/i18n/coverage?locale=en');

        $res->assertOk();

        $res->assertJsonPath('missing_in_db', [
            'app.title',
            'common.cancel',
            'common.ok',
            'validation.required',
        ]);

        $res->assertJsonPath('extra_in_db', [
            'app.footer',
        ]);
    }
}
