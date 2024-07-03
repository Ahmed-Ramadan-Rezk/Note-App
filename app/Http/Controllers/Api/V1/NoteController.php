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
        try {
            $notes = Note::whereUserId(auth()->id())->latest()->paginate(5);
            return new NoteCollection($notes);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No data found !',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {
        try {
            $note = $request->user()->notes()->create($request->all());
            return new NoteResource($note);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Note creation failed. Please try again.',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        try {
            Gate::authorize('view', $note);
            return new NoteResource($note);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No data found !',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        try {
            Gate::authorize('update', $note);
            $note->update($request->all());
            return new NoteResource($note);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Note update failed. Please try again.',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            Gate::authorize('delete', $note);
            $note->delete();
            return new NoteResource($note);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Note deletion failed. Please try again.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
