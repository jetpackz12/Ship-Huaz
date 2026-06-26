<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'eventTypes' => EventType::all(),
        ];

        return Inertia::render('Admin/EventTypes', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|min:3|unique:event_types,type',
        ]);

        EventType::create($request->only(['type']));

        return redirect()->route('admin.event-types.index')->with('success', 'Event Type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $eventType)
    {
        $request->validate([
            'type' => 'required|min:3|unique:event_types,type,' . $eventType->id,
            'status' => 'required|in:active,inactive',
        ]);

        $eventType->update($request->only(['type', 'status']));

        return redirect()->route('admin.event-types.index')->with('success', 'Event Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $eventType)
    {
        $eventType->delete();

        return redirect()->route('admin.event-types.index')->with('success', 'Event Type deleted successfully.');
    }
}
