<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;

class MemberController extends Controller
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
    public function store(StoreMemberRequest $request)
    {
        $validatedData = $request->validated();

        Member::create([
            'nim' => $validatedData['nim'],
            'name' => $validatedData['name'],
            'division_id' => $validatedData['division_id'],
            'position' => $validatedData['position'],
            'photos' => $validatedData['photos'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $validatedData = $request->validated();
        Member::findOrFail($member)->update([
            'nim' => $validatedData['nim'],
            'name' => $validatedData['name'],
            'division_id' => $validatedData['division_id'],
            'position' => $validatedData['position'],
            'photos' => $validatedData['photos'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        Member::findOrFail($member)->destroy();
    }
}
