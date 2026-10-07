<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use App\Models\Events;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventsRequest $request)
    {
        $validatedData = $request->validated();
        Events::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'description' => $validatedData['description'],
            'image' => $validatedData['image'],
            'location' => $validatedData['location'],
            'start_at' => $validatedData['start_at'],
            'end_at' => $validatedData['end_at'],
            'status' => $validatedData['status'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Events $events)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Events $events)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventsRequest $request, Events $events)
    {
        $validatedData = $request->validated();
        Events::findOrFail($events->id)->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'description' => $validatedData['description'],
            'image' => $validatedData['image'],
            'location' => $validatedData['location'],
            'start_at' => $validatedData['start_at'],
            'end_at' => $validatedData['end_at'],
            'status' => $validatedData['status'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Events $events)
    {
        Events::findOrFail($events->id)->destroy();
    }
}
