<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::with('users')->get();
        // dd($users->where('role_id', 3)->count());
// dd($roles->where('users.id', 1)->count());
        return view('role.index', compact('roles', 'users'));
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
    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();

        Role::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ]);

        return redirect('role')->with('success', 'Data Berhasil Ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
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

        return redirect('role')->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Role::findOrFail($role->id)->destroy();
    }
}
