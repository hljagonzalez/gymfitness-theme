(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var lettersEl = document.querySelector('.site-header__hero-titulo.ml9 .letters');

        if (!lettersEl || typeof anime === 'undefined') {
            return;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        var text = lettersEl.textContent;

        lettersEl.textContent = '';
        text.split('').forEach(function (char) {
            var span = document.createElement('span');
            var esEspacio = char === ' ' || char === '\u00a0';

            span.className = esEspacio ? 'letter letter--space' : 'letter';

            if (esEspacio) {
                span.innerHTML = '&nbsp;';
            } else {
                span.textContent = char;
            }

            lettersEl.appendChild(span);
        });

        anime
            .timeline()
            .add({
                targets: '.site-header__hero-titulo.ml9 .letter:not(.letter--space)',
                scale: [0, 1],
                duration: 1500,
                elasticity: 600,
                delay: function (el, i) {
                    return 45 * (i + 1);
                },
            });
    });
})();
