<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Foro;
use App\Models\Diario;
use App\Models\Chat;
use Illuminate\Http\Request;

class ComunidadController extends Controller
{
    // --- LÓGICA DE FOROS ---
    public function getForos() {
        return response()->json(Foro::with('usuario:ID_usuario,Nombre')->latest()->get());
    }

    public function crearForo(Request $request) {
        $foro = Foro::create($request->all());
        return response()->json(['message' => 'Post creado', 'data' => $foro], 201);
    }

    // --- LÓGICA DE DIARIOS (PRIVADOS) ---
    public function getMisDiarios($idUsuario) {
        return response()->json(Diario::where('ID_usuario', $idUsuario)->latest()->get());
    }

    public function guardarDiario(Request $request) {
        $entrada = Diario::create($request->all());
        return response()->json(['message' => 'Nota guardada', 'data' => $entrada], 201);
    }

    // --- LÓGICA DE CHATS ---
    public function enviarMensaje(Request $request) {
        $chat = Chat::create($request->all());
        return response()->json(['message' => 'Mensaje enviado', 'data' => $chat], 201);
    }

    public function getChatPrivado($user1, $user2) {
        $mensajes = Chat::where(function($q) use ($user1, $user2) {
            $q->where('ID_emisor', $user1)->where('ID_receptor', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('ID_emisor', $user2)->where('ID_receptor', $user1);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($mensajes);
    }

    // Obtener TODO el perfil del usuario
public function getPerfilCompleto($idUsuario)
{
    try {
        // 1. Última evaluación (Ordenada por ID porque no hay created_at)
        $ultimaEvaluacion = \App\Models\Evaluacion::with('diagnostico')
            ->where('ID_usuario', $idUsuario)
            ->orderBy('ID_evaluacion', 'desc') 
            ->first();

        // 2. Recomendaciones
        $recomendaciones = ['alimentos' => [], 'actividades' => []];

        if ($ultimaEvaluacion) {
            $riesgo = $ultimaEvaluacion->Nivel_Riesgo;
            $recomendaciones['alimentos'] = \App\Models\Alimentos::where('Clasificacion', $riesgo)->get();
            $recomendaciones['actividades'] = \App\Models\Actividades::where('Clasificacion', $riesgo)->get();
        }

        // 3. Actividad en la comunidad (Ordenada por ID_foro)
        $misPosts = \App\Models\Foro::where('ID_usuario', $idUsuario)
            ->orderBy('ID_foro', 'desc') 
            ->take(5)
            ->get();

        // 4. Diario Personal (Ordenada por ID_diario)
        $misNotasDiario = \App\Models\Diario::where('ID_usuario', $idUsuario)
            ->orderBy('ID_diario', 'desc') 
            ->take(5)
            ->get();

        // 5. Últimos mensajes recibidos (Ordenada por ID_chat)
        $mensajesRecientes = \App\Models\Chat::where('ID_receptor', $idUsuario)
            ->orderBy('ID_chat', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'usuario_id' => $idUsuario,
            'salud' => [
                'ultima_evaluacion' => $ultimaEvaluacion,
                'recomendaciones' => $recomendaciones
            ],
            'comunidad' => [
                'mis_posts_foro' => $misPosts,
                'mensajes_recientes' => $mensajesRecientes
            ],
            'personal' => [
                'ultimas_notas_diario' => $misNotasDiario
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
