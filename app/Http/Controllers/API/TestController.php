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
    public function show($id)
    {
        // Buscamos el test por su ID_test cargando de golpe su relación 'preguntas'
        $test = Test::with('preguntas')->findOrFail($id);

        return response()->json($test, 200);
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
}