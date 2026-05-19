<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario_Alimentos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Usuario_AlimentosController extends Controller
{
    // LISTAR TODAS LAS RELACIONES (LIMPIO Y CORREGIDO)
   public function index()
{
    try {
        // Cambiado 'alimento' por 'alimentos' con s
        $datos = Usuario_Alimentos::with(['usuario', 'alimentos'])->get();
        return response()->json($datos, 200);
    } catch (\Exception $e) {
        return response()->json([
            'error_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}

    // ASIGNAR ALIMENTO A USUARIO
    public function store(Request $request)
    {
        $relacion = Usuario_Alimentos::create($request->all());

        return response()->json([
            'message' => 'Alimento asignado al usuario con éxito',
            'data' => $relacion
        ], 201);
    }

    // MOSTRAR POR USUARIO
public function show($id_usuario)
{
    // Cambiado 'alimento' por 'alimentos' con s
    $asignaciones = Usuario_Alimentos::where('ID_usuario', $id_usuario)
                    ->with('alimentos')
                    ->get();

    return response()->json($asignaciones);
}

    // ELIMINAR ASIGNACIÓN
    public function destroy(Request $request)
    {
        Usuario_Alimentos::where('ID_usuario', $request->ID_usuario)
            ->where('ID_Alimento', $request->ID_Alimento)
            ->delete();

        return response()->json([
            'message' => 'Relación eliminada'
        ]);
    }
}