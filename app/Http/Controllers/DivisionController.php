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
        $divisions = Division::with('members')->get();

        return view('division.index', compact('divisions'));
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
    public function store(StoreDivisionRequest $request)
    {
        $validatedData = $request->validated();
        Division::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect('division')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        //
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

        return redirect('division')->with('success', 'Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        Division::findOrFail($division->id)->destroy();

        return redirect('division')->with('success', 'Data berhasil dihapus.');
    }
}
