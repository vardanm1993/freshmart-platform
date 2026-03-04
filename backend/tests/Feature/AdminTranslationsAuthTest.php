<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminTranslationsAuthTest extends TestCase
{
    public function test_admin_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/i18n/translations')
            ->assertUnauthorized();
    }
}
