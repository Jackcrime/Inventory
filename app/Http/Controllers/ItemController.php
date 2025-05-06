<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('item', compact('categories', 'locations'));
    }

    public function json(Request $request)
    {
        $query = Item::with(['category', 'location'])
        ->withTrashed()
        ->latest('id');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('categories_id', $request->category);
        }

        if ($request->location) {
            $query->where('locations_id', $request->location);
        }

        $items = $query->latest()->paginate(10);

        return response()->json([
            'data' => $items->items(),
            'links' => (string) $items->links('pagination::tailwind')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'categories_id' => 'required|exists:categories,id',
            'locations_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer',
            'description' => 'nullable'
        ]);

        $item = Item::create($validated);

        return response()->json([
            'message' => 'Item berhasil disimpan',
            'data' => $item
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required',
            'categories_id' => 'required|exists:categories,id',
            'locations_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer',
            'description' => 'nullable'
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Item berhasil diperbarui',
            'data' => $item
        ]);
    }
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus (soft delete)'
        ]);
    }

    public function restore($id)
    {
        $item = Item::withTrashed()->findOrFail($id);
        $item->restore();

        return response()->json([
            'message' => 'Item berhasil direstore',
            'data' => $item
        ]);
    }

    public function forceDelete($id)
    {
        $item = Item::withTrashed()->findOrFail($id);
        $item->forceDelete();

        return response()->json([
            'message' => 'Item berhasil dihapus permanen'
        ]);
    }
}
