<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Leccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CursosSeeder extends Seeder
{
    public function run(): void
    {
        $definiciones = [
            'END / NDT' => ['slug' => 'end-ndt', 'orden' => 1, 'descripcion' => 'Ensayos no destructivos con esquemas SNT-TC-1A e ISO 9712.'],
            'Izaje' => ['slug' => 'izaje', 'orden' => 2, 'descripcion' => 'Inspección de aparejos y grúas.'],
            'Oil & Gas' => ['slug' => 'oil-gas', 'orden' => 3, 'descripcion' => 'Estándares API, ASME y preparación de exámenes.'],
            'Mantenimiento' => ['slug' => 'mantenimiento', 'orden' => 4, 'descripcion' => 'Termografía, vibraciones y confiabilidad.'],
        ];

        $categorias = [];
        foreach ($definiciones as $nombre => $datos) {
            $categorias[$nombre] = Categoria::query()->updateOrCreate(
                ['slug' => $datos['slug']],
                ['nombre' => $nombre, 'descripcion' => $datos['descripcion'], 'orden' => $datos['orden']],
            );
        }

        $destacados = [
            'Inspección Visual|NDT Level I & II',
            'Tintes Penetrantes|NDT Level I & II',
            'Ultrasonido|NDT Level I (incl. NDT Level II Ltd. A-Scan TM)',
            'Ultrasonido Phased Array|NDT',
            'Termografía Infrarroja|NDT Level I',
        ];

        $filas = json_decode(file_get_contents(database_path('seeders/data/cursos.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach ($filas as $indice => $fila) {
            $categoria = $categorias[$fila['categoria']] ?? null;
            if ($categoria === null) {
                throw new \RuntimeException('Categoría no mapeada: '.$fila['categoria']);
            }

            $nivel = $fila['nivel'] ?: null;
            $slug = Str::slug(trim($fila['titulo'].' '.($nivel ?? '')));
            $precio = $this->precio($fila['precio'] ?? null);
            $clave = $fila['titulo'].'|'.($fila['nivel'] ?? '');

            $curso = Curso::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'categoria_id' => $categoria->id,
                    'titulo' => $fila['titulo'],
                    'nivel' => $nivel,
                    'duracion' => $this->limpio($fila['duracion'] ?? null),
                    'modalidad' => $this->limpio($fila['modalidad'] ?? null),
                    'fechas' => $this->limpio($fila['fechas'] ?? null),
                    'certificacion' => $this->limpio($fila['certificacion'] ?? null),
                    'precio' => $precio,
                    'moneda' => 'PEN',
                    'incluye_igv' => (bool) ($fila['incluye_igv'] ?? true),
                    'descripcion' => 'Programa de '.$fila['categoria'].'. '.($this->limpio($fila['certificacion'] ?? null) ?: 'Certificación por confirmar.').' El temario detallado está pendiente de publicación.',
                    'portada' => match ($categoria->slug) {
                        'izaje' => '/marca/izajes.jpg',
                        'end-ndt' => '/marca/end.jpg',
                        'oil-gas' => '/marca/oilgas.jpg',
                        'mantenimiento' => '/marca/mantenimiento.jpg',
                        default => '/marca/hero.jpg',
                    },
                    'publicado' => true,
                    'destacado' => in_array($clave, $destacados, true),
                    'orden' => $indice + 1,
                ],
            );

            Leccion::query()->updateOrCreate(
                ['curso_id' => $curso->id, 'orden' => 1],
                [
                    'titulo' => 'Bienvenida',
                    'contenido' => "Bienvenida al curso «{$curso->titulo}».\n\nEl temario, los materiales y la fecha de la parte práctica se publicarán en esta aula. El contenido de las lecciones se muestra como texto: las etiquetas HTML no se interpretan.\n\nSi necesitas apoyo, escribe a ".config('marca.correo').'.',
                ],
            );
        }
    }

    private function precio(mixed $valor): ?string
    {
        if (! is_string($valor)) {
            return null;
        }
        $sinMiles = str_replace(',', '', $valor);
        if (! preg_match('/(\d+(?:\.\d+)?)/', $sinMiles, $coincidencia)) {
            return null;
        }

        return number_format((float) $coincidencia[1], 2, '.', '');
    }

    private function limpio(mixed $valor): ?string
    {
        if (! is_string($valor)) {
            return null;
        }
        $texto = trim($valor);

        return $texto === '' || $texto === '-' ? null : $texto;
    }
}
