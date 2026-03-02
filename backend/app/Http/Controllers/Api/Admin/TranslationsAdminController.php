<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;

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
}
