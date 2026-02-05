document.addEventListener('DOMContentLoaded', () => {
    const galleries = document.querySelectorAll('.wp-block-gallery.is-carousel');

    galleries.forEach(gallery => {
        const images = Array.from(gallery.querySelectorAll('.wp-block-image'));
        if (images.length === 0) return;

        let currentIndex = 0;

        // Criação dos Controles
        const controls = document.createElement('div');
        controls.className = 'carousel-controls';
        const dots = [];

        images.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot';
            dot.ariaLabel = `Slide ${index + 1}`;
            dot.addEventListener('click', () => handleNavigation(index));
            controls.appendChild(dot);
            dots.push(dot);
        });
        gallery.appendChild(controls);

        // Inicializa o primeiro
        images[0].classList.add('is-active');
        dots[0].classList.add('active');

        // Função de Navegação
        const handleNavigation = (targetIndex) => {
            if (targetIndex === currentIndex) return;

            const currentImg = images[currentIndex];
            const targetImg = images[targetIndex];
            const isGoingBack = targetIndex < currentIndex;

            // Atualiza dots
            dots.forEach(d => d.classList.remove('active'));
            dots[targetIndex].classList.add('active');

            if (isGoingBack) {
                // VOLTAR (Entrada suave da esquerda)
                targetImg.classList.add('is-starting-left');
                void targetImg.offsetWidth; // Force Reflow
                
                targetImg.classList.remove('is-starting-left');
                targetImg.classList.add('is-active');
                
                currentImg.classList.remove('is-active');

            } else {
                // AVANÇAR (Saída suave para esquerda)
                targetImg.classList.add('is-active');
                currentImg.classList.add('is-exiting-left');
                currentImg.classList.remove('is-active');

                // SINCRONIA: 800ms para bater com o CSS 0.8s
                setTimeout(() => {
                    currentImg.classList.remove('is-exiting-left');
                }, 800);
            }

            currentIndex = targetIndex;
        };
    });
});