(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var header = document.querySelector('.site-header--inicio');

        if (!header) {
            return;
        }

        var barra = header.querySelector('.site-header__barra');
        var spacer = header.querySelector('.site-header__barra-spacer');

        if (!barra || !spacer) {
            return;
        }

        var umbral = 40;

        function sincronizarEspaciador() {
            if (header.classList.contains('site-header--nav-fijo')) {
                spacer.style.height = barra.offsetHeight + 'px';
            } else {
                spacer.style.height = '0';
            }
        }

        function actualizarNavFija() {
            var fija = window.scrollY > umbral;

            header.classList.toggle('site-header--nav-fijo', fija);
            sincronizarEspaciador();
        }

        barra.addEventListener('transitionend', function (evento) {
            if (
                evento.target === barra &&
                (evento.propertyName === 'padding' ||
                    evento.propertyName === 'padding-top' ||
                    evento.propertyName === 'padding-bottom')
            ) {
                sincronizarEspaciador();
            }
        });

        var logoImg = barra.querySelector('.logo img');
        if (logoImg) {
            logoImg.addEventListener('transitionend', function (evento) {
                if (evento.propertyName === 'max-width') {
                    sincronizarEspaciador();
                }
            });
        }

        actualizarNavFija();
        window.addEventListener('scroll', actualizarNavFija, { passive: true });
        window.addEventListener('resize', sincronizarEspaciador);
    });
})();
