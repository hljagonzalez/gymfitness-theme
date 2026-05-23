/**
 * Barra fija de confirmación tras enviar un comentario (visible sin hacer scroll).
 */
(function () {
    var aviso = document.getElementById('comentario-enviado');
    if (!aviso) {
        return;
    }

    function ajustarPosicion() {
        var cabecera = document.querySelector('.site-header');
        var margen = 12;
        var altura = cabecera ? cabecera.getBoundingClientRect().height : 72;

        document.documentElement.style.setProperty(
            '--gymfitness-header-offset',
            Math.ceil(altura + margen) + 'px'
        );
    }

    function mostrarAviso() {
        ajustarPosicion();
        aviso.focus({ preventScroll: true });
    }

    ajustarPosicion();
    window.addEventListener('resize', ajustarPosicion);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mostrarAviso);
    } else {
        mostrarAviso();
    }

    window.addEventListener('load', ajustarPosicion);
})();
