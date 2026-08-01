<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    /**
     * Liste tous les cours avec pagination.
     */
    public function index()
    {
        // On récupère les cours, on peut aussi charger les étudiants inscrits si besoin
        $cours = Cours::withCount('etudiants')->paginate(10);
        return response()->json($cours);
    }

    /**
     * Créer un nouveau cours.
     */
    public function store(Request $request)
    {
        // Exemple pour store()
$validated = $request->validate([
    'libelle' => 'required|string|max:255',
    'professeur' => 'required|string|max:255',
    'volume_horaire' => 'required|integer|min:1',
]);

        $cours = Cours::create($validated);

        return response()->json($cours, 201);
    }

    /**
     * Afficher un cours spécifique.
     */
    public function show(Cours $cours)
    {
        // Charge les étudiants liés à ce cours
        return response()->json($cours->load('etudiants'));
    }

    /**
     * Mettre à jour un cours.
     */
    public function update(Request $request, Cours $cours)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'sometimes|required|string|unique:cours,code,' . $cours->id,
        ]);

        $cours->update($validated);

        return response()->json($cours);
    }

    /**
     * Supprimer un cours.
     */
    public function destroy(Cours $cours)
    {
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé avec succès'], 204);
    }
}