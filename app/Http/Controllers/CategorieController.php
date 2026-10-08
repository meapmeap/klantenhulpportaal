<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Resources\CategorieResource;

class CategorieController extends Controller
{
    public function index()
    {
        return CategorieResource::collection(Categorie::orderBy('naam')->get());
    }

    public function store(StoreCategorieRequest $request)
    {
        $categorie = Categorie::create($request->validated());

        return new CategorieResource($categorie);
    }

    public function update(StoreCategorieRequest $request, Categorie $categorie)
    {
        $categorie->update($request->validated());

        return new CategorieResource($categorie);
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json([
            'message' => 'Categorie succesvol verwijderd.',
        ]);
    }
}