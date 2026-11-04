import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginImagePreview);

const pintarBotonesModoOscuro = (modoOscuro) => {
    document.querySelectorAll('[data-dark-mode-toggle]').forEach((boton) => {
        const luna = boton.querySelector('[data-dark-mode-moon]');
        const sol = boton.querySelector('[data-dark-mode-sun]');
        const etiqueta = boton.querySelector('[data-dark-mode-label]');
        const accion = modoOscuro ? 'Activar modo claro' : 'Activar modo oscuro';

        boton.setAttribute('aria-pressed', String(modoOscuro));
        boton.setAttribute('aria-label', accion);
        if (etiqueta) {
            etiqueta.textContent = accion;
        }
        if (luna) {
            luna.hidden = modoOscuro;
        }
        if (sol) {
            sol.hidden = ! modoOscuro;
        }
    });
};

document.querySelectorAll('[data-dark-mode-toggle]').forEach((boton) => {
    boton.addEventListener('click', () => {
        const nuevoModoOscuro = ! document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', nuevoModoOscuro);
        pintarBotonesModoOscuro(nuevoModoOscuro);
        try {
            localStorage.setItem('c3-modo-oscuro', nuevoModoOscuro ? 'dark' : 'light');
        } catch (error) {
            // El modo visual permanece activo aunque el navegador bloquee el almacenamiento local.
        }
    });
});

pintarBotonesModoOscuro(document.documentElement.classList.contains('dark'));

let reproductorEnfoqueActivo = null;
let overflowCuerpoAnterior = '';

const cerrarModoEnfoque = () => {
    if (! reproductorEnfoqueActivo) {
        return;
    }

    const reproductor = reproductorEnfoqueActivo;
    const boton = reproductor.querySelector('[data-focus-toggle]');
    const etiqueta = reproductor.querySelector('[data-focus-label]');
    const icono = reproductor.querySelector('[data-focus-icon]');

    reproductor.classList.remove('focus-mode-active');
    reproductor.removeAttribute('role');
    reproductor.removeAttribute('aria-modal');
    boton?.setAttribute('aria-pressed', 'false');
    boton?.setAttribute('aria-label', 'Activar modo enfoque');
    if (etiqueta) {
        etiqueta.textContent = 'Modo enfoque';
    }
    if (icono) {
        icono.className = 'ti ti-maximize';
    }

    document.body.style.overflow = overflowCuerpoAnterior;
    reproductorEnfoqueActivo = null;
    boton?.focus();
};

document.querySelectorAll('[data-focus-player]').forEach((reproductor) => {
    const boton = reproductor.querySelector('[data-focus-toggle]');
    const etiqueta = reproductor.querySelector('[data-focus-label]');
    const icono = reproductor.querySelector('[data-focus-icon]');
    if (! boton || ! etiqueta || ! icono) {
        return;
    }

    boton.addEventListener('click', () => {
        if (reproductorEnfoqueActivo === reproductor) {
            cerrarModoEnfoque();
            return;
        }

        if (reproductorEnfoqueActivo) {
            cerrarModoEnfoque();
        }

        overflowCuerpoAnterior = document.body.style.overflow;
        reproductorEnfoqueActivo = reproductor;
        reproductor.classList.add('focus-mode-active');
        reproductor.setAttribute('role', 'dialog');
        reproductor.setAttribute('aria-modal', 'true');
        boton.setAttribute('aria-pressed', 'true');
        boton.setAttribute('aria-label', 'Salir del modo enfoque');
        etiqueta.textContent = 'Salir del modo enfoque';
        icono.className = 'ti ti-minimize';
        document.body.style.overflow = 'hidden';
        boton.focus();
    });
});

document.addEventListener('keydown', (evento) => {
    if (! reproductorEnfoqueActivo) {
        return;
    }

    if (evento.key === 'Escape') {
        cerrarModoEnfoque();
        return;
    }

    if (evento.key !== 'Tab') {
        return;
    }

    const elementosEnfocables = [...reproductorEnfoqueActivo.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    )].filter((elemento) => elemento.getAttribute('aria-hidden') !== 'true');
    const primero = elementosEnfocables[0];
    const ultimo = elementosEnfocables[elementosEnfocables.length - 1];

    if (! primero || ! ultimo) {
        evento.preventDefault();
        return;
    }

    if (evento.shiftKey && (document.activeElement === primero || ! reproductorEnfoqueActivo.contains(document.activeElement))) {
        evento.preventDefault();
        ultimo.focus();
    } else if (! evento.shiftKey && (document.activeElement === ultimo || ! reproductorEnfoqueActivo.contains(document.activeElement))) {
        evento.preventDefault();
        primero.focus();
    }
});

document.querySelectorAll('[data-notifications-dropdown]').forEach((dropdown) => {
    const boton = dropdown.querySelector('[data-notifications-toggle]');
    const menu = dropdown.querySelector('[data-notifications-menu]');
    if (! boton || ! menu) {
        return;
    }

    const cerrarMenu = (devolverFoco = false) => {
        menu.hidden = true;
        boton.setAttribute('aria-expanded', 'false');
        if (devolverFoco) {
            boton.focus();
        }
    };

    boton.addEventListener('click', () => {
        const seAbrira = menu.hidden;
        menu.hidden = ! seAbrira;
        boton.setAttribute('aria-expanded', String(seAbrira));
    });

    document.addEventListener('click', (evento) => {
        if (! dropdown.contains(evento.target)) {
            cerrarMenu();
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && ! menu.hidden) {
            cerrarMenu(true);
        }
    });
});

document.querySelectorAll('video[data-progreso-url]').forEach((video) => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (! csrf) {
        return;
    }

    const progresoInicial = Number(video.dataset.reanudarSegundos ?? 0);
    let ultimoGuardado = progresoInicial;
    let guardando = false;
    let progresoPendiente = null;

    video.addEventListener('loadedmetadata', () => {
        if (progresoInicial > 0 && Number.isFinite(video.duration) && progresoInicial < video.duration - 2) {
            video.currentTime = progresoInicial;
        }
    }, { once: true });

    const guardarProgreso = (forzar = false) => {
        const segundos = Math.floor(video.currentTime);
        if (segundos < 1 || (! forzar && Math.abs(segundos - ultimoGuardado) < 15)) {
            return;
        }

        if (guardando) {
            progresoPendiente = segundos;
            return;
        }

        guardando = true;
        fetch(video.dataset.progresoUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ segundos }),
        }).then((respuesta) => {
            if (respuesta.ok) {
                ultimoGuardado = segundos;
            }
        }).catch(() => {}).finally(() => {
            guardando = false;
            if (progresoPendiente !== null) {
                const pendiente = progresoPendiente;
                progresoPendiente = null;
                if (Math.abs(pendiente - ultimoGuardado) >= 15) {
                    guardarProgreso(true);
                }
            }
        });
    };

    video.addEventListener('timeupdate', () => guardarProgreso());
    video.addEventListener('pause', () => guardarProgreso(true));
    video.addEventListener('ended', () => guardarProgreso(true));
    document.addEventListener('visibilitychange', () => {
        if (document.hidden && ! video.paused) {
            guardarProgreso(true);
        }
    });
});

document.querySelectorAll('input[data-filepond]').forEach((entrada) => {
    if (FilePond.find(entrada)) {
        return;
    }

    FilePond.create(entrada, {
        allowMultiple: entrada.dataset.allowMultiple === 'true',
        acceptedFileTypes: entrada.dataset.acceptedFileTypes.split(',').map((tipo) => tipo.trim()),
        storeAsFile: true,
        labelIdle: 'Arrastra el archivo aquí o <span class="filepond--label-action">selecciona</span>',
        labelFileTypeNotAllowed: 'Tipo de archivo no permitido',
        fileValidateTypeLabelExpectedTypes: 'Se acepta: {allButLastType} o {lastType}',
    });
});

const reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.querySelectorAll('[data-contar]').forEach((elemento) => {
    const destino = Number(elemento.dataset.contar);
    if (! Number.isFinite(destino)) {
        return;
    }
    const pintar = (valor) => {
        elemento.textContent = new Intl.NumberFormat('es-PE').format(valor);
    };
    if (reducir) {
        pintar(destino);
        return;
    }
    const observar = new IntersectionObserver((entradas) => {
        entradas.forEach((entrada) => {
            if (! entrada.isIntersecting) {
                return;
            }
            observar.disconnect();
            const inicio = performance.now();
            const duracion = 900;
            const paso = (ahora) => {
                const avance = Math.min(1, (ahora - inicio) / duracion);
                pintar(Math.round(destino * avance));
                if (avance < 1) {
                    requestAnimationFrame(paso);
                }
            };
            requestAnimationFrame(paso);
        });
    }, { threshold: 0.6 });
    observar.observe(elemento);
});

const graficosAdmin = document.querySelectorAll('[data-admin-chart]');

document.querySelectorAll('input[type="file"][name="archivo"]').forEach((entrada) => {
    entrada.addEventListener('change', () => {
        const etiqueta = entrada.parentElement.querySelector('[data-archivo-seleccionado]');
        if (etiqueta) {
            etiqueta.textContent = entrada.files?.[0]?.name ?? 'Ningún archivo seleccionado.';
        }
    });
});

if (graficosAdmin.length > 0) {
    Promise.all([
        import('apexcharts/core'),
        import('apexcharts/line'),
        import('apexcharts/bar'),
    ]).then(([{ default: ApexCharts }]) => {
        graficosAdmin.forEach((elemento) => {
            const tipo = elemento.dataset.adminChart;
            const etiquetas = JSON.parse(elemento.dataset.labels ?? '[]');
            const valores = JSON.parse(elemento.dataset.values ?? '[]').map(Number);
            const esIngreso = tipo === 'ingresos';
            const esCurso = tipo === 'cursos';
            const color = esIngreso ? '#0f766e' : esCurso ? '#7c3aed' : '#0284c7';

            const grafico = new ApexCharts(elemento, {
                chart: {
                    type: esIngreso ? 'area' : 'bar',
                    height: 260,
                    toolbar: { show: false },
                    fontFamily: 'Poppins, sans-serif',
                    foreColor: '#64748b',
                    animations: { enabled: true, speed: 450 },
                },
                series: [{ name: esIngreso ? 'Ingresos' : esCurso ? 'Matrículas' : 'Inscripciones', data: valores }],
                colors: [color],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: esIngreso ? 3 : 0 },
                fill: esIngreso
                    ? { type: 'gradient', gradient: { opacityFrom: 0.32, opacityTo: 0.03, stops: [0, 90, 100] } }
                    : { opacity: 0.92 },
                markers: { size: 4, strokeWidth: 0, hover: { size: 6 } },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4, padding: { left: 8, right: 8 } },
                plotOptions: { bar: { horizontal: esCurso, borderRadius: 5, columnWidth: '45%', barHeight: '55%' } },
                xaxis: {
                    categories: etiquetas,
                    labels: { trim: true, style: { fontSize: '11px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (valor) => esIngreso
                            ? `S/ ${new Intl.NumberFormat('es-PE', { maximumFractionDigits: 0 }).format(valor)}`
                            : new Intl.NumberFormat('es-PE', { maximumFractionDigits: 0 }).format(valor),
                        style: { fontSize: '11px' },
                    },
                },
                tooltip: {
                    y: {
                        formatter: (valor) => esIngreso
                            ? `S/ ${new Intl.NumberFormat('es-PE', { minimumFractionDigits: 2 }).format(valor)}`
                            : `${new Intl.NumberFormat('es-PE').format(valor)} ${esCurso ? 'matrículas' : 'inscripciones'}`,
                    },
                },
                noData: { text: 'Todavía no hay datos para mostrar' },
            });

            grafico.render();
        });
    });
}
