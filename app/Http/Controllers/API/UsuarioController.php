<?php

namespace App\Http\Controllers\API;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // IMPORTANTE: Agregamos el import de Mail

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
                $data['Fecha_Registro'] = now()->toDateTimeString();
            }

            // 3. Evitamos errores de BD asignando valores por defecto si no vienen de Android
            if (!isset($data['Edad'])) {
                $data['Edad'] = 0;
            }

            if (!isset($data['Localidad'])) {
                $data['Localidad'] = 'No especificada';
            }

            // 4. Creamos el usuario
            $usuario = Usuario::create($data);

            return response()->json([
                'message' => 'Usuario creado con éxito y contraseña cifrada',
                'data' => $usuario
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error de Base de Datos: ' . $e->getMessage()
            ], 500);
        }
    }

   // MÉTODO DE LOGIN AJUSTADO PARA EVITAR VALIDACIONES AUTOMÁTICAS OCULTAS
    public function login(Request $request) 
    {
        // En lugar de usar $request->validate() que a veces se dispara en inglés y bloquea la petición antes de tiempo,
        // validamos manualmente con un validador clásico:
        $validator = \Validator::make($request->all(), [
            'Correo_Electronico' => 'required|email',
            'Password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Por favor, introduce un correo y contraseña válidos.'], 422);
        }

        // Buscamos al usuario por correo electrónico
        $usuario = Usuario::where('Correo_Electronico', $request->Correo_Electronico)->first();

        // Verificamos si el usuario existe y si la contraseña coincide con el Hash
        if ($usuario && Hash::check($request->Password, $usuario->Password)) {
            
            // Si el usuario es administrador, saltamos el 2FA por comodidad de gestión
            if ($usuario->Rol === 'admin' || $usuario->Rol === 'administrador') {
                return response()->json([
                    'message' => 'Acceso correcto admin', 
                    'usuario' => $usuario
                ], 200);
            }

            // GENERAMOS CÓDIGO DE 6 DÍGITOS PARA EL USUARIO NORMAL
            $codigo = rand(100000, 999999);
            $usuario->codigo_2fa = $codigo; 
            $usuario->save();

            try {
                Mail::raw("Tu código de verificación de seguridad para HDA1 es: $codigo", function ($message) use ($usuario) {
                    $message->to($usuario->Correo_Electronico)
                            ->subject("Código de Verificación 2FA");
                });
            } catch (\Exception $mailException) {
                \Log::error("Error enviando correo 2FA: " . $mailException->getMessage());
            }

            return response()->json([
                'message' => 'Código 2FA enviado al correo',
                'usuario' => $usuario 
            ], 200);
        }
        
        // Retorno manual explícito
        return response()->json(['message' => 'Correo o contraseña incorrectos'], 401);
    }

    // NUEVO MÉTODO: RECIBE EL CÓDIGO DESDE ANDROID Y DA ACCESO COMPLETO
   public function verify2fa(Request $request)
{
    // TRUCO DE DEBUG: Si quieres ver en el celular qué campos están llegando de Android,
    // descomenta la línea de abajo para retornar todo el contenido de la petición:
    // return response()->json(['message' => 'Android envió: ' . json_encode($request->all())], 422);

    $correo = $request->input('Correo_Electronico');
    
    // Buscamos el código con las dos variantes comunes de nombres por seguridad
    $codigo = $request->input('Codigo_2fa') ?? $request->input('codigo_2fa');

    $usuario = Usuario::where('Correo_Electronico', $correo)
                      ->where('codigo_2fa', $codigo)
                      ->first();

    if ($usuario) {
        $usuario->codigo_2fa = null;
        $usuario->save();

        return response()->json([
            'message' => 'Verificación exitosa'
        ], 200);
    }

    return response()->json([
        'message' => "Código incorrecto. Buscado: Correo[$correo] Código[$codigo]"
    ], 422);
}

    // MOSTRAR UNO
    public function show($id)
    {
        return response()->json(Usuario::findOrFail($id));
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $data = $request->all();

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