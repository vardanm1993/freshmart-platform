<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_approved_translations_for_locale(): void
    {
        Translation::query()->create([
            'locale' => 'en',
            'key' => 'app.title',
            'value' => 'FreshMart',
            'approved' => true,
        ]);

        Translation::query()->create([
            'locale' => 'en',
            'key' => 'app.tagline',
            'value' => 'Groceries',
            'approved' => false,
        ]);

        Translation::query()->create([
            'locale' => 'ru',
            'key' => 'app.title',
            'value' => 'ФрешМарт',
            'approved' => true,
        ]);

        $res = $this->getJson('/api/i18n/translations?locale=en');

        $res->assertOk()
            ->assertJson([
                'locale' => 'en',
            ])
            ->assertJsonCount(1, 'items')
            ->assertJsonFragment([
                'key' => 'app.title',
                'value' => 'FreshMart',
            ])
            ->assertJsonMissing([
                'key' => 'app.tagline',
            ]);
    }

    public function test_cache_is_invalidated_when_translation_changes(): void
    {
        $t = Translation::query()->create([
            'locale' => 'en',
            'key' => 'app.title',
            'value' => 'FreshMart',
            'approved' => true,
        ]);

        $this->getJson('/api/i18n/translations?locale=en')->assertOk();

        $t->update(['value' => 'FreshMart 2']);

        $this->getJson('/api/i18n/translations?locale=en')
            ->assertOk()
            ->assertJsonFragment([
                'key' => 'app.title',
                'value' => 'FreshMart 2',
            ]);
    }

}
