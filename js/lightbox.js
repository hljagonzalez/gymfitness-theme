(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const gallery = document.querySelector('.galeria-contenido .wp-block-gallery');

        if (!gallery) {
            return;
        }

        const images = Array.from(gallery.querySelectorAll('.wp-block-image img'));

        if (!images.length) {
            return;
        }

        const lightbox = document.createElement('div');
        lightbox.className = 'gymfitness-lightbox';
        lightbox.setAttribute('aria-hidden', 'true');
        lightbox.innerHTML = [
            '<button class="gymfitness-lightbox__cerrar" type="button" aria-label="Cerrar imagen">&times;</button>',
            '<button class="gymfitness-lightbox__nav gymfitness-lightbox__nav--prev" type="button" aria-label="Imagen anterior">&#8249;</button>',
            '<img class="gymfitness-lightbox__imagen" alt="">',
            '<button class="gymfitness-lightbox__nav gymfitness-lightbox__nav--next" type="button" aria-label="Imagen siguiente">&#8250;</button>',
        ].join('');

        document.body.appendChild(lightbox);

        const lightboxImage = lightbox.querySelector('.gymfitness-lightbox__imagen');
        const closeButton = lightbox.querySelector('.gymfitness-lightbox__cerrar');
        const prevButton = lightbox.querySelector('.gymfitness-lightbox__nav--prev');
        const nextButton = lightbox.querySelector('.gymfitness-lightbox__nav--next');
        let currentIndex = 0;

        function getImageUrl(image) {
            const link = image.closest('a');

            if (link && link.href) {
                return link.href;
            }

            return image.currentSrc || image.src;
        }

        function openLightbox(index) {
            currentIndex = index;
            lightboxImage.src = getImageUrl(images[currentIndex]);
            lightboxImage.alt = images[currentIndex].alt || '';
            lightbox.classList.add('gymfitness-lightbox--activo');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.classList.add('gymfitness-lightbox-abierto');
        }

        function closeLightbox() {
            lightbox.classList.remove('gymfitness-lightbox--activo');
            lightbox.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('gymfitness-lightbox-abierto');
            lightboxImage.src = '';
        }

        function showPrev() {
            openLightbox((currentIndex - 1 + images.length) % images.length);
        }

        function showNext() {
            openLightbox((currentIndex + 1) % images.length);
        }

        images.forEach(function (image, index) {
            image.style.cursor = 'zoom-in';

            image.addEventListener('click', function (event) {
                event.preventDefault();
                openLightbox(index);
            });
        });

        closeButton.addEventListener('click', closeLightbox);
        prevButton.addEventListener('click', showPrev);
        nextButton.addEventListener('click', showNext);

        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!lightbox.classList.contains('gymfitness-lightbox--activo')) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            }

            if (event.key === 'ArrowLeft') {
                showPrev();
            }

            if (event.key === 'ArrowRight') {
                showNext();
            }
        });
    });
}());
