<?php
namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        return view('location');
    }
    
    public function json(Request $request)
    {
        $query = Location::withTrashed()->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $locations = $query->paginate(10)->appends($request->query());

        return response()->json([
            'data' => $locations->items(),
            'links' => (string) $locations->links('pagination::tailwind'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $location = Location::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return response()->json([
            'message' => 'Lokasi berhasil diperbarui',
            'data' => $location,
        ]);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id); 
        $location->delete(); 

        return response()->json([
            'message' => 'Lokasi berhasil dihapus',
        ]);
    }

    public function restore($id)
    {
        $location = Location::onlyTrashed()->findOrFail($id); 
        $location->restore();

        return response()->json([
            'message' => 'Lokasi berhasil dipulihkan',
        ]);
    }

    public function forceDelete($id)
    {
        $location = Location::onlyTrashed()->findOrFail($id); 
        $location->forceDelete(); 

        return response()->json([
            'message' => 'Lokasi berhasil dihapus permanen',
        ]);
    }
}