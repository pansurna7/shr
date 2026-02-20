<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('backend.location.index', compact('locations'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'radius' => 'required|integer|min:10',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Simpan ke Database
            $location = new Location();
            $location->name = $request->name;
            $location->latitude = $request->latitude;
            $location->longitude = $request->longitude;
            $location->radius = $request->radius;
            $location->address = $request->address;
            $location->is_active = 1; // Default aktif saat dibuat
            $location->save();

            DB::commit();

            // 3. Redirect dengan pesan sukses
            return redirect()
                ->route('location.index')
                ->with('success', 'Location "' . $request->name . '" has been created successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            // Log error jika diperlukan: \Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to save location: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman edit lokasi
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        if (!$location) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->all());
        return redirect()->back()->with('success', 'Location updated successfully');
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return redirect()->back()->with('success', 'Location deleted successfully');
    }
}
