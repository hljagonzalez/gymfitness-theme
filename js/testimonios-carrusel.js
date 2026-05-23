(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const carrusel = document.querySelector('[data-testimonios-carrusel]');

        if (!carrusel) {
            return;
        }

        const viewport = carrusel.querySelector('.inicio-testimonios__viewport');
        const track = carrusel.querySelector('.inicio-testimonios__track');
        const slides = Array.from(carrusel.querySelectorAll('.inicio-testimonios__slide'));
        const btnPrev = carrusel.querySelector('.inicio-testimonios__carrusel-btn--anterior');
        const btnNext = carrusel.querySelector('.inicio-testimonios__carrusel-btn--siguiente');
        const indicadores = carrusel.querySelector('.inicio-testimonios__indicadores');

        if (!viewport || !track || slides.length < 2) {
            if (carrusel) {
                carrusel.classList.add('inicio-testimonios__carrusel--estatico');
            }
            return;
        }

        let indice = 0;
        let autoplayId = null;
        const autoplayMs = 6000;

        function slidesVisibles() {
            if (window.matchMedia('(min-width: 64em)').matches) {
                return Math.min(3, slides.length);
            }
            if (window.matchMedia('(min-width: 48em)').matches) {
                return Math.min(2, slides.length);
            }
            return 1;
        }

        function indiceMaximo() {
            return Math.max(0, slides.length - slidesVisibles());
        }

        function actualizarIndicadores() {
            if (!indicadores) {
                return;
            }

            const paginas = indiceMaximo() + 1;
            indicadores.innerHTML = '';

            for (let i = 0; i < paginas; i += 1) {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'inicio-testimonios__indicador';
                boton.setAttribute('role', 'tab');
                boton.setAttribute('aria-label', 'Testimonio ' + (i + 1));
                boton.setAttribute('aria-selected', i === indice ? 'true' : 'false');

                if (i === indice) {
                    boton.classList.add('inicio-testimonios__indicador--activo');
                }

                boton.addEventListener('click', function () {
                    indice = i;
                    irA(indice);
                });

                indicadores.appendChild(boton);
            }
        }

        function actualizarBotones() {
            const max = indiceMaximo();

            if (btnPrev) {
                btnPrev.disabled = indice <= 0;
            }
            if (btnNext) {
                btnNext.disabled = indice >= max;
            }
        }

        function irA(nuevoIndice) {
            const visibles = slidesVisibles();
            const max = indiceMaximo();

            indice = Math.max(0, Math.min(nuevoIndice, max));
            carrusel.style.setProperty('--slides-visibles', String(visibles));

            const slide = slides[0];
            const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
            const desplazamiento = indice * (slide.offsetWidth + gap);

            track.style.transform = 'translate3d(-' + desplazamiento + 'px, 0, 0)';

            if (indicadores) {
                indicadores.querySelectorAll('.inicio-testimonios__indicador').forEach(function (punto, i) {
                    const activo = i === indice;
                    punto.classList.toggle('inicio-testimonios__indicador--activo', activo);
                    punto.setAttribute('aria-selected', activo ? 'true' : 'false');
                });
            }

            actualizarBotones();
        }

        function reiniciar() {
            actualizarIndicadores();
            irA(Math.min(indice, indiceMaximo()));
        }

        function detenerAutoplay() {
            if (autoplayId) {
                window.clearInterval(autoplayId);
                autoplayId = null;
            }
        }

        function iniciarAutoplay() {
            detenerAutoplay();

            if (indiceMaximo() < 1) {
                return;
            }

            autoplayId = window.setInterval(function () {
                if (indice >= indiceMaximo()) {
                    irA(0);
                } else {
                    irA(indice + 1);
                }
            }, autoplayMs);
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                irA(indice - 1);
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                irA(indice + 1);
            });
        }

        carrusel.addEventListener('mouseenter', detenerAutoplay);
        carrusel.addEventListener('mouseleave', iniciarAutoplay);
        carrusel.addEventListener('focusin', detenerAutoplay);
        carrusel.addEventListener('focusout', function (event) {
            if (!carrusel.contains(event.relatedTarget)) {
                iniciarAutoplay();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!carrusel.contains(document.activeElement)) {
                return;
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                irA(indice - 1);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                irA(indice + 1);
            }
        });

        let resizeTimer;
        window.addEventListener('resize', function () {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(reiniciar, 150);
        });

        reiniciar();
        iniciarAutoplay();
    });
})();
