<?php

namespace App\Http\Controllers;

use App\Models\PhysicalProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhysicalProgressController extends Controller
{
    // Method to create a new progress
    public function store(Request $request)
    {
        // Validate the sent data
        $validatedData = $request->validate([
            'weight' => 'required|numeric',
            'measurements' => 'required|numeric',
            'sports_performance' => 'required|string',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create a new physical progress
        $progress = new PhysicalProgress();
        $progress->weight = $validatedData['weight'];
        $progress->measurements = $validatedData['measurements'];
        $progress->sports_performance = $validatedData['sports_performance'];
        $progress->user_id = $user->id; // Associate the user with the progress
        $progress->save();

        // JSON response with the newly created progress
        return response()->json($progress, 201);
    }

    /**
     * Retrieve all physical progress for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgresshistory()
    {
       $user = Auth::user(); 
       $progress = $user->physicalProgress->sortBy('created_at');
    
       return response()->json($progress);
    }

    /**
     * Delete a specific physical progress for the authenticated user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $progress = PhysicalProgress::where('user_id', $user->id)->findOrFail($id);
        $progress->delete();
        return response()->json([
            'message' => 'Physical progress deleted successfully.'
        ], 200);
    }

    /**
     * Update a specific physical progress for the authenticated user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $progress = PhysicalProgress::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'weight' => 'nullable|numeric',
            'measurements' => 'nullable|numeric',
            'sports_performance' => 'nullable|string',
        ]);

        $progress->weight = $request->input('weight', $progress->weight);
        $progress->measurements = $request->input('measurements', $progress->measurements);
        $progress->sports_performance = $request->input('sports_performance', $progress->sports_performance);

        $progress->save();

        return response()->json($progress, 200);
    }

    /**
     * Update the status of a specific physical progress for the authenticated user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $progress = PhysicalProgress::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Completed',
        ]);

        $progress->status = $request->input('status');
        $progress->save();

        return response()->json($progress, 200);
    }
}