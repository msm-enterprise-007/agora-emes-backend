<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternshipApplicationRequest;
use App\Http\Requests\ReviewInternshipApplicationRequest;
use App\Models\InternshipApplication;
use Illuminate\Http\JsonResponse;
use App\Services\InternshipApplicationService;
use Illuminate\Support\Facades\DB;

class InternshipApplicationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            InternshipApplication::with('reviewer')
                ->latest()
                ->get()
        );
    }

    public function store(StoreInternshipApplicationRequest $request): JsonResponse
    {
        $application = InternshipApplication::create(
            $request->validated()
        );

        return response()->json($application, 201);
    }

    public function show(InternshipApplication $internshipApplication): JsonResponse
    {
        return response()->json(
            $internshipApplication->load('reviewer')
        );
    }

    public function review(
        ReviewInternshipApplicationRequest $request,
        InternshipApplication $internshipApplication,
        InternshipApplicationService $service
    ): JsonResponse {
    
        if ($request->status === 'approved') {
    
            $application = $service->approve(
                $internshipApplication,
                auth()->id()
            );
    
            return response()->json($application);
        }
    
        $internshipApplication->update([
            'status' => 'rejected',
            'admin_comment' => $request->admin_comment,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    
        return response()->json($internshipApplication);
    }
}