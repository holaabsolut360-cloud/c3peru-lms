<nav class="space-y-2 text-sm">
    @if (auth()->user()->esAdmin())
        <a class="block text-white" href="{{ route('admin.inicio') }}">Resumen</a>
        <a class="block text-white" href="{{ route('admin.cursos') }}">Cursos</a>
        <a class="block text-white" href="{{ route('admin.pagos') }}">Pagos</a>
        <a class="block text-white" href="{{ route('admin.certificados') }}">Certificados</a>
        <a class="block text-white" href="{{ route('admin.usuarios') }}">Usuarios</a>
        <a class="block text-white" href="{{ route('admin.reclamaciones') }}">Reclamaciones</a>
        <a class="block text-white" href="{{ route('admin.referencias') }}">Clientes y aliados</a>
        <a class="block text-white" href="{{ route('admin.apariencia') }}">Marca</a>
    @elseif (auth()->user()->esDocente())
        <a class="block text-white" href="{{ route('docente.inicio') }}">Mis cursos</a>
    @else
        <a class="block text-white" href="{{ route('aula.inicio') }}">Mis cursos</a>
        <a class="block text-white" href="{{ route('aula.pagos') }}">Mis pagos</a>
    @endif
    <a class="block text-white" href="{{ route('password.cambiar') }}">Contraseña</a>
    <a class="block text-white" href="{{ route('cursos.index') }}">Catálogo</a>
</nav>
