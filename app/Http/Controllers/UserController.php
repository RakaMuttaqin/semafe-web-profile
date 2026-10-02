<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
    public function store(Request $request)
    {
        // 'name' => $request['name'],
        // 'email' => $request['email'],
        // 'role_id' => $request['role_id'],
        // 'password' => $request['password'],
        // 'passwordConfirmation' => $request['passwordConfirmation'],

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'role_id' => 'nullable',
            'password' => 'required',
            'passwordConfirmation' => 'required|confirmed',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'passwordConfirmation' => $validatedData['passwordConfirmation'],
        ]);
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
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'string',
            'email' => 'string',
            'role' => 'string',
            'password' => 'string',
        ]);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $validatedData['role_id'],
            'password' => $validatedData['password'],
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->destroy();
    }
}
