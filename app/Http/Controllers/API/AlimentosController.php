<?php

namespace App\Http\Controllers\API;

use App\Models\Alimentos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlimentosController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(Alimentos::all(), 200);
    }

    // CREAR
    public function store(Request $request)
    {
        $alimento = Alimentos::create($request->all());

        return response()->json([
            'message' => 'Alimento agregado correctamente',
            'data' => $alimento
        ], 201);
    }

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(
            Alimentos::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $alimento = Alimentos::findOrFail($id);
        $alimento->update($request->all());

        return response()->json([
            'message' => 'Alimento actualizado',
            'data' => $alimento
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $alimento = Alimentos::findOrFail($id);
        $alimento->delete();

        return response()->json([
            'message' => 'Alimento eliminado'
        ]);
    }
}