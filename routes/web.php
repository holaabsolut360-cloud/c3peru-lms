<?php

use App\Http\Controllers\Admin\AparienciaController;
use App\Http\Controllers\Admin\CertificadoController as AdminCertificadoController;
use App\Http\Controllers\Admin\ComunidadAdminController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\PagoController as AdminPagoController;
use App\Http\Controllers\Admin\PanelController as AdminPanelController;
use App\Http\Controllers\Admin\ReclamacionAdminController;
use App\Http\Controllers\Admin\ReferenciaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\Auth\ContrasenaController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Auth\SesionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CertificadoPdfController;
use App\Http\Controllers\CertificadoVerificacionController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CursoPublicoController;
use App\Http\Controllers\Docente\PanelController as DocentePanelController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ReclamacionController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', InicioController::class)->name('inicio');
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');
Route::get('/equipo', [NosotrosController::class, 'equipo'])->name('equipo');
Route::get('/cursos', [CursoPublicoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{curso}', [CursoPublicoController::class, 'ver'])->name('cursos.ver');
Route::get('/paginas/{pagina}', [PaginaController::class, 'ver'])->name('paginas.ver');
Route::get('/contacto', [ContactoController::class, 'crear'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'guardar'])->name('contacto.guardar');
Route::get('/blog', BlogController::class)->name('blog');
Route::get('/libro-de-reclamaciones', [ReclamacionController::class, 'crear'])->name('reclamaciones.crear');
Route::post('/libro-de-reclamaciones', [ReclamacionController::class, 'guardar'])->name('reclamaciones.guardar');

Route::get('/verificar-certificado', [CertificadoVerificacionController::class, 'formulario'])->name('certificados.formulario');
Route::post('/verificar-certificado', [CertificadoVerificacionController::class, 'consultar'])
    ->middleware('throttle:verificacion-certificado')
    ->name('certificados.verificar');

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [SesionController::class, 'crear'])->name('login');
    Route::post('/ingresar', [SesionController::class, 'guardar'])->name('login.guardar');
    Route::get('/registro', [RegistroController::class, 'crear'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'guardar'])->name('registro.guardar');
    Route::get('/recuperar-contrasena', [ContrasenaController::class, 'solicitar'])->name('password.request');
    Route::post('/recuperar-contrasena', [ContrasenaController::class, 'enviar'])->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [ContrasenaController::class, 'editar'])->name('password.reset');
    Route::post('/restablecer-contrasena', [ContrasenaController::class, 'actualizar'])->name('password.update');
});

Route::post('/salir', [SesionController::class, 'destruir'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/cuenta/contrasena', [ContrasenaController::class, 'cambiarFormulario'])->name('password.cambiar');
    Route::put('/cuenta/contrasena', [ContrasenaController::class, 'cambiar'])->name('password.cambiar.guardar');

    Route::get('/aula', [AulaController::class, 'inicio'])->name('aula.inicio');
    Route::get('/aula/cursos/{curso}', [AulaController::class, 'curso'])->name('aula.curso');
    Route::get('/aula/pagos', [AulaController::class, 'pagos'])->name('aula.pagos');
    Route::get('/cursos/{curso}/inscripcion', [CompraController::class, 'crear'])->name('compra.crear');
    Route::post('/cursos/{curso}/inscripcion', [CompraController::class, 'guardar'])->name('compra.guardar');
    Route::get('/comprobantes/{pago}', [CompraController::class, 'descargar'])->name('comprobantes.descargar');
    Route::get('/certificados/{certificado}/pdf', CertificadoPdfController::class)->name('certificados.pdf');
});

Route::middleware(['auth', 'rol:docente'])->prefix('docente')->name('docente.')->group(function () {
    Route::get('/', DocentePanelController::class)->name('inicio');
});

Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminPanelController::class)->name('inicio');
    Route::get('/cursos', [AdminCursoController::class, 'index'])->name('cursos');
    Route::get('/cursos/nuevo', [AdminCursoController::class, 'crear'])->name('cursos.nuevo');
    Route::post('/cursos', [AdminCursoController::class, 'guardar'])->name('cursos.guardar');
    Route::get('/cursos/{curso}/editar', [AdminCursoController::class, 'editar'])->name('cursos.editar');
    Route::put('/cursos/{curso}', [AdminCursoController::class, 'actualizar'])->name('cursos.actualizar');
    Route::delete('/cursos/{curso}', [AdminCursoController::class, 'eliminar'])->name('cursos.eliminar');
    Route::get('/comunidad', [ComunidadAdminController::class, 'index'])->name('comunidad');
    Route::post('/comunidad/cifras', [ComunidadAdminController::class, 'guardarCifra'])->name('comunidad.cifras');
    Route::put('/comunidad/cifras/{cifra}', [ComunidadAdminController::class, 'actualizarCifra'])->name('comunidad.cifras.actualizar');
    Route::post('/comunidad/compromisos', [ComunidadAdminController::class, 'guardarCompromiso'])->name('comunidad.compromisos');
    Route::put('/comunidad/compromisos/{compromiso}', [ComunidadAdminController::class, 'actualizarCompromiso'])->name('comunidad.compromisos.actualizar');
    Route::put('/comunidad/instructores/{instructor}', [ComunidadAdminController::class, 'actualizarInstructor'])->name('comunidad.instructores.actualizar');
    Route::get('/referencias', [ReferenciaController::class, 'index'])->name('referencias');
    Route::post('/referencias', [ReferenciaController::class, 'guardar'])->name('referencias.guardar');
    Route::put('/referencias/{referencia}', [ReferenciaController::class, 'actualizar'])->name('referencias.actualizar');
    Route::delete('/referencias/{referencia}', [ReferenciaController::class, 'eliminar'])->name('referencias.eliminar');
    Route::get('/pagos', [AdminPagoController::class, 'index'])->name('pagos');
    Route::post('/pagos/{pago}/aprobar', [AdminPagoController::class, 'aprobar'])->name('pagos.aprobar');
    Route::post('/pagos/{pago}/rechazar', [AdminPagoController::class, 'rechazar'])->name('pagos.rechazar');
    Route::get('/certificados', [AdminCertificadoController::class, 'index'])->name('certificados');
    Route::post('/matriculas/{matricula}/certificado', [AdminCertificadoController::class, 'emitir'])->name('certificados.emitir');
    Route::post('/certificados/{certificado}/revocar', [AdminCertificadoController::class, 'revocar'])->name('certificados.revocar');
    Route::get('/apariencia', [AparienciaController::class, 'editar'])->name('apariencia');
    Route::put('/apariencia', [AparienciaController::class, 'actualizar'])->name('apariencia.actualizar');
    Route::get('/reclamaciones', [ReclamacionAdminController::class, 'index'])->name('reclamaciones');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::post('/usuarios/docentes', [UsuarioController::class, 'crearDocente'])->name('usuarios.docentes');
    Route::put('/usuarios/{usuario}/documento', [UsuarioController::class, 'actualizarDocumento'])->name('usuarios.documento');
});
