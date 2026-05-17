<?php

namespace App\Http\Controllers\API;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(Test::all(), 200);
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

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(
            Test::findOrFail($id)
        );
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