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
