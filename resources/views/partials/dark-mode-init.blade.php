<script>
    (() => {
        try {
            const preferencia = localStorage.getItem('c3-modo-oscuro');
            const modoOscuro = preferencia === null
                ? window.matchMedia('(prefers-color-scheme: dark)').matches
                : preferencia === 'dark';

            document.documentElement.classList.toggle('dark', modoOscuro);
        } catch (error) {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>
