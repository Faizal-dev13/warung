(function () {
    const root = document.documentElement;
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        root.classList.add('dark');
    }

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            root.classList.toggle('dark');
            localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        });
    });

    const panel = document.querySelector('[data-cart-panel]');
    const overlay = document.querySelector('[data-cart-overlay]');

    const openCart = () => {
        panel && panel.classList.add('is-open');
        overlay && overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    const closeCart = () => {
        panel && panel.classList.remove('is-open');
        overlay && overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('[data-cart-toggle]').forEach((button) => button.addEventListener('click', openCart));
    document.querySelectorAll('[data-cart-close]').forEach((button) => button.addEventListener('click', closeCart));
    overlay && overlay.addEventListener('click', closeCart);

    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.getAttribute('data-copy');
            try {
                await navigator.clipboard.writeText(value);
                const original = button.innerHTML;
                button.innerHTML = '<i class="ph ph-check"></i> Tersalin';
                setTimeout(() => { button.innerHTML = original; }, 1300);
            } catch (error) {
                window.prompt('Salin kode voucher:', value);
            }
        });
    });

    const slider = document.querySelector('[data-banner-slider]');
    if (slider) {
        const slides = Array.from(slider.querySelectorAll('[data-banner-slide]'));
        const dots = Array.from(slider.querySelectorAll('[data-banner-dot]'));
        let activeIndex = 0;

        const showSlide = (index) => {
            if (!slides.length) return;
            activeIndex = index % slides.length;
            slides.forEach((slide, slideIndex) => slide.classList.toggle('hidden', slideIndex !== activeIndex));
            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('bg-white', dotIndex === activeIndex);
                dot.classList.toggle('bg-white/40', dotIndex !== activeIndex);
            });
        };

        dots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));

        if (slides.length > 1) {
            setInterval(() => showSlide(activeIndex + 1), 4500);
        }
    }

    setTimeout(() => {
        const toast = document.querySelector('.fixed.left-1\\/2.top-24');
        if (toast) toast.style.display = 'none';
    }, 2600);
})();
