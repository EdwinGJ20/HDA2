<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario_Alimentos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Usuario_AlimentosController extends Controller
{
    // LISTAR TODAS LAS RELACIONES
    public function index()
    {
        // Traemos la relación con el nombre del usuario y del alimento
        return response()->json(Usuario_Alimentos::with(['usuario', 'alimento'])->get(), 200);
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
        $asignaciones = Usuario_Alimentos::where('ID_usuario', $id_usuario)
                        ->with('alimento')
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