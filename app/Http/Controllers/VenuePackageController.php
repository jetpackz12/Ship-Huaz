<?php

namespace App\Http\Controllers;

use App\Models\VenuePackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VenuePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'venuePackages' => VenuePackage::all(),
        ];

        return Inertia::render('Admin/VenuePackages', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|unique:venue_packages,title',
            'description' => 'required|min:3',
            'guests' => 'required|numeric|min:10',
            'price' => 'required|numeric|min:1000',
        ]);

        VenuePackage::create($request->only(['title', 'description', 'guests', 'price']));

        return redirect()->route('admin.venue-packages.index')->with('success', 'Venue Package created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string|int $id)
    {
        $venuePackage = VenuePackage::findOrFail($id);

        $request->validate([
            'title' => 'required|min:3|unique:venue_packages,title,' . $venuePackage->id,
            'description' => 'required|min:3',
            'guests' => 'required|numeric|min:10',
            'price' => 'required|numeric|min:1000',
            'status' => 'required|in:active,inactive',
        ]);

        $venuePackage->update($request->only(['title', 'description', 'guests', 'price', 'status']));

        return redirect()->route('admin.venue-packages.index')->with('success', 'Venue Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $venuePackage = VenuePackage::findOrFail($id);
        $venuePackage->delete();

        return redirect()->route('admin.venue-packages.index')->with('success', 'Venue Package deleted successfully.');
    }
}
