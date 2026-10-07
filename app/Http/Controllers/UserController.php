<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        // dd($users);

        return view('user.index', compact('users', 'roles'));
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'role_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $users = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $validatedData['role_id'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // dd($users);
        return redirect('user')->with('success', 'Data Berhasil Ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $validatedData['role_id'],
            'status' => $validatedData['status'],
        ];

        if (! empty($validatedData['password'])) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        $user->update($data);

        return redirect('user')->with('success', 'Data Berhasil Diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->destroy();

        return redirect('user')->with('success', 'Data Berhasil Dihapus.');
    }
}
