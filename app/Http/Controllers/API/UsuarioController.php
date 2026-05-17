<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(Usuario::all(), 200);
    }

    // CREAR CON CIFRADO
    public function store(Request $request)
    {
        $data = $request->all();

        // Ciframos la contraseña si viene en la petición
        if ($request->has('Password')) {
            $data['Password'] = Hash::make($request->Password);
        }

        $usuario = Usuario::create($data);

        return response()->json([
            'message' => 'Usuario creado con éxito y contraseña cifrada',
            'data' => $usuario
        ], 201);
    }

    // MÉTODO DE LOGIN (Ajustado a Correo_Electronico)
    public function login(Request $request)
    {
        // Validamos que envíen los campos correctos según tu tabla
        $request->validate([
            'Correo_Electronico' => 'required|email',
            'Password' => 'required'
        ]);

        // Buscamos por el nombre exacto de tu columna
        $usuario = Usuario::where('Correo_Electronico', $request->Correo_Electronico)->first();

        // Verificamos existencia y comparamos hash de la contraseña
        if ($usuario && Hash::check($request->Password, $usuario->Password)) {
            return response()->json([
                'message' => 'Acceso correcto',
                'usuario' => $usuario
            ], 200);
        }

        return response()->json([
            'message' => 'Credenciales inválidas (Correo o Contraseña incorrectos)'
        ], 401);
    }

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(
            Usuario::findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $data = $request->all();

        // Si el usuario decide actualizar su contraseña, se cifra de nuevo
        if ($request->has('Password')) {
            $data['Password'] = Hash::make($request->Password);
        }

        $usuario->update($data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }
}