<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HdaDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ruta del archivo SQL que creamos
        $path = database_path('sql/inserts_hda.sql');
        
        if (File::exists($path)) {
            // Ejecuta el SQL sin preparar de forma masiva
            DB::unprepared(File::get($path));
            $this->command->info('¡Datos de HDA insertados correctamente!');
        } else {
            $this->command->error('No se encontró el archivo SQL en: ' . $path);
        }
    }
}