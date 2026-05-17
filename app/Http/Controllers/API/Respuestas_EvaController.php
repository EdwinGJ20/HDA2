<?php

namespace App\Http\Controllers\API;

use App\Models\Respuestas_Evaluacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Respuestas_EvaController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(Respuestas_Evaluacion::all(), 200);
    }

    // CREAR
    public function store(Request $request)
    {
        $respuesta = Respuestas_Evaluacion::create($request->all());

        return response()->json([
            'message' => 'Respuesta registrada',
            'data' => $respuesta
        ], 201);
    }

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(
            Respuestas_Evaluacion::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $respuesta = Respuestas_Evaluacion::findOrFail($id);
        $respuesta->update($request->all());

        return response()->json([
            'message' => 'Respuesta actualizada',
            'data' => $respuesta
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $respuesta = Respuestas_Evaluacion::findOrFail($id);
        $respuesta->delete();

        return response()->json([
            'message' => 'Respuesta eliminada'
        ]);
    }
}