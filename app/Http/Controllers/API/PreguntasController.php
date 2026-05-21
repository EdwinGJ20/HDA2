<?php

namespace App\Http\Controllers\API;

use App\Models\Preguntas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreguntasController extends Controller
{
    // LISTAR TODAS
    public function index()
    {
        return response()->json(Preguntas::all(), 200);
    }
    // En PreguntasController.php
public function porTest($id_test)
{
    $preguntas = Preguntas::where('ID_test', $id_test)->get();
    return response()->json($preguntas);
}

    // CREAR
    public function store(Request $request)
    {
        $pregunta = Preguntas::create($request->all());

        return response()->json([
            'message' => 'Pregunta creada',
            'data' => $pregunta
        ], 201);
    }

    // MOSTRAR UNA
    public function show($id)
    {
        return response()->json(
            Preguntas::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $pregunta = Preguntas::findOrFail($id);
        $pregunta->update($request->all());

        return response()->json([
            'message' => 'Pregunta actualizada',
            'data' => $pregunta
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $pregunta = Preguntas::findOrFail($id);
        $pregunta->delete();

        return response()->json([
            'message' => 'Pregunta eliminada'
        ]);
        
    }
    // Método para obtener preguntas de un cuestionario específico
public function obtenerPorTest($id_test)
{
    // 1. Buscamos las preguntas filtradas por el ID del test asignado
    $preguntas = Preguntas::where('ID_test', $id_test)->get();

    if ($preguntas->isEmpty()) {
        return response()->json(['message' => 'No hay preguntas para este test'], 404);
    }

    // 2. Definimos las opciones que van a pintar los RadioButtons en Android Studio
    $opcionesFijas = [
        "Casi siempre",
        "Frecuentemente",
        "A veces",
        "Nunca o casi nunca"
    ];

    // 3. Formateamos la respuesta para asegurar que vayan mapeadas con la lista de opciones obligatoria
    $respuestaFormateada = $preguntas->map(function($item) use ($opcionesFijas) {
        return [
            'ID_pregunta' => $item->ID_pregunta, 
            'pregunta'    => $item->Pregunta ?? $item->pregunta ?? 'Pregunta sin texto', 
            'opciones'    => $opcionesFijas // <-- ¡Inyección crucial del arreglo para que Android no truene!
        ];
    });

    return response()->json($respuestaFormateada, 200);
}
}
