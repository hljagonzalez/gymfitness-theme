(function () {
    'use strict';

    var MQ_ESCRITORIO = window.matchMedia('(min-width: 62em)');

    document.addEventListener('DOMContentLoaded', function () {
        var barra = document.querySelector('.site-header [data-barra-navegacion]');

        if (!barra) {
            return;
        }

        var boton = barra.querySelector('.menu-hamburguesa');
        var panel = barra.querySelector('#menu-principal-panel');
        var overlay = barra.querySelector('.menu-principal-overlay');
        var lista = panel ? panel.querySelector('ul') : null;
        var logo = barra.querySelector('.logo');
        var enlaces = panel ? panel.querySelectorAll('a') : [];

        if (!boton || !panel) {
            return;
        }

        function cerrarMenu() {
            barra.classList.remove('barra-navegacion--menu-abierto');
            boton.setAttribute('aria-expanded', 'false');
            boton.setAttribute('aria-label', 'Abrir menú');
            document.body.classList.remove('menu-principal-abierto');

            if (overlay) {
                overlay.setAttribute('hidden', '');
                overlay.setAttribute('aria-hidden', 'true');
            }
        }

        function abrirMenu() {
            barra.classList.add('barra-navegacion--menu-abierto');
            boton.setAttribute('aria-expanded', 'true');
            boton.setAttribute('aria-label', 'Cerrar menú');
            document.body.classList.add('menu-principal-abierto');

            if (overlay) {
                overlay.removeAttribute('hidden');
                overlay.setAttribute('aria-hidden', 'false');
            }

            document.documentElement.style.setProperty(
                '--menu-panel-top',
                barra.getBoundingClientRect().height + 'px'
            );
        }

        function menuDesborda() {
            if (!lista) {
                return false;
            }

            barra.classList.remove('barra-navegacion--compacta');

            var gap = 24;
            var anchoLogo = logo ? logo.getBoundingClientRect().width : 0;
            var espacioDisponible = barra.clientWidth - anchoLogo - gap;
            var anchoLista = lista.scrollWidth;

            return anchoLista > espacioDisponible;
        }

        function actualizarModoMenu() {
            if (!MQ_ESCRITORIO.matches) {
                barra.classList.remove('barra-navegacion--compacta');
                return;
            }

            if (menuDesborda()) {
                barra.classList.add('barra-navegacion--compacta');
            } else {
                barra.classList.remove('barra-navegacion--compacta');
                cerrarMenu();
            }
        }

        boton.addEventListener('click', function () {
            if (!barra.classList.contains('barra-navegacion--compacta') && MQ_ESCRITORIO.matches) {
                return;
            }

            if (barra.classList.contains('barra-navegacion--menu-abierto')) {
                cerrarMenu();
            } else {
                abrirMenu();
            }
        });

        if (overlay) {
            overlay.addEventListener('click', cerrarMenu);
        }

        enlaces.forEach(function (enlace) {
            enlace.addEventListener('click', function () {
                if (
                    barra.classList.contains('barra-navegacion--compacta') ||
                    !MQ_ESCRITORIO.matches
                ) {
                    cerrarMenu();
                }
            });
        });

        document.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape') {
                cerrarMenu();
            }
        });

        MQ_ESCRITORIO.addEventListener('change', actualizarModoMenu);
        window.addEventListener('resize', actualizarModoMenu);

        actualizarModoMenu();
    });
})();
