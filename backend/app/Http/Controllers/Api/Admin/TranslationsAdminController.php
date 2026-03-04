<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class TranslationsAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Translation::query()->orderByDesc('id');

        $locale = $request->query('locale');
        if (is_string($locale) && $locale !== '') {
            $query->where('locale', $locale);
        }

        $approved = $request->query('approved');
        if ($approved === 'true' || $approved === 'false') {
            $query->where('approved', $approved === 'true');
        }

        $items = $query->paginate(20);

        return response()->json([
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'locale' => ['required', 'string', 'max:10'],
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string'],
        ]);

        $translation = Translation::query()->updateOrCreate(
            ['locale' => $data['locale'], 'key' => $data['key']],
            ['value' => $data['value'], 'approved' => false, 'approved_by' => null, 'approved_at' => null],
        );

        return response()->json([
            'item' => $translation,
        ], 201);
    }

    public function approve(int $id)
    {
        $translation = Translation::query()->findOrFail($id);

        $translation->forceFill([
            'approved' => true,
            'approved_at' => now(),
            'approved_by' => null,
        ])->save();

        return response()->json([
            'item' => $translation,
        ]);
    }

    public function coverage(Request $request)
    {
        $locale = (string) $request->query('locale', 'en');

        $path = resource_path('i18n/source/en.json');

        if (! File::exists($path)) {
            return response()->json([
                'message' => 'Source locale file not found.',
                'path' => $path,
            ], 500);
        }

        $json = File::get($path);
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json([
                'message' => 'Invalid source locale JSON.',
                'path' => $path,
            ], 500);
        }

        $sourceKeys = array_keys(Arr::dot($data));
        sort($sourceKeys);

        $dbKeys = Translation::query()
            ->where('locale', $locale)
            ->pluck('key')
            ->all();
        $dbKeys = array_values(array_unique($dbKeys));
        sort($dbKeys);

        $missingInDb = array_values(array_diff($sourceKeys, $dbKeys));
        $extraInDb = array_values(array_diff($dbKeys, $sourceKeys));

        return response()->json([
            'locale' => $locale,
            'source' => [
                'path' => $path,
                'count' => count($sourceKeys),
            ],
            'db' => [
                'count' => count($dbKeys),
            ],
            'missing_in_db' => $missingInDb,
            'extra_in_db' => $extraInDb,
        ]);
    }
}
