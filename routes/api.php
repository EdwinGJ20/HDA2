<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsuarioController;
use App\Http\Controllers\API\Usuario_TestController;
use App\Http\Controllers\API\Usuario_AlimentosController;
use App\Http\Controllers\API\Tipos_ResultadosController;
use App\Http\Controllers\API\Tipos_DiagnosticoController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\Respuestas_EvaController;
use App\Http\Controllers\API\PreguntasController;
use App\Http\Controllers\API\EvaluacionController;
use App\Http\Controllers\API\AlimentosController;
use App\Http\Controllers\API\ActividadesController;
use App\Http\Controllers\API\ComunidadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- AUTENTICACIÓN Y USUARIOS ---
Route::post('HDA2/login', [UsuarioController::class, 'login']);
Route::post('HDA2/verificar-2fa', [UsuarioController::class, 'verify2fa']);
Route::apiResource('HDA2/usuario', UsuarioController::class);

// --- CUESTIONARIOS Y PREGUNTAS (UNIFICADOS CON HDA2) ---
Route::apiResource('HDA2/test', TestController::class);

// PUENTE DE SEGURIDAD PARA ANDROID:
// Si Retrofit busca "HDA2/test/1", ejecutará el método show que acabamos de arreglar
Route::get('HDA2/test/{id}', [TestController::class, 'show']); 
Route::get('HDA2/test_aleatorio', [TestController::class, 'getRandomTest']);
Route::get('HDA2/preguntas/test/{id_test}', [PreguntasController::class, 'obtenerPorTest']);

// --- EVALUACIONES Y RESULTADOS ---
Route::apiResource('HDA2/Evaluacion', EvaluacionController::class);
Route::get('HDA2/evaluaciones/reporte/{id}', [EvaluacionController::class, 'obtenerReporteCompleto']);
Route::apiResource('HDA2/Respuestas_Eva', Respuestas_EvaController::class);
Route::apiResource('HDA2/tipos_resultados', Tipos_ResultadosController::class);
Route::apiResource('HDA2/tipos_diagnostico', Tipos_DiagnosticoController::class);

// --- RECOMENDACIONES ---
Route::apiResource('HDA2/Alimentos', AlimentosController::class);
Route::apiResource('HDA2/Actividades', ActividadesController::class);
Route::apiResource('HDA2/usuario_test', Usuario_TestController::class);
Route::apiResource('HDA2/usuario_alimentos', Usuario_AlimentosController::class);

// --- COMUNIDAD, DIARIO Y CHAT ---
Route::get('HDA2/foros', [ComunidadController::class, 'getForos']);
Route::post('HDA2/foros', [ComunidadController::class, 'crearForo']);
Route::put('HDA2/foros/{id}', [ComunidadController::class, 'editarForo']);
Route::get('HDA2/diario/{idUsuario}', [ComunidadController::class, 'getMisDiarios']);
Route::post('HDA2/diario', [ComunidadController::class, 'guardarDiario']);
Route::get('HDA2/chat/{user1}/{user2}', [ComunidadController::class, 'getChatPrivado']);
Route::post('HDA2/chat', [ComunidadController::class, 'enviarMensaje']);
Route::get('HDA2/perfil_completo/{idUsuario}', [ComunidadController::class, 'getPerfilCompleto']);

// --- PUENTE EXCLUSIVO PARA EL LOGIN WEB ---
Route::get('puente-web/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.puente');

// Agrégala al final de routes/api.php por si Android busca sin el prefijo HDA2/
Route::get('preguntas/test/{id_test}', [App\Http\Controllers\API\PreguntasController::class, 'obtenerPorTest']);