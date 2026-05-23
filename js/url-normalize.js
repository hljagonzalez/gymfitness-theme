/**
 * Añade https:// a dominios escritos sin protocolo (www.ejemplo.es).
 */
(function () {
    function normalizarUrl(valor) {
        var v = (valor || '').trim();
        if (!v) {
            return '';
        }
        if (/^https?:\/\//i.test(v)) {
            return v;
        }
        return 'https://' + v.replace(/^\/+/, '');
    }

    function normalizarInput(input) {
        if (!input || input.type !== 'url') {
            return;
        }
        var normalizada = normalizarUrl(input.value);
        if (normalizada !== input.value) {
            input.value = normalizada;
        }
    }

    function enlazarInput(input) {
        input.addEventListener('blur', function () {
            normalizarInput(input);
        });
    }

    document.querySelectorAll('input[type="url"]').forEach(enlazarInput);

    document.addEventListener(
        'submit',
        function (evento) {
            var formulario = evento.target;
            if (!formulario || !formulario.querySelectorAll) {
                return;
            }
            formulario.querySelectorAll('input[type="url"]').forEach(normalizarInput);
        },
        true
    );

    document.addEventListener('wpcf7validating', function (evento) {
        var formulario = evento.target;
        if (!formulario || !formulario.querySelectorAll) {
            return;
        }
        formulario.querySelectorAll('input[type="url"]').forEach(normalizarInput);
    });
})();
