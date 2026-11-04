<?php

namespace App\Imports;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class MatriculaMasivaImport implements OnEachRow, WithChunkReading, WithHeadingRow
{
    private const MAX_ERRORES_MOSTRADOS = 25;

    /**
     * @var array<int, string>
     */
    private array $errores = [];

    /**
     * @var array<string, true>
     */
    private array $documentosProcesados = [];

    /**
     * @var array<string, true>
     */
    private array $correosProcesados = [];

    private int $cantidadMatriculas = 0;

    private int $usuariosNuevos = 0;

    private int $usuariosExistentes = 0;

    private int $cantidadOmitidos = 0;

    private int $cantidadErrores = 0;

    public function __construct(private readonly Curso $curso)
    {
    }

    public function onRow(Row $row): void
    {
        $fila = $row->toArray();
        if (count(array_filter($fila, fn ($valor): bool => $valor !== null && trim((string) $valor) !== '')) === 0) {
            return;
        }

        $datos = $this->normalizar($fila);
        $validador = Validator::make($datos, [
            'documento_tipo' => ['required', Rule::in(['dni', 'ce'])],
            'documento_numero' => ['required', 'string', 'max:20'],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:160'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'empresa' => ['nullable', 'string', 'max:160'],
        ], [
            'documento_tipo.in' => 'El tipo de documento debe ser DNI o CE.',
            'documento_numero.required' => 'Falta el DNI o número de documento.',
            'nombres.required' => 'Faltan los nombres.',
            'apellidos.required' => 'Faltan los apellidos.',
            'email.required' => 'Falta el correo electrónico.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
        ], [
            'documento_tipo' => 'tipo de documento',
            'documento_numero' => 'DNI o documento',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'email' => 'correo electrónico',
            'telefono' => 'teléfono',
            'empresa' => 'empresa',
        ]);

        if ($validador->fails()) {
            $this->registrarError($row->getIndex(), implode(' ', $validador->errors()->all()));

            return;
        }

        $documentoValido = $datos['documento_tipo'] === 'dni'
            ? preg_match('/^\d{8}$/', $datos['documento_numero']) === 1
            : preg_match('/^[A-Z0-9]{6,12}$/', $datos['documento_numero']) === 1;

        if (! $documentoValido) {
            $this->registrarError($row->getIndex(), 'El número no tiene un formato válido para el tipo de documento indicado.');

            return;
        }

        $claveDocumento = $datos['documento_tipo'].':'.$datos['documento_numero'];
        $claveCorreo = $datos['email'];

        if (isset($this->documentosProcesados[$claveDocumento]) || isset($this->correosProcesados[$claveCorreo])) {
            $this->registrarError($row->getIndex(), 'El documento o correo ya aparece en otra fila del archivo.');

            return;
        }

        $this->documentosProcesados[$claveDocumento] = true;
        $this->correosProcesados[$claveCorreo] = true;

        try {
            $resultado = DB::transaction(function () use ($datos): array {
                $usuarioPorDocumento = User::query()
                    ->where('documento_tipo', $datos['documento_tipo'])
                    ->where('documento_numero', $datos['documento_numero'])
                    ->first();
                $usuarioPorCorreo = User::query()->where('email', $datos['email'])->first();

                if ($usuarioPorDocumento && $usuarioPorCorreo && $usuarioPorDocumento->getKey() !== $usuarioPorCorreo->getKey()) {
                    return ['error' => 'El documento y el correo pertenecen a cuentas distintas.'];
                }

                $usuario = $usuarioPorDocumento ?? $usuarioPorCorreo;
                $usuarioNuevo = false;

                if ($usuario) {
                    if ($usuario->role !== 'alumno') {
                        return ['error' => 'El documento o correo pertenece a una cuenta que no es de alumno.'];
                    }

                    if ($usuario->documento_numero !== null && (
                        $usuario->documento_tipo !== $datos['documento_tipo']
                        || $usuario->documento_numero !== $datos['documento_numero']
                    )) {
                        return ['error' => 'El correo ya está asociado a otro documento.'];
                    }

                    if ($usuarioPorDocumento && mb_strtolower($usuario->email) !== $datos['email']) {
                        return ['error' => 'El documento ya está registrado con otro correo.'];
                    }

                    if (Matricula::query()
                        ->where('user_id', $usuario->id)
                        ->where('curso_id', $this->curso->id)
                        ->exists()) {
                        return ['error' => 'El alumno ya está matriculado en este curso.'];
                    }

                    $usuario->forceFill([
                        'documento_tipo' => $datos['documento_tipo'],
                        'documento_numero' => $datos['documento_numero'],
                        'telefono' => $usuario->telefono ?: $datos['telefono'],
                    ])->save();
                } else {
                    $usuario = User::query()->create([
                        'name' => trim($datos['nombres'].' '.$datos['apellidos']),
                        'email' => $datos['email'],
                        'password' => Str::random(40),
                        'role' => 'alumno',
                        'documento_tipo' => $datos['documento_tipo'],
                        'documento_numero' => $datos['documento_numero'],
                        'telefono' => $datos['telefono'],
                    ]);
                    $usuarioNuevo = true;
                }

                Matricula::query()->create([
                    'user_id' => $usuario->id,
                    'curso_id' => $this->curso->id,
                    'estado' => 'activa',
                    'empresa' => $datos['empresa'],
                ]);

                return ['usuario_nuevo' => $usuarioNuevo];
            });
        } catch (QueryException) {
            $this->registrarError($row->getIndex(), 'Los datos coinciden con un registro existente y no se pudo crear la matrícula.');

            return;
        }

        if (isset($resultado['error'])) {
            $this->registrarError($row->getIndex(), $resultado['error']);

            return;
        }

        $this->cantidadMatriculas++;
        if ($resultado['usuario_nuevo']) {
            $this->usuariosNuevos++;
        } else {
            $this->usuariosExistentes++;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * @return array{matriculas: int, usuarios_nuevos: int, usuarios_existentes: int, omitidos: int, cantidad_errores: int, errores: array<int, string>}
     */
    public function resumen(): array
    {
        return [
            'matriculas' => $this->cantidadMatriculas,
            'usuarios_nuevos' => $this->usuariosNuevos,
            'usuarios_existentes' => $this->usuariosExistentes,
            'omitidos' => $this->cantidadOmitidos,
            'cantidad_errores' => $this->cantidadErrores,
            'errores' => $this->errores,
        ];
    }

    /**
     * @param  array<string, mixed>  $fila
     * @return array<string, string|null>
     */
    private function normalizar(array $fila): array
    {
        $tipoDocumento = mb_strtolower(trim((string) ($fila['documento_tipo'] ?? '')));
        $tipoDocumento = $tipoDocumento !== '' ? $tipoDocumento : 'dni';
        $numeroDocumento = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) (
            $fila['dni'] ?? $fila['documento'] ?? $fila['documento_numero'] ?? ''
        )) ?? '');

        if ($tipoDocumento === 'dni' && preg_match('/^\d{1,8}$/', $numeroDocumento) === 1) {
            $numeroDocumento = str_pad($numeroDocumento, 8, '0', STR_PAD_LEFT);
        }

        return [
            'documento_tipo' => $tipoDocumento,
            'documento_numero' => $numeroDocumento,
            'nombres' => trim((string) ($fila['nombres'] ?? $fila['nombre'] ?? '')),
            'apellidos' => trim((string) ($fila['apellidos'] ?? $fila['apellido'] ?? '')),
            'email' => mb_strtolower(trim((string) ($fila['email'] ?? $fila['correo'] ?? ''))),
            'telefono' => trim((string) ($fila['telefono'] ?? $fila['celular'] ?? '')) ?: null,
            'empresa' => trim((string) ($fila['empresa'] ?? '')) ?: null,
        ];
    }

    private function registrarError(int $fila, string $mensaje): void
    {
        $this->cantidadErrores++;
        $this->cantidadOmitidos++;

        if (count($this->errores) < self::MAX_ERRORES_MOSTRADOS) {
            $this->errores[] = "Fila {$fila}: {$mensaje}";
        }
    }
}
