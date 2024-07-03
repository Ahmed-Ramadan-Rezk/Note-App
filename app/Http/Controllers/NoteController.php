<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\NoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Notes';
        // Fetch all notes for the current user
        $notes = Note::whereUserId(auth()->id())->latest()->paginate(5);
        return view('notes.index', compact('notes', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Note';
        return view('notes.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request): RedirectResponse
    {

        $request->user()->notes()->create($request->all());

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);
        $title = 'Note';
        return view('notes.show', compact('note', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        Gate::authorize('update', $note);
        $title = 'Edit Note';
        return view('notes.edit', compact('note', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        Gate::authorize('update', $note);
        $note->update($request->all());

        return redirect()->route('notes.show', $note)
            ->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $note->delete();
        return redirect()->route('notes.index', $note)
            ->with('success', 'Note deleted successfully!');
    }
}
