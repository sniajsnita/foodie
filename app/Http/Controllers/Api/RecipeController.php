<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Menampilkan semua resep.
     */
    public function index()
    {
        $recipes = Recipe::all()->map(function ($recipe) {
        $recipe->ingredients = explode(',', $recipe->ingredients);
        $recipe->steps = explode(',', $recipe->steps);
        return $recipe;
    });
    return response()->json($recipes);
    }

    /**
     * Menyimpan resep baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'ingredients' => 'required|array',
            'steps' => 'required|array',
            'duration' => 'required|integer',
            'servings' => 'required|integer',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('recipes', 'public');
        }

        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'photo' => $photoPath,
            'ingredients' => implode(',', $request->ingredients),
            'steps' => implode(',', $request->steps),
            'duration' => $request->duration,
            'servings' => $request->servings,
        ]);


        return response()->json($recipe, 201);
    }

    /**
     * Menampilkan detail resep berdasarkan ID.
     */
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->ingredients = explode(',', $recipe->ingredients);
        $recipe->steps = explode(',', $recipe->steps);
        return response()->json($recipe);
    }

    /**
     * Memperbarui data resep.
     */
    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'ingredients' => 'required|array',
            'steps' => 'required|array',
            'duration' => 'required|integer',
            'servings' => 'required|integer',
        ]);

        $recipe->title = $request->title;
        $recipe->description = $request->description;
        $recipe->ingredients = implode(',', $request->ingredients);
        $recipe->steps = implode(',', $request->steps);
        $recipe->duration = $request->duration;
        $recipe->servings = $request->servings;

        if ($request->hasFile('photo')) {
            if ($recipe->photo) {
                Storage::disk('public')->delete($recipe->photo);
            }
            $recipe->photo = $request->file('photo')->store('recipes', 'public');
        }

        $recipe->save();

        return response()->json($recipe);
    }

    /**
     * Menghapus resep.
     */
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);

        if ($recipe->photo) {
            Storage::disk('public')->delete($recipe->photo);
        }

        $recipe->delete();

        return response()->json(['message' => 'Resep berhasil dihapus.'], 200);
    }
}
