<?php

namespace App\Http\Controllers\API;

use App\Models\Tipos_Resultados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Tipos_ResultadosController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(Tipos_Resultados::all(), 200);
    }

    // CREAR
    public function store(Request $request)
    {
        $tipoResultado = Tipos_Resultados::create($request->all());

        return response()->json([
            'message' => 'Tipo de resultado creado',
            'data' => $tipoResultado
        ], 201);
    }

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(
            Tipos_Resultados::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $tipoResultado = Tipos_Resultados::findOrFail($id);
        $tipoResultado->update($request->all());

        return response()->json([
            'message' => 'Tipo de resultado actualizado',
            'data' => $tipoResultado
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $tipoResultado = Tipos_Resultados::findOrFail($id);
        $tipoResultado->delete();

        return response()->json([
            'message' => 'Tipo de resultado eliminado'
        ]);
    }
}