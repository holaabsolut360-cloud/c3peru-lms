<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_apariencia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->string('lema');
            $table->string('eslogan');
            $table->string('razon_social');
            $table->string('ruc', 20);
            $table->string('direccion');
            $table->string('telefono', 40);
            $table->string('whatsapp', 20);
            $table->string('correo');
            $table->string('logo');
            $table->string('favicon');
            $table->string('apple_touch');
            $table->string('color_primario', 7);
            $table->string('color_secundario', 7);
            $table->string('color_acento', 7);
            $table->string('color_fondo', 7);
            $table->string('color_fondo_alt', 7);
            $table->string('color_whatsapp', 7);
            $table->string('fuente_titulos', 40);
            $table->string('fuente_ui', 40);
            $table->string('fuente_texto', 40);
            $table->string('facebook');
            $table->string('instagram');
            $table->string('youtube');
            $table->string('tiktok');
            $table->string('linkedin');
            $table->string('texto_footer');
            $table->string('copyright');
            $table->text('meta_descripcion');
            $table->string('prefijo_certificado', 12);
            $table->string('gerente_general');
            $table->string('sitio');
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->foreignId('docente_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('nivel')->nullable();
            $table->string('duracion')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('fechas')->nullable();
            $table->string('certificacion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('moneda', 3)->default('PEN');
            $table->boolean('incluye_igv')->default(true);
            $table->text('descripcion')->nullable();
            $table->boolean('publicado')->default(true);
            $table->boolean('destacado')->default(false);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('contenido');
            $table->unsignedInteger('orden')->default(1);
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('moneda', 3)->default('PEN');
            $table->string('estado', 20)->default('pendiente')->index();
            $table->string('comprobante_path');
            $table->string('comprobante_nombre');
            $table->string('comprobante_mime', 120);
            $table->string('referencia')->nullable();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_en')->nullable();
            $table->text('motivo')->nullable();
            $table->timestamps();
        });

        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->nullOnDelete();
            $table->string('estado', 20)->default('activa');
            $table->timestamps();
            $table->unique(['user_id', 'curso_id']);
        });

        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->string('codigo')->unique();
            $table->string('documento_tipo', 20);
            $table->string('documento_numero', 32)->index();
            $table->string('nombre');
            $table->string('curso_titulo');
            $table->string('horas')->nullable();
            $table->timestamp('emitido_en');
            $table->string('estado', 20)->default('vigente')->index();
            $table->timestamps();
        });

        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 32);
            $table->string('celular', 30);
            $table->string('departamento', 40);
            $table->string('provincia', 80);
            $table->string('distrito', 80);
            $table->string('direccion');
            $table->string('referencia')->nullable();
            $table->string('correo');
            $table->boolean('es_menor')->default(false);
            $table->string('tutor_nombre')->nullable();
            $table->string('tutor_correo')->nullable();
            $table->string('tutor_tipo_documento', 20)->nullable();
            $table->string('tutor_numero_documento', 32)->nullable();
            $table->string('tipo', 20);
            $table->string('tipo_consumo', 20);
            $table->string('numero_pedido');
            $table->date('fecha_reclamo');
            $table->string('proveedor')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->text('descripcion_bien');
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_consumo')->nullable();
            $table->date('fecha_caducidad')->nullable();
            $table->text('detalle');
            $table->text('pedido_cliente');
            $table->boolean('acepta_privacidad');
            $table->string('estado', 20)->default('recibido');
            $table->timestamps();
        });

        Schema::create('paginas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titulo');
            $table->longText('cuerpo');
            $table->string('grupo', 30)->default('institucional');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('en_sitemap')->default(true);
            $table->timestamps();
        });

        Schema::create('mensajes_contacto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo');
            $table->string('telefono', 40)->nullable();
            $table->string('especialidad')->nullable();
            $table->string('modalidad')->nullable();
            $table->text('mensaje');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes_contacto');
        Schema::dropIfExists('paginas');
        Schema::dropIfExists('reclamaciones');
        Schema::dropIfExists('certificados');
        Schema::dropIfExists('matriculas');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('lecciones');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('configuracion_apariencia');
    }
};
