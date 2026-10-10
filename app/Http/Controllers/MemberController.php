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
        $members = Member::with('divisions')->get();

        return view('member.index', compact('members'));
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

        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $validatedData = $request->validated();
        $members = Member::findOrFail($member->id)->update([
            'nim' => $validatedData['nim'],
            'name' => $validatedData['name'],
            'division_id' => $validatedData['division_id'],
            'position' => $validatedData['position'],
            'photos' => $validatedData['photos'] ?? null,
            'status' => $validatedData['status'],
        ]);

        return redirect()->back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        Member::findOrFail($member->id)->delete();

        return redirect()->back()->with('success', 'Anggota berhasil dihapus.');
    }
}
