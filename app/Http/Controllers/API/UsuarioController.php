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
    try {
        $data = $request->all();

        // 1. Ciframos la contraseña si viene en la petición
        if ($request->has('Password')) {
            $data['Password'] = Hash::make($request->Password);
        }

        // 2. Forzamos la fecha de registro actual (¡Crucial ya que timestamps = false!)
        if (!isset($data['Fecha_Registro'])) {
            $data['Fecha_Registro'] = now()->toDateTimeString(); // O now()->format('Y-m-d') según tu tipo de columna
        }

        // 3. Evitamos errores de BD asignando valores por defecto si no vienen de Android
        if (!isset($data['Edad'])) {
            $data['Edad'] = 0; // O null si tu columna en la BD acepta nulls
        }

        if (!isset($data['Localidad'])) {
            $data['Localidad'] = 'No especificada'; // O null si acepta nulls
        }

        // 4. Creamos el usuario
        $usuario = Usuario::create($data);

        return response()->json([
            'message' => 'Usuario creado con éxito y contraseña cifrada',
            'data' => $usuario
        ], 201);

    } catch (\Exception $e) {
        // Si sigue fallando por otra columna, esto te dirá exactamente cuál es en Android
        return response()->json([
            'message' => 'Error de Base de Datos: ' . $e->getMessage()
        ], 500);
    }
}
    // MÉTODO DE LOGIN (Ajustado a Correo_Electronico)
  public function login(Request $request) {
    $request->validate([
        'Correo_Electronico' => 'required|email',
        'Password' => 'required'
    ]);

    $usuario = Usuario::where('Correo_Electronico', $request->Correo_Electronico)->first();

    if ($usuario && Hash::check($request->Password, $usuario->Password)) {
        
        // Si el usuario es administrador, saltamos el 2FA por comodidad de gestión
        if ($usuario->Rol === 'admin') {
            return response()->json(['message' => 'Acceso correcto admin', 'usuario' => $usuario], 200);
        }

        // GENERAMOS CÓDIGO DE 6 DÍGITOS PARA EL USUARIO NORMAL
        $codigo = rand(100000, 999999);
        $usuario->codigo_2fa = $codigo; 
        $usuario->save();

        // AQUÍ ENVIARIAS EL EMAIL USANDO LA LÓGICA DE LARAVEL:
        // Mail::to($usuario->Correo_Electronico)->send(new Send2faCode($codigo));

        return response()->json([
            'message' => 'Código 2FA enviado al correo',
            'usuario' => $usuario // Lo mandamos para que Android sepa el rol, pero retenido
        ], 200);
    }
    
    return response()->json(['message' => 'Credenciales inválidas'], 401);
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