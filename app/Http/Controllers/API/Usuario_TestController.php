<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario_Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Usuario_TestController extends Controller
{
    // LISTAR TODOS LOS TESTS ASIGNADOS
    public function index()
    {
        return response()->json(Usuario_Test::with(['usuario', 'test'])->get(), 200);
    }

    // ASIGNAR UN TEST A UN USUARIO
    public function store(Request $request)
    {
        $relacion = Usuario_Test::create($request->all());

        return response()->json([
            'message' => 'Test asignado al usuario con éxito',
            'data' => $relacion
        ], 201);
    }

    // MOSTRAR TESTS DE UN USUARIO ESPECÍFICO
    public function show($id_usuario)
    {
        $tests = Usuario_Test::where('ID_usuario', $id_usuario)
                    ->with('test')
                    ->get();

        return response()->json($tests);
    }

    // ELIMINAR ASIGNACIÓN
    public function destroy(Request $request)
    {
        Usuario_Test::where('ID_usuario', $request->ID_usuario)
            ->where('ID_test', $request->ID_test)
            ->delete();

        return response()->json([
            'message' => 'Asignación de test eliminada'
        ]);
    }
}