<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\NoteRequest;
use App\Http\Resources\V1\NoteResource;
use App\Http\Resources\V1\NoteCollection;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new NoteCollection(Note::whereUserId(auth()->id())->latest()->paginate(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {
        return new NoteResource($request->user()->notes()->create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);
        return new NoteResource($note, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        Gate::authorize('update', $note);
        $note->update($request->all());
        return new NoteResource($note, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $note->delete();
        return new NoteResource($note, 200);
    }
}
