<?php

namespace App\Http\Controllers\API;

use App\Models\Evaluacion;
use App\Models\Respuestas_Evaluacion;
use App\Models\Tipos_Resultados;
use App\Models\Tipos_Diagnosticos;
use App\Models\Alimentos;
use App\Models\Actividades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    // LISTAR TODAS
    public function index()
    {
        return response()->json(Evaluacion::with('usuario')->get(), 200);
    }

    // CREAR (Lógica corregida para multi-test)
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Calcular puntaje total
            $respuestasEnviadas = $request->respuestas; 
            $puntajeTotal = collect($respuestasEnviadas)->sum('Puntaje');
            $idTestActual = $request->ID_test; 

            // 2. Determinar Nivel de Riesgo (Filtrando por ID_test)
            $resultado = Tipos_Resultados::where('ID_test', $idTestActual)
                            ->where('Puntaje_Asig', '>=', $puntajeTotal)
                            ->orderBy('Puntaje_Asig', 'asc')
                            ->first();

            $nivelRiesgo = $resultado ? $resultado->Tipo : 'Riesgo Bajo';

            // 3. Buscar Diagnóstico sugerido
            $diagnostico = Tipos_Diagnosticos::where('Nivel_Riesgo', $nivelRiesgo)->first();

            // 4. Crear Evaluación
            $evaluacion = Evaluacion::create([
                'ID_usuario'     => $request->ID_usuario,
                'ID_test'        => $idTestActual,
                'Fecha'          => now()->format('Y-m-d'),
                'Puntaje_Total'  => $puntajeTotal,
                'Nivel_Riesgo'   => $nivelRiesgo,
                'ID_Diagnostico' => $diagnostico ? $diagnostico->ID_Diagnostico : null
            ]);

            // 5. Guardar cada respuesta individual
            foreach ($respuestasEnviadas as $resp) {
                Respuestas_Evaluacion::create([
                    'ID_evaluacion' => $evaluacion->ID_evaluacion,
                    'ID_pregunta'   => $resp['ID_pregunta'],
                    'Respuesta'     => $resp['Respuesta'],
                    'Puntaje'       => $resp['Puntaje']
                ]);
            }

            DB::commit();

            // 6. Obtener recomendaciones basadas en el riesgo detectado
            $alimentos = Alimentos::where('Clasificacion', $nivelRiesgo)->get();
            $actividades = Actividades::where('Clasificacion', $nivelRiesgo)->get();

            return response()->json([
                'message' => 'Evaluación y respuestas guardadas con éxito',
                'resumen' => [
                    'id_evaluacion' => $evaluacion->ID_evaluacion, // Lo agregamos para que la App sepa cuál consultar
                    'puntaje' => $puntajeTotal,
                    'riesgo' => $nivelRiesgo,
                    'diagnostico' => $diagnostico ? $diagnostico->Nombre : 'No asignado',
                    'sugerencia' => $diagnostico ? $diagnostico->Sugerencia : 'Sigue con tus hábitos saludables'
                ],
                'recomendaciones' => [
                    'alimentos' => $alimentos,
                    'actividades' => $actividades
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error al procesar la evaluación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // NUEVO MÉTODO: REPORTE COMPLETO PARA LA APP
    public function obtenerReporteCompleto($id)
    {
        try {
            // Buscamos la evaluación con su diagnóstico vinculado
            $evaluacion = Evaluacion::with(['diagnostico'])->findOrFail($id);
            $riesgo = $evaluacion->Nivel_Riesgo;

            // Obtenemos solo los nombres de alimentos y actividades
            $alimentos = Alimentos::where('Clasificacion', $riesgo)->pluck('Nombre')->toArray();
            $actividades = Actividades::where('Clasificacion', $riesgo)->pluck('Nombre')->toArray();

            return response()->json([
                'id_evaluacion' => $evaluacion->ID_evaluacion,
                'puntaje'       => $evaluacion->Puntaje_Total,
                'nivel'         => $evaluacion->Nivel_Riesgo,
                'fecha'         => $evaluacion->Fecha,
                'diagnostico'   => $evaluacion->diagnostico ? $evaluacion->diagnostico->Nombre : 'No disponible',
                'sugerencia'    => $evaluacion->diagnostico ? $evaluacion->diagnostico->Sugerencia : 'Consulte a su médico.',
                'recomendaciones_texto' => [
                    'alimentos'   => implode(', ', $alimentos),
                    'actividades' => implode(', ', $actividades)
                ],
                'recomendaciones_lista' => [
                    'alimentos'   => $alimentos,
                    'actividades' => $actividades
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Reporte no encontrado', 'error' => $e->getMessage()], 404);
        }
    }

    // MOSTRAR UNA
    public function show($id)
    {
        return response()->json(
            Evaluacion::with(['usuario', 'diagnostico'])->findOrFail($id)
        );
    }

    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->update($request->all());

        return response()->json([
            'message' => 'Evaluación actualizada',
            'data' => $evaluacion
        ]);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return response()->json([ 'message' => 'Evaluación eliminada' ]);
    }
}