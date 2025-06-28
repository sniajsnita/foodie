<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /* -----------------------------------------------------------------
     |  Helper privat: format satu resep
     |-----------------------------------------------------------------*/
    private function transform(Recipe $recipe): Recipe
    {
        $recipe->ingredients = explode(',', $recipe->ingredients);
        $recipe->steps       = explode(',', $recipe->steps);
        $recipe->photo_url   = $recipe->photo ? asset('storage/'.$recipe->photo) : null;

        return $recipe;
    }

    /* -----------------------------------------------------------------
     |  CRUD
     |-----------------------------------------------------------------*/
    /** GET /recipes */
    public function index()
    {
        $recipes = Recipe::with('category')->get()->map(fn ($r) => $this->transform($r));

        return response()->json($recipes);
    }

    /** POST /recipes */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'photo'        => 'nullable|image|max:2048',
            'ingredients'  => 'required|array',
            'steps'        => 'required|array',
            'duration'     => 'required|integer',
            'servings'     => 'required|integer',
            'category_id'  => 'required|exists:categories,id',
        ]);

        // foto
        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('recipes', 'public')
            : null;

        $recipe = Recipe::create([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'photo'        => $photoPath,
            'ingredients'  => implode(',', $validated['ingredients']),
            'steps'        => implode(',', $validated['steps']),
            'duration'     => $validated['duration'],
            'servings'     => $validated['servings'],
            'category_id'  => $validated['category_id'],
        ])->load('category');

        return response()->json($this->transform($recipe), 201);
    }

    /** GET /recipes/{id} */
    public function show(int $id)
    {
        $recipe = Recipe::with('category')->findOrFail($id);

        return response()->json($this->transform($recipe));
    }

    /** PUT /recipes/{id} */
    public function update(Request $request, int $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'description'  => 'nullable|string',
            'photo'        => 'nullable|image|max:2048',
            'ingredients'  => 'sometimes|required|array',
            'steps'        => 'sometimes|required|array',
            'duration'     => 'sometimes|required|integer',
            'servings'     => 'sometimes|required|integer',
            'category_id'  => 'sometimes|required|exists:categories,id',
        ]);

        // update kolom biasa
        $recipe->fill(array_filter([
            'title'        => $validated['title']        ?? null,
            'description'  => $validated['description']  ?? null,
            'ingredients'  => isset($validated['ingredients'])
                                ? implode(',', $validated['ingredients'])
                                : null,
            'steps'        => isset($validated['steps'])
                                ? implode(',', $validated['steps'])
                                : null,
            'duration'     => $validated['duration']     ?? null,
            'servings'     => $validated['servings']     ?? null,
            'category_id'  => $validated['category_id']  ?? null,
        ]));

        // ganti foto jika ada file baru
        if ($request->hasFile('photo')) {
            if ($recipe->photo) {
                Storage::disk('public')->delete($recipe->photo);
            }
            $recipe->photo = $request->file('photo')->store('recipes', 'public');
        }

        $recipe->save();

        return response()->json($this->transform($recipe->load('category')));
    }

    /** DELETE /recipes/{id} */
    public function destroy(int $id)
    {
        $recipe = Recipe::findOrFail($id);

        if ($recipe->photo) {
            Storage::disk('public')->delete($recipe->photo);
        }

        $recipe->delete();

        return response()->json(['message' => 'Resep berhasil dihapus.']);
    }
}
