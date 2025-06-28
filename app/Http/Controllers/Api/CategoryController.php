<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'photo' => $photoPath
        ]);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'photo' => 'nullable|image|max:2048'
        ]);

        $category->name = $request->name;

        if ($request->hasFile('photo')) {
            if ($category->photo) {
                Storage::disk('public')->delete($category->photo);
            }
            $category->photo = $request->file('photo')->store('categories', 'public');
        }

        $category->save();

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->photo) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.'], 200);
    }
}
