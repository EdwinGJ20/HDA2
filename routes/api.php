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


Route::post('HDA1/login', [App\Http\Controllers\API\UsuarioController::class, 'login']);
Route::apiResource('HDA1/usuario', UsuarioController::class);
Route::apiResource('HDA1/usuario_test', Usuario_TestController::class);
Route::apiResource('HDA1/usuario_alimentos', Usuario_AlimentosController::class);
Route::apiResource('HDA1/tipos_resultados', Tipos_ResultadosController::class);
Route::apiResource('HDA1/tipos_diagnostico', Tipos_DiagnosticoController::class);
Route::apiResource('HDA1/test', TestController::class);
Route::apiResource('HDA1/Respuestas_Eva', Respuestas_EvaController::class);
Route::get('HDA1/preguntas/test/{id_test}', [PreguntasController::class, 'obtenerPorTest']);
Route::apiResource('HDA1/Evaluacion', EvaluacionController::class);
Route::apiResource('HDA1/Alimentos', AlimentosController::class);
Route::apiResource('HDA1/Actividades', ActividadesController::class);
Route::get('evaluaciones/reporte/{id}', [App\Http\Controllers\API\EvaluacionController::class, 'obtenerReporteCompleto']);
// Rutas de Comunidad
Route::get('HDA1/foros', [ComunidadController::class, 'getForos']);
Route::post('HDA1/foros', [ComunidadController::class, 'crearForo']);

Route::get('HDA1/diario/{idUsuario}', [ComunidadController::class, 'getMisDiarios']);
Route::post('HDA1/diario', [ComunidadController::class, 'guardarDiario']);

Route::get('HDA1/chat/{user1}/{user2}', [ComunidadController::class, 'getChatPrivado']);
Route::post('HDA1/chat', [ComunidadController::class, 'enviarMensaje']);
// Ruta para obtener el perfil completo del usuario
Route::get('HDA1/perfil_completo/{idUsuario}', [ComunidadController::class, 'getPerfilCompleto']);