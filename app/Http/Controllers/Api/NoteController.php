<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Note::with(['internship', 'author'])
                ->latest()
                ->get()
        );
    }

    public function store(StoreNoteRequest $request): JsonResponse
    {
        $note = Note::create($request->validated());

        return response()->json(
            $note->load(['internship', 'author']),
            201
        );
    }

    public function show(Note $note): JsonResponse
    {
        return response()->json(
            $note->load(['internship', 'author'])
        );
    }

    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $note->update($request->validated());

        return response()->json(
            $note->load(['internship', 'author'])
        );
    }

    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json([
            'message' => 'Note supprimée avec succès.'
        ]);
    }
}