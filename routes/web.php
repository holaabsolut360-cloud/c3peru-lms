<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AparienciaController;
use App\Http\Controllers\Admin\CalificacionController;
use App\Http\Controllers\Admin\CertificadoController as AdminCertificadoController;
use App\Http\Controllers\Admin\ComunidadAdminController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\ExamenController;
use App\Http\Controllers\Admin\MatriculaMasivaController;
use App\Http\Controllers\Admin\PagoController as AdminPagoController;
use App\Http\Controllers\Admin\PanelController as AdminPanelController;
use App\Http\Controllers\Admin\ReclamacionAdminController;
use App\Http\Controllers\Admin\ReferenciaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\Auth\ContrasenaController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Auth\SesionController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CertificadoPdfController;
use App\Http\Controllers\CertificadoVerificacionController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CursoPublicoController;
use App\Http\Controllers\Docente\AlumnoController as DocenteAlumnoController;
use App\Http\Controllers\Docente\AnuncioController as DocenteAnuncioController;
use App\Http\Controllers\Docente\ClaseEnVivoController;
use App\Http\Controllers\Docente\ContenidoController as DocenteContenidoController;
use App\Http\Controllers\Docente\PanelController as DocentePanelController;
use App\Http\Controllers\Docente\TareaController as DocenteTareaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ReclamacionController;
use App\Http\Controllers\RecursosController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\TareaEntregaController;
use App\Http\Controllers\VideoStreamController;
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
Route::get('/badges/issuer', [BadgeController::class, 'emisor'])->name('badges.issuer');
Route::get('/badges/{assertionId}', [BadgeController::class, 'verificacion'])->name('badges.ver');
Route::get('/badges/classes/{insignia}', [BadgeController::class, 'clase'])->name('badges.class');

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
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('/notificaciones/{notificationId}/abrir', [NotificacionController::class, 'abrir'])->name('notificaciones.abrir');
    Route::post('/notificaciones/{notificationId}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::post('/notificaciones/leidas', [NotificacionController::class, 'marcarTodas'])->name('notificaciones.todas');
    Route::get('/badges/{insigniaUsuario}/linkedin', [BadgeController::class, 'compartirLinkedin'])->name('badges.linkedin');
    Route::get('/cuenta/contrasena', [ContrasenaController::class, 'cambiarFormulario'])->name('password.cambiar');
    Route::put('/cuenta/contrasena', [ContrasenaController::class, 'cambiar'])->name('password.cambiar.guardar');

    Route::get('/aula', [AulaController::class, 'inicio'])->name('aula.inicio');
    Route::get('/aula/cursos/{curso}', [AulaController::class, 'curso'])->name('aula.curso');
    Route::get('/aula/cursos/{curso}/recursos', [RecursosController::class, 'index'])->name('aula.recursos.index');
    Route::get('/aula/cursos/{curso}/recursos/zip', [RecursosController::class, 'descargarZip'])->name('aula.recursos.zip');
    Route::post('/aula/cursos/{curso}/lecciones/{leccion}/progreso-video', [AulaController::class, 'guardarProgresoVideo'])->middleware('throttle:60,1')->name('aula.lecciones.progreso-video');
    Route::get('/aula/clases/{clase}/calendario.ics', [CalendarioController::class, 'descargar'])->name('calendario.descargar');
    Route::get('/aula/materiales/{material}/descargar', [AulaController::class, 'descargarMaterial'])->name('aula.materiales.descargar');
    Route::post('/aula/cursos/{curso}/lecciones/{leccion}/completar', [AulaController::class, 'completarLeccion'])->name('aula.lecciones.completar');
    Route::get('/aula/examenes/{examen}', [AulaController::class, 'examen'])->name('aula.examenes.realizar');
    Route::get('/aula/videos/{video}/stream', VideoStreamController::class)->name('aula.videos.stream');
    Route::get('/aula/pagos', [AulaController::class, 'pagos'])->name('aula.pagos');
    Route::get('/cursos/{curso}/inscripcion', [CompraController::class, 'crear'])->name('compra.crear');
    Route::post('/cursos/{curso}/inscripcion', [CompraController::class, 'guardar'])->name('compra.guardar');
    Route::get('/comprobantes/{pago}', [CompraController::class, 'descargar'])->name('comprobantes.descargar');
    Route::get('/certificados/{certificado}/pdf', CertificadoPdfController::class)->name('certificados.pdf');
    Route::post('/tareas/{tarea}/entregas', [TareaEntregaController::class, 'store'])->name('tareas.entregas.guardar');
    Route::get('/tareas/entregas/{entrega}/descargar', [TareaEntregaController::class, 'descargar'])->name('tareas.entregas.descargar');
});

