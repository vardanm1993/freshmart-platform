<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTranslationsWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_draft_and_approve_it(): void
    {
        $res = $this->postJson('/api/admin/i18n/translations', [
            'locale' => 'en',
            'key' => 'app.footer',
            'value' => 'Footer draft',
        ]);

        $res->assertCreated()
            ->assertJsonPath('item.approved', false)
            ->assertJsonPath('item.locale', 'en')
            ->assertJsonPath('item.key', 'app.footer');

        $id = $res->json('item.id');

        $approve = $this->postJson("/api/admin/i18n/translations/{$id}/approve");

        $approve->assertOk()
            ->assertJsonPath('item.approved', true)
            ->assertJsonPath('item.approved_at', fn ($v) => is_string($v) && $v !== '');

        $public = $this->getJson('/api/i18n/translations?locale=en');

        $public->assertOk()
            ->assertJsonFragment([
                'key' => 'app.footer',
                'value' => 'Footer draft',
            ]);

        $this->assertTrue(Translation::query()->where('id', $id)->exists());
    }
}
