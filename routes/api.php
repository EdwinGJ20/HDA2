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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::post('HDA2/login', [App\Http\Controllers\API\UsuarioController::class, 'login']);
Route::apiResource('HDA2/usuario', UsuarioController::class);
Route::apiResource('HDA2/usuario_test', Usuario_TestController::class);
Route::apiResource('HDA2/usuario_alimentos', Usuario_AlimentosController::class);
Route::apiResource('HDA2/tipos_resultados', Tipos_ResultadosController::class);
Route::apiResource('HDA2/tipos_diagnostico', Tipos_DiagnosticoController::class);
Route::apiResource('HDA2/test', TestController::class);
// Ruta estándar para pedir un test por su ID específico (/api/test/1 o /api/test/2)
Route::get('/test/{id}', [TestController::class, 'show']);
// OPCIONAL: Ruta por si quieres pedir un test aleatorio sin pasar un ID (/api/test_aleatorio)
Route::get('/test_aleatorio', [TestController::class, 'getRandomTest']);
Route::apiResource('HDA2/Respuestas_Eva', Respuestas_EvaController::class);
Route::get('HDA2/preguntas/test/{id_test}', [PreguntasController::class, 'obtenerPorTest']);
Route::apiResource('HDA2/Evaluacion', EvaluacionController::class);
Route::apiResource('HDA2/Alimentos', AlimentosController::class);
Route::apiResource('HDA2/Actividades', ActividadesController::class);
Route::get('evaluaciones/reporte/{id}', [App\Http\Controllers\API\EvaluacionController::class, 'obtenerReporteCompleto']);
// Rutas de Comunidad
Route::get('HDA2/foros', [ComunidadController::class, 'getForos']);
Route::post('HDA2/foros', [ComunidadController::class, 'crearForo']);

Route::get('HDA2/diario/{idUsuario}', [ComunidadController::class, 'getMisDiarios']);
Route::post('HDA2/diario', [ComunidadController::class, 'guardarDiario']);

// --- PUENTE EXCLUSIVO PARA EL LOGIN WEB (DENTRO DEL REPOSITORIO UNIFICADO) ---
Route::get('puente-web/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.puente');

Route::get('HDA2/chat/{user1}/{user2}', [ComunidadController::class, 'getChatPrivado']);
Route::post('HDA2/chat', [ComunidadController::class, 'enviarMensaje']);
// Ruta para obtener el perfil completo del usuario
Route::get('HDA2/perfil_completo/{idUsuario}', [ComunidadController::class, 'getPerfilCompleto']);