Route::middleware(['auth', 'rol:docente'])->prefix('docente')->name('docente.')->group(function () {
    Route::get('/', DocentePanelController::class)->name('inicio');
    Route::get('/cursos/{curso}/alumnos', [DocenteAlumnoController::class, 'index'])->name('alumnos.index');
    Route::post('/cursos/{curso}/alumnos/{alumno}/recordatorio', [DocenteAlumnoController::class, 'enviarRecordatorio'])->middleware('throttle:6,1')->name('alumnos.recordatorio');
    Route::get('/cursos/{curso}/tareas', [DocenteTareaController::class, 'index'])->name('tareas.index');
    Route::post('/cursos/{curso}/tareas', [DocenteTareaController::class, 'store'])->name('tareas.guardar');
    Route::get('/cursos/{curso}/tareas/{tarea}/entregas', [DocenteTareaController::class, 'entregas'])->name('tareas.entregas');
    Route::get('/tareas/entregas/{entrega}/calificar', [DocenteTareaController::class, 'calificar'])->name('tareas.calificar');
    Route::post('/tareas/entregas/{entrega}/calificar', [DocenteTareaController::class, 'guardarCalificacion'])->name('tareas.calificar.guardar');
    Route::get('/cursos/{curso}/anuncios', [DocenteAnuncioController::class, 'index'])->name('anuncios.index');
    Route::get('/cursos/{curso}/anuncios/nuevo', [DocenteAnuncioController::class, 'create'])->name('anuncios.crear');
    Route::post('/cursos/{curso}/anuncios', [DocenteAnuncioController::class, 'store'])->name('anuncios.guardar');
    Route::get('/cursos/{curso}/contenido', [DocenteContenidoController::class, 'index'])->name('contenido.index');
    Route::post('/cursos/{curso}/contenido', [DocenteContenidoController::class, 'store'])->name('contenido.guardar');
    Route::delete('/cursos/{curso}/contenido/videos/{video}', [DocenteContenidoController::class, 'destroy'])->name('contenido.videos.eliminar');
    Route::delete('/cursos/{curso}/contenido/materiales/{material}', [DocenteContenidoController::class, 'destroyMaterial'])->name('contenido.materiales.eliminar');
    Route::get('/cursos/{curso}/clases/nueva', [ClaseEnVivoController::class, 'crear'])->name('clases.crear');
    Route::post('/cursos/{curso}/clases', [ClaseEnVivoController::class, 'guardar'])->name('clases.guardar');
});

Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminPanelController::class)->name('inicio');
    Route::get('/cursos', [AdminCursoController::class, 'index'])->name('cursos');
    Route::get('/cursos/nuevo', [AdminCursoController::class, 'crear'])->name('cursos.nuevo');
    Route::post('/cursos', [AdminCursoController::class, 'guardar'])->name('cursos.guardar');
    Route::get('/cursos/{curso}/matriculas/masiva/plantilla', [MatriculaMasivaController::class, 'descargarPlantilla'])->name('cursos.matriculas.masiva.plantilla');
    Route::get('/cursos/{curso}/matriculas/masiva', [MatriculaMasivaController::class, 'create'])->name('cursos.matriculas.masiva');
    Route::post('/cursos/{curso}/matriculas/masiva', [MatriculaMasivaController::class, 'store'])->name('cursos.matriculas.masiva.guardar');
    Route::get('/cursos/{curso}/editar', [AdminCursoController::class, 'editar'])->name('cursos.editar');
    Route::put('/cursos/{curso}', [AdminCursoController::class, 'actualizar'])->name('cursos.actualizar');
    Route::delete('/cursos/{curso}', [AdminCursoController::class, 'eliminar'])->name('cursos.eliminar');
    Route::get('/cursos/{curso}/videos', [AdminVideoController::class, 'index'])->name('cursos.videos');
    Route::post('/cursos/{curso}/videos', [AdminVideoController::class, 'store'])->name('cursos.videos.guardar');
    Route::delete('/cursos/{curso}/videos/{video}', [AdminVideoController::class, 'destroy'])->name('cursos.videos.eliminar');
    Route::get('/cursos/{curso}/examenes', [ExamenController::class, 'index'])->name('cursos.examenes');
    Route::post('/cursos/{curso}/examenes', [ExamenController::class, 'store'])->name('cursos.examenes.guardar');
    Route::get('/cursos/{curso}/calificaciones.xlsx', [CalificacionController::class, 'exportar'])->name('calificaciones.exportar');
    Route::get('/cursos/{curso}/examenes', [ExamenController::class, 'index'])->name('cursos.examenes');
    Route::post('/cursos/{curso}/examenes', [ExamenController::class, 'store'])->name('cursos.examenes.guardar');
    Route::get('/cursos/{curso}/calificaciones.xlsx', [CalificacionController::class, 'exportar'])->name('calificaciones.exportar');
    Route::get('/cursos/{curso}/examenes', [ExamenController::class, 'index'])->name('cursos.examenes');
    Route::post('/cursos/{curso}/examenes', [ExamenController::class, 'store'])->name('cursos.examenes.guardar');
    Route::get('/cursos/{curso}/calificaciones.xlsx', [CalificacionController::class, 'exportar'])->name('calificaciones.exportar');
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
    Route::get('/actividad', [ActivityLogController::class, 'index'])->name('actividad');
    Route::get('/reclamaciones', [ReclamacionAdminController::class, 'index'])->name('reclamaciones');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::post('/usuarios/docentes', [UsuarioController::class, 'crearDocente'])->name('usuarios.docentes');
    Route::put('/usuarios/{usuario}/documento', [UsuarioController::class, 'actualizarDocumento'])->name('usuarios.documento');
});
