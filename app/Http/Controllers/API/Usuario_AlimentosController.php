public function index()
{
    try {
        // Intentamos traerlo con las relaciones corregidas
        $datos = \App\Models\Usuario_Alimentos::with(['usuario', 'alimento'])->get();
        return response()->json($datos, 200);

    } catch (\Exception $e) {
        // Si algo falla, te devolverá un JSON con el mensaje real del error
        return response()->json([
            'error_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}