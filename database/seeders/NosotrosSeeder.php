<?php

namespace Database\Seeders;

use App\Models\CifraComunidad;
use App\Models\Compromiso;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class NosotrosSeeder extends Seeder
{
    public function run(): void
    {
        $equipo = [
            [
                'nombre' => 'Ana Castillo',
                'cargo' => 'Directora de Certificaciones',
                'iniciales' => 'AC',
                'linkedin' => 'https://www.linkedin.com/in/anamariacastillovasquez',
                'bio' => 'Ing. Industrial, MBA, con más de 15 años de experiencia, especializada en gerencia de proyectos, gestión de calidad, estrategias comerciales y marketing digital. Experiencia liderando la capacitación de equipos e implementación de estándares de calidad e ISO.',
                'credenciales' => [],
                'orden' => 1,
            ],
            [
                'nombre' => 'Luis Chirinos',
                'cargo' => 'Líder especialidad NDT',
                'iniciales' => 'LC',
                'linkedin' => 'https://www.linkedin.com/in/luis-chirinos-m-a42a58104',
                'bio' => 'Ing. Mecánico Electricista, MBA, con 20 años de experiencia en proyectos electromecánicos, montaje de equipos estáticos y desarrollo de planes de inspección. Inspector Certificado ASNT NDT Level III #207051 VT-PT-MT-UT, Inspector API 570 #69101, API 577 #105556, especialista en equipos estáticos.',
                'credenciales' => ['ASNT NDT Level III', 'API 570', 'API 577'],
                'orden' => 2,
            ],
            [
                'nombre' => 'Ysabel Torres',
                'cargo' => 'Líder izajes y técnicas de mantenimiento',
                'iniciales' => 'YT',
                'linkedin' => 'https://www.linkedin.com/in/ysabel-torres-guerrero-08867b16',
                'bio' => 'Ing. Mecánica Electricista, MBA, 20 años de experiencia, ASNT NDT Level III 303528 MT-IRT; certificación CIA como Inspectors & Trainer de Operadores de Equipos de Izaje y Equipos; Auditora Certificada ISO 17020 y 17024, especialista Equipos Dinámicos e Izajes.',
                'credenciales' => ['ASNT NDT Level III', 'ISO 17020', 'ISO 17024'],
                'orden' => 3,
            ],
        ];

        foreach ($equipo as $persona) {
            Instructor::query()->updateOrCreate(
                ['nombre' => $persona['nombre']],
                [...$persona, 'publicado' => true, 'foto' => null],
            );
        }

        foreach ([
            ['Alumnos certificados', 1],
            ['Cursos', 2],
            ['Horas de formación', 3],
            ['Empresas atendidas', 4],
        ] as [$etiqueta, $orden]) {
            CifraComunidad::query()->updateOrCreate(
                ['etiqueta' => $etiqueta],
                ['valor' => 'POR CONFIRMAR', 'orden' => $orden, 'publicado' => true],
            );
        }

        Compromiso::query()->updateOrCreate(
            ['titulo' => 'Nuestro compromiso'],
            [
                'texto' => 'Formamos haciendo, con expertos. Acompañamos la certificación de personas en izaje de cargas, ensayos no destructivos, oil & gas y mantenimiento, con la mirada puesta en la NTP ISO/IEC 17024, la seguridad y la calidad. El enfoque es personalizado: el programa se ajusta a los equipos y procedimientos de tu operación.',
                'propuesto' => true,
                'orden' => 1,
                'publicado' => true,
            ],
        );
    }
}
