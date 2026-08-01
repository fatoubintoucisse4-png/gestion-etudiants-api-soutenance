<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    public function index()
    {
        return response()->json(Etudiant::with('cours')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email',
            'date_naissance' => 'required|date',
        ]);
        return response()->json(Etudiant::create($validated), 201);
    }

    public function show(Etudiant $etudiant)
    {
        return response()->json($etudiant->load('cours'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate([
            'prenom' => 'sometimes|required|string|max:255',
            'nom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:etudiants,email,' . $etudiant->id,
            'date_naissance' => 'sometimes|required|date',
        ]);
        $etudiant->update($validated);
        return response()->json($etudiant);
    }

    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();
        return response()->json(['message' => 'Étudiant supprimé'], 204);
    }

    public function attach(Request $request, Etudiant $etudiant)
    {
        $request->validate(['cours_ids' => 'required|array|exists:cours,id']);
        $etudiant->cours()->attach($request->cours_ids);
        return response()->json(['message' => 'Cours ajoutés']);
    }

    public function detach(Request $request, Etudiant $etudiant)
    {
        $request->validate(['cours_ids' => 'required|array|exists:cours,id']);
        $etudiant->cours()->detach($request->cours_ids);
        return response()->json(['message' => 'Cours retirés']);
    }

    public function sync(Request $request, Etudiant $etudiant)
    {
        $request->validate(['cours_ids' => 'required|array|exists:cours,id']);
        $etudiant->cours()->sync($request->cours_ids);
        return response()->json(['message' => 'Synchronisation réussie']);
    }
}