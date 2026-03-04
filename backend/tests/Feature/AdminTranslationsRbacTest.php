<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTranslationsRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_translator_cannot_approve_translation(): void
    {
        $translator = User::factory()->create(['role' => 'translator']);
        Sanctum::actingAs($translator);

        $translation = Translation::create([
            'locale' => 'en',
            'key' => 'app.footer',
            'value' => 'Footer draft',
            'approved' => false,
            'approved_at' => null,
        ]);

        $this->postJson("/api/admin/i18n/translations/{$translation->id}/approve")
            ->assertForbidden();
    }
}
