<?php

namespace App\Http\Controllers\API;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    // LISTAR TODOS (Ahora incluye sus preguntas)
    public function index()
    {
        return response()->json(Test::with('preguntas')->get(), 200);
    }

    // CREAR
    public function store(Request $request)
    {
        $test = Test::create($request->all());

        return response()->json([
            'message' => 'Test creado',
            'data' => $test
        ], 201);
    }

    // MOSTRAR UNO ESPECÍFICO CON SUS PREGUNTAS (Para /test/1 o /test/2)
    // MOSTRAR PREGUNTAS DE UN TEST ESPECÍFICO FORMATEADO PARA ANDROID
    public function show($id)
    {
        // 1. Buscamos el test cargando sus preguntas asociadas
        $test = Test::with('preguntas')->findOrFail($id);

        // 2. Definimos las opciones que van a pintar los RadioButtons en Android
        // (Modifica estos textos si tu test usa opciones diferentes en tu capstone)
        $opcionesFijas = [
            "Casi siempre",
            "Frecuentemente",
            "A veces",
            "Nunca o casi nunca"
        ];

        // 3. Convertimos la respuesta en la lista directa de preguntas que espera Retrofit
        $preguntasFormateadas = $test->preguntas->map(function($item) use ($opcionesFijas) {
            return [
                'ID_pregunta' => $item->ID_pregunta, 
                'pregunta'    => $item->Pregunta,    // <- Ojo: Verifica que en tu Modelo/BD sea "Pregunta" con P mayúscula
                'opciones'    => $opcionesFijas      // El array que tu TestScreen.kt va a mapear
            ];
        });

        // 4. Retornamos la LISTA limpia que tu objeto de Android espera recibir
        return response()->json($preguntasFormateadas, 200);
    }

    // NUEVO MÉTODO: OBTENER UN TEST ALEATORIO DIRECTAMENTE DESDE LA API
    // (Útil si prefieres no calcular el random desde el Login de Android)
    public function getRandomTest()
    {
        // Trae un test al azar (ID 1 o 2) con todas sus preguntas
        $test = Test::with('preguntas')->inRandomOrder()->firstOrFail();

        return response()->json($test, 200);
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $test = Test::findOrFail($id);
        $test->update($request->all());

        return response()->json([
            'message' => 'Test actualizado',
            'data' => $test
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        $test->delete();

        return response()->json([
            'message' => 'Test eliminado'
        ]);
    }
    // En tu TestController.php
// Ajuste: Ahora devuelve siempre el primer test (permitiendo repetición)
    public function getNextTestForUser($idUsuario)
    {
        // Cambiamos first() por inRandomOrder()->first()
    $test = Test::with('preguntas')->inRandomOrder()->first();

    if (!$test) {
        return response()->json(['message' => 'No hay tests disponibles'], 404);
    }

        // 3. Aplicar el formato para Android
        $opcionesFijas = ["Casi siempre", "Frecuentemente", "A veces", "Nunca o casi nunca"];

        $testFormateado = [
            'ID_test' => $test->ID_test,
            'Nombre' => $test->Nombre,
            'Descripcion' => $test->Descripcion,
            'preguntas' => $test->preguntas->map(function($item) use ($opcionesFijas) {
                return [
                    'ID_pregunta' => $item->ID_pregunta,
                    'pregunta'    => $item->Pregunta,
                    'opciones'    => $opcionesFijas
                ];
            })
        ];

        return response()->json($testFormateado, 200);
    }
    // Historial de resultados del usuario
public function getHistorial($idUsuario)
{
    $historial = \App\Models\Evaluacion::where('ID_usuario', $idUsuario)
        ->orderBy('Fecha', 'DESC')
        ->get(['ID_test', 'Puntaje_Total', 'Fecha', 'Nivel_Riesgo']);

    return response()->json($historial, 200);
}
}