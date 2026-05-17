<?php

namespace App\Http\Controllers\API;

use App\Models\Actividades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActividadesController extends Controller
{
    // LISTAR TODAS
    public function index()
    {
        return response()->json(Actividades::all(), 200);
    }

    // CREAR
    public function store(Request $request)
    {
        $actividad = Actividades::create($request->all());

        return response()->json([
            'message' => 'Actividad registrada correctamente',
            'data' => $actividad
        ], 201);
    }

    // MOSTRAR UNA
    public function show($id)
    {
        return response()->json(
            Actividades::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $actividad = Actividades::findOrFail($id);
        $actividad->update($request->all());

        return response()->json([
            'message' => 'Actividad actualizada',
            'data' => $actividad
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $actividad = Actividades::findOrFail($id);
        $actividad->delete();

        return response()->json([
            'message' => 'Actividad eliminada'
        ]);
    }
}