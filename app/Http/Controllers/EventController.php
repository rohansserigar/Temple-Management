<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display the events management dashboard.
     */
    public function manageEvents(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        // Get filter parameter if any
        $statusFilter = $request->input('status');

        $query = Event::orderBy('event_date', 'asc')->orderBy('start_time', 'asc');

        if ($statusFilter && in_array($statusFilter, ['Upcoming', 'Ongoing', 'Completed', 'Cancelled'])) {
            $query->where('status', $statusFilter);
        }

        $events = $query->get();

        return view('admin.manage-events', compact('events', 'statusFilter'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:Upcoming,Ongoing,Completed,Cancelled',
        ]);

        try {
            Event::create($validated);
            return redirect()->back()->with('success', 'Event scheduled and created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create event: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:Upcoming,Ongoing,Completed,Cancelled',
        ]);

        try {
            $event = Event::findOrFail($id);
            $event->update($validated);
            return redirect()->back()->with('success', 'Event details and schedule updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update event: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified event.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return redirect()->back()->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }
}
