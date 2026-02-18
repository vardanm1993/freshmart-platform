<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicTranslationsController extends Controller
{
    public function index(Request $request)
    {
        $locale = (string) $request->query('locale', 'en');
        $cacheKey = "i18n:translations:{$locale}";

        $items = Cache::remember($cacheKey, now()->addMinutes(5), fn () =>
        Translation::query()
            ->where('approved', true)
            ->where('locale', $locale)
            ->orderBy('key')
            ->get(['key', 'value'])
        );

        return response()->json([
            'items' => $items,
            'locale' => $locale,
        ]);
    }
}
