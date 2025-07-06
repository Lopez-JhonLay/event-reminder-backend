<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
	public function index()
	{
		$events = Auth::user()->events;
		return response()->json(['events' => $events]);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'event_date' => 'required|date',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i|after:start_time',
			'location' => 'required|string|max:255',
		]);

		$event = Auth::user()->events()->create($data);

		return response()->json([
			'message' => 'Event created successfully',
			'event' => $event,
		], 201);
	}

	public function show($id)
	{
		$event = Auth::user()->events()->findOrFail($id);
		return response()->json(['event' => $event]);
	}

	public function update(Request $request, $id)
	{
		$data = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'event_date' => 'required|date',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i|after:start_time',
			'location' => 'required|string|max:255',
		]);

		$event = Auth::user()->events()->findOrFail($id);

		$event->update($data);

		return response()->json([
			'message' => 'Event updated successfully',
			'event' => $event,
		]);
	}

	public function destroy($id)
	{
		$event = Auth::user()->events()->findOrFail($id);
		$event->delete();

		return response()->json(['message' => 'Event deleted successfully']);
	}
}
