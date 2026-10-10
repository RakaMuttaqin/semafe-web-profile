<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();
        // dd($roles);

        return view('role.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();

        Role::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();
        $roles = Role::findOrFail($role->id);

        $roles->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Role::findOrFail($role->id)->delete();

        return redirect()->back()->with('success', 'Peran berhasil dihapus.');
    }
}
