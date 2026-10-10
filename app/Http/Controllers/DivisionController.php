<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Division;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $palette = ['#6366f1', '#a855f7', '#f97316', '#22c55e', '#3b82f6', '#ef4444', '#14b8a6', '#f59e0b'];

        $divisions = ($divisions ?? Division::withCount('members')->get())
            ->map(function ($d) use ($palette) {
                $coordinator = $d->members()->where('position', 'like', '%Koordinator%')->first();

                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'slug' => $d->slug,
                    'members_count' => $d->members_count ?? $d->members()->count(),
                    'coordinator' => $coordinator?->name,
                    'coordinator_photo' => $coordinator
                        ? 'https://ui-avatars.com/api/?name='.urlencode($coordinator->name).'&background='.ltrim($palette[($d->id - 1) % count($palette)], '#').'&color=fff&size=64'
                        : null,
                ];
            })
            ->values();

        return view('division.index', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request)
    {
        $validatedData = $request->validated();
        Division::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request, Division $division)
    {
        $validatedData = $request->validated();
        $divisions = Division::findOrFail($division->id);

        $divisions->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect()->back()->with('success', 'Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        Division::findOrFail($division->id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
