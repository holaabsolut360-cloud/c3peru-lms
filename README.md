# C3 Perú — plataforma de cursos

Aula virtual de **CAPACITACION-CERTIFICACION-CALIDAD PERU S.A.C.** (C3 Perú, [c3peru.com](https://c3peru.com)). La venta es por curso, con comprobante y aprobación manual. No hay membresías, planes ni segmentos de colegios o preuniversitario.

## Origen de este código

La intención era copiar la rama `perucapacitate-produccion` del repositorio privado `holaabsolut360-cloud/lms-eduplatform`, sin historial, sin `.env` y sin datos de esa operación. El token de GitHub de este agente solo tiene acceso a `c3peru-lms` (la API responde 404 para `lms-eduplatform`). Ese repositorio no se leyó ni se modificó.

Esta base es una aplicación **Laravel 13** nueva que implementa las decisiones de marca, catálogo, legales, pagos, certificados y seguridad pedidas para C3. No arrastra seeders, comandos ni textos de perucapacitate, Ceprepol, Cursotube ni Absolut360.

## Requisitos

- PHP 8.3 o superior, con `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `gd`, `fileinfo` y `curl`
- Composer 2
- Node.js 20 o superior y npm (solo para compilar los estilos; el resultado queda en `public/build`)
- MySQL 8 o MariaDB 10.6 en producción. Las pruebas usan SQLite en memoria

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edita `.env`:

- `APP_ENV=production` y `APP_DEBUG=false` en el servidor
- `APP_URL` con la URL pública, porque el sitemap y los enlaces de los correos salen de ahí
- `APP_TIMEZONE=America/Lima`
- `DB_*` de MySQL
- `ADMIN_EMAIL` y `ADMIN_PASSWORD` (mínimo 12 caracteres). No los subas al repositorio
- Correo SMTP real si quieres que la recuperación de contraseña salga de `ana.castillo@c3peru.com`

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
npm install
npm run build
```

`db:seed` carga la marca, 4 categorías, 34 cursos, las páginas institucionales y los textos legales. Si `ADMIN_EMAIL` o `ADMIN_PASSWORD` están vacíos, no crea administrador y lo avisa en consola.

El logo provisional (745×306) y los favicon están en `public/marca/`.

## Cola, caché y cron

Por defecto la caché es `file` y la cola es `sync`. No hace falta un worker ni tablas de jobs para operar. Las migraciones de caché, jobs y sesiones sí existen, por si más adelante cambias el driver.

En cPanel, el cron del usuario puede ser cada minuto:

```bash
* * * * * /usr/local/bin/php /home/USUARIO/c3peru-lms/artisan schedule:run >> /dev/null 2>&1
```

Ajusta la ruta de PHP y del proyecto. El scheduler ejecuta `auth:clear-resets` una vez al día para borrar tokens de contraseña vencidos.

El document root del dominio debe ser la carpeta `public/`. Permisos de escritura en `storage/` y `bootstrap/cache/`.

## Cómo se usa

1. El alumno se registra con DNI o carné de extranjería.
2. Si el curso tiene precio, envía el comprobante (JPG, PNG, WEBP o PDF, hasta 5 MB). El archivo se guarda en `storage/app/comprobantes`, fuera del disco público.
3. Un administrador entra a **Pagos** y aprueba. Esa acción está protegida por el Gate `aprobar-pagos`. Al aprobar se crea la matrícula y el alumno ve el aula.
4. Los cursos sin precio muestran **Consultar por WhatsApp** y no aceptan compra en línea.
5. El administrador emite el certificado desde la matrícula. El código usa el prefijo guardado en la marca (`C3-AÑO-XXXXXX`). El PDF lleva logo, colores y datos de `configuracion_apariencia`.
6. La verificación pública es un formulario POST. Acepta el código o el DNI/CE y responde solo con nombre enmascarado, curso, fecha y estado. No consulta el libro de reclamaciones. Hay límite de intentos por IP.
7. Alumnos y docentes tienen 3 intentos de ingreso. Al cuarto, el formulario pide recuperar o cambiar la contraseña. El mensaje es el mismo si el correo no existe.

La marca (razón social, RUC, dirección, logo, favicon, colores, fuentes, redes, contacto, footer y prefijo de certificado) se edita en **Administración → Marca**. Los colores de fábrica están solo en `config/marca.php`; el sitio, los correos de restablecimiento y el PDF leen la fila de `configuracion_apariencia`. El verde de WhatsApp se usa únicamente en el botón flotante.

## Pruebas

```bash
php artisan test
```

Cubren la compra con comprobante, el rechazo de archivos que no son imagen o PDF, la aprobación solo por administrador, la matrícula, el escape del HTML de las lecciones, el tope de 3 intentos de alumnos y docentes, la recuperación de contraseña sin revelar si el correo existe, la verificación por código y por DNI, el límite de intentos de verificación, el aislamiento del libro de reclamaciones, el catálogo de 34 cursos, `robots.txt`, el sitemap con las páginas legales, el favicon y la meta descripción.

## Compilar el CSS

La cara pública usa Vite y Tailwind CSS v4 (`resources/css/app.css` y `resources/js/app.js`). Las fuentes Montserrat, Poppins y Roboto llegan con Fontsource. Los iconos son `@tabler/icons-webfont`.

```bash
npm install
npm run build
```

`public/build` se versiona para poder subir el proyecto a un hosting sin Node. Si cambias vistas o estilos, vuelve a compilar y sube esa carpeta.

## Pendientes de contenido

- Fotos y alta de los instructores como usuarios docentes. Las biografías publicadas en c3peru.com ya están en la página Equipo; no hay foto confirmada.
- Acreditación: el sitio habla de NTP ISO/IEC 17024, SNT-TC-1A, ISO 9712 e ISO 18436. Falta el documento de la entidad acreditadora. La home lo deja señalado.
- Precios de 24 cursos (izaje, oil & gas, parte de mantenimiento y radiografía). Hoy se consultan por WhatsApp.
- Temarios, imágenes propias de cada curso y páginas que en el sitio actual estaban vacías. Clientes no inventa logos.
- Copyright. El sitio público mezclaba el aviso de C3 con una línea de un tercero. Aquí solo queda el de CAPACITACION-CERTIFICACION-CALIDAD PERU S.A.C. Conviene que el estudio jurídico lo confirme.
- Logo en alta resolución. El archivo actual mide 745×306 y es provisional.
- Política de reembolso: el texto de c3peru.com ofrece una sola salida (trasladar la matrícula) cuando C3 cancela el curso. Si existe una segunda opción, hay que escribirla.
- Catálogo INEI de provincias y distritos. El libro de reclamaciones pide departamento en una lista y provincia/distrito escritos, para no publicar un padrón incompleto.
- Blog sin artículos.
