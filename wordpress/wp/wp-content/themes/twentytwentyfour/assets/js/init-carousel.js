document.addEventListener('DOMContentLoaded', () => {
    const galleries = document.querySelectorAll('.wp-block-gallery.is-carousel');

    galleries.forEach(gallery => {
        // O Swiper precisa de classes específicas (swiper, swiper-wrapper, swiper-slide)
        gallery.classList.add('swiper');
        
        // As figuras dentro da galeria tornam-se os slides
        const images = gallery.querySelectorAll('.wp-block-image');
        images.forEach(img => img.classList.add('swiper-slide'));

        // Envolve as imagens em uma div 'swiper-wrapper' (exigência da lib)
        const wrapper = document.createElement('div');
        wrapper.classList.add('swiper-wrapper');
        gallery.append(wrapper);
        images.forEach(img => wrapper.appendChild(img));

        // Inicializa o Swiper
        new Swiper(gallery, {
            slidesPerView: 1.2, // Mostra uma foto e um pedaço da próxima
            centeredSlides: true,
            spaceBetween: 20,
            loop: true,
            breakpoints: {
                768: { slidesPerView: 3 }
            }
        });
    });
});