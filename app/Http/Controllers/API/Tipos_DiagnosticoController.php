<?php

namespace App\Http\Controllers\API;

use App\Models\Tipos_Diagnosticos; // <--- AGREGAR LA 'S' AQUÍ
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Tipos_DiagnosticoController extends Controller
{
    public function index()
    {
        // <--- AGREGAR LA 'S' AQUÍ TAMBIÉN
        return response()->json(Tipos_Diagnosticos::all(), 200);
    }

    public function store(Request $request)
    {
        $diagnostico = Tipos_Diagnosticos::create($request->all());
        return response()->json(['message' => 'Creado', 'data' => $diagnostico], 201);
    }

    public function show($id)
    {
        return response()->json(Tipos_Diagnosticos::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $diagnostico = Tipos_Diagnosticos::findOrFail($id);
        $diagnostico->update($request->all());
        return response()->json(['message' => 'Actualizado', 'data' => $diagnostico]);
    }

    public function destroy($id)
    {
        Tipos_Diagnosticos::destroy($id);
        return response()->json(['message' => 'Eliminado']);
    }
}