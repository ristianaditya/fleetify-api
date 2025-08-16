<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $departements = Departement::with('employees')->paginate($perPage);

        return response()->json($departements, 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'departement_name' => 'required|string|max:255',
            'max_clock_in_time' => 'nullable|date_format:H:i',
            'max_clock_out_time' => 'nullable|date_format:H:i',
        ]);

        $departement = Departement::create($validated);
        return response()->json($departement, 201);
    }

    public function show($id)
    {
        $departement = Departement::findOrFail($id);
        return response()->json($departement, 200);
    }

    public function update(Request $request, $id)
    {
        $departement = Departement::findOrFail($id);
        $departement->update($request->all());
        return response()->json($departement, 200);
    }

    public function destroy($id)
    {
        Departement::destroy($id);
        return response()->json(null, 204);
    }
}
