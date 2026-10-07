<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;

class NewsController extends Controller
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
    public function store(StoreNewsRequest $request)
    {
        $validatedData = $request->validated();
        News::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'thumbnail' => $validatedData['thumbnail'],
            'published_at' => $validatedData['published_at'],
            'status' => $validatedData['status'],
            'user_id' => $validatedData['user_id'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $validatedData = $request->validated();
        News::findOrFail($news->id)->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'thumbnail' => $validatedData['thumbnail'],
            'published_at' => $validatedData['published_at'],
            'status' => $validatedData['status'],
            'user_id' => $validatedData['user_id'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        News::findOrFail($news->id)->destroy();
    }
}
