<?php

namespace App\Http\Controllers;

use App\Models\PhysicalProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhysicalProgressController extends Controller
{
    // Méthode pour créer un nouveau progrès
    public function store(Request $request)
    {
        // Validation des données envoyées
        $validatedData = $request->validate([
            'weight' => 'required|numeric',
            'measurements' => 'required|numeric',
            'sports_performance' => 'required|string',
        ]);

        // Récupération de l'utilisateur authentifié
        $user = Auth::user();

        // Création d'un nouveau progrès physique
        $progress = new PhysicalProgress();
        $progress->weight = $validatedData['weight'];
        $progress->measurements = $validatedData['measurements'];
        $progress->sports_performance = $validatedData['sports_performance'];
        $progress->user_id = $user->id; // Associer l'utilisateur au progrès
        $progress->save();

        // Réponse JSON avec le nouveau progrès créé
        return response()->json($progress, 201);
    }


    /**
     * Récupère tous les progrès physiques de l'utilisateur authentifié
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function getProgressForAuthUser()
     {
        $user = Auth::user(); 
        $progress = $user->physicalProgress; 
     
        return response()->json($progress);
     }



    /**
     * Supprime un progrès physique spécifique pour l'utilisateur authentifié
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProgress($id)
    {
        $user = Auth::user();
        $progress = PhysicalProgress::where('user_id', $user->id)->findOrFail($id);
        $progress->delete();
        return response()->json([
            'message' => 'Progrès physique supprimé avec succès.'
        ], 200);
    }
     
}