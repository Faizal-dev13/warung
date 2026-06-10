(() => {
    const readCsrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    const applyPublicTheme = (theme) => {
        const nextTheme = theme === 'light' ? 'light' : 'dark';
        document.documentElement.classList.toggle('dark', nextTheme === 'dark');
        try {
            localStorage.setItem('theme', nextTheme);
        } catch (error) {}
    };

    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = readCsrf();
        const panel = document.querySelector('[data-cart-panel]');
        const overlay = document.querySelector('[data-cart-overlay]');
        const cartItemsTarget = document.querySelector('[data-cart-items]');
        const subtotalTarget = document.querySelector('[data-cart-subtotal]');
        const toast = document.querySelector('[data-cart-toast]');
        const toastMessage = document.querySelector('[data-cart-toast-message]');
        const voucherInput = document.querySelector('[data-voucher-input]');
        const checkoutTotalTarget = document.querySelector('[data-checkout-total]');
        const voucherFeedback = document.querySelector('[data-voucher-feedback]');
        const cartSummaryUrl = panel?.dataset.cartSummaryUrl || '';
        let toastTimer;
        let voucherTimer;
        let cartHasLoaded = false;
        let cartIsLoading = false;

        document.addEventListener('click', (event) => {
            const themeButton = event.target.closest('[data-theme-toggle]');
            if (!themeButton) return;

            event.preventDefault();
            event.stopPropagation();

            const isDark = document.documentElement.classList.contains('dark');
            applyPublicTheme(isDark ? 'light' : 'dark');
        }, true);

        const showToast = (message, type = 'success') => {
            if (!toast || !toastMessage) return;
            toastMessage.textContent = message || 'Keranjang diperbarui.';
            toast.classList.remove('hidden', 'text-emerald-600', 'text-rose-600');
            toast.classList.add(type === 'error' ? 'text-rose-600' : 'text-emerald-600');
            window.clearTimeout(toastTimer);
            toastTimer = window.setTimeout(() => toast.classList.add('hidden'), 2400);
        };

        const updateBadges = (count) => {
            document.querySelectorAll('[data-cart-count-badge]').forEach((badge) => {
                badge.textContent = count;
                badge.classList.toggle('hidden', Number(count) <= 0);
            });
        };

        const updateCartDom = (data) => {
            if (cartItemsTarget && typeof data.html === 'string') {
                cartItemsTarget.innerHTML = data.html;
                cartItemsTarget.dataset.cartLoaded = 'true';
                cartHasLoaded = true;
            }

            if (subtotalTarget && data.cart?.subtotal_formatted) {
                subtotalTarget.textContent = data.cart.subtotal_formatted;
            }

            if (checkoutTotalTarget && data.cart?.subtotal_formatted) {
                checkoutTotalTarget.textContent = data.cart.subtotal_formatted;
            }

            updateBadges(data.cart?.count || 0);
        };

        const loadCartSummary = async (force = false) => {
            if (!cartSummaryUrl || cartIsLoading || (cartHasLoaded && !force)) return;

            cartIsLoading = true;
            try {
                const response = await fetch(cartSummaryUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await response.json();
                if (!response.ok || !data.ok) throw new Error(data.message || 'Keranjang belum bisa dimuat.');
                updateCartDom(data);
            } catch (error) {
                if (cartItemsTarget) {
                    cartItemsTarget.innerHTML = '<div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-700 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200">Keranjang belum bisa dimuat. Coba buka ulang halaman.</div>';
                }
            } finally {
                cartIsLoading = false;
            }
        };

        const openCart = async () => {
            if (!panel || !overlay) return;
            await loadCartSummary(false);
            panel.classList.remove('translate-x-full', 'translate-y-full', 'sm:translate-x-full');
            overlay.classList.remove('pointer-events-none', 'bg-slate-950/0');
            overlay.classList.add('bg-slate-950/45');
            document.documentElement.classList.add('overflow-hidden');
        };

        const closeCart = () => {
            if (!panel || !overlay) return;
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-y-full', 'sm:translate-x-full');
            overlay.classList.add('pointer-events-none', 'bg-slate-950/0');
            overlay.classList.remove('bg-slate-950/45');
            document.documentElement.classList.remove('overflow-hidden');
        };

        const setSubmitting = (form, isSubmitting) => {
            const buttons = form.querySelectorAll('button');
            buttons.forEach((button) => {
                button.disabled = isSubmitting;
                if (isSubmitting) {
                    button.dataset.originalHtml = button.innerHTML;
                    button.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i><span class="hidden sm:inline">Memproses</span>';
                } else if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                    delete button.dataset.originalHtml;
                }
            });
        };

        const resetVoucherPreview = () => {
            if (checkoutTotalTarget && subtotalTarget) {
                checkoutTotalTarget.textContent = subtotalTarget.textContent;
            }
            if (voucherFeedback) {
                voucherFeedback.textContent = '';
                voucherFeedback.classList.add('hidden');
                voucherFeedback.classList.remove('text-rose-600', 'dark:text-rose-300');
                voucherFeedback.classList.add('text-emerald-700', 'dark:text-emerald-300');
            }
        };

        const refreshVoucherPreview = async () => {
            if (!voucherInput) return;

            const code = voucherInput.value.trim();
            if (!code) {
                resetVoucherPreview();
                return;
            }

            try {
                const formData = new FormData();
                formData.append('voucher', code);

                const response = await fetch(voucherInput.dataset.voucherPreviewUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (checkoutTotalTarget && data.total_formatted) {
                    checkoutTotalTarget.textContent = data.total_formatted;
                }

                if (voucherFeedback) {
                    voucherFeedback.textContent = data.ok
                        ? `${data.message} Hemat ${data.discount_formatted}.`
                        : (data.message || 'Voucher belum bisa digunakan.');
                    voucherFeedback.classList.remove('hidden', 'text-emerald-700', 'dark:text-emerald-300', 'text-rose-600', 'dark:text-rose-300');
                    voucherFeedback.classList.add(data.ok ? 'text-emerald-700' : 'text-rose-600', data.ok ? 'dark:text-emerald-300' : 'dark:text-rose-300');
                }
            } catch (error) {
                resetVoucherPreview();
            }
        };

        voucherInput?.addEventListener('input', () => {
            window.clearTimeout(voucherTimer);
            voucherTimer = window.setTimeout(refreshVoucherPreview, 350);
        });


        const setupBannerSliders = () => {
            document.querySelectorAll('[data-banner-slider]').forEach((slider) => {
                const slides = Array.from(slider.querySelectorAll('[data-banner-slide]'));
                const dots = Array.from(slider.querySelectorAll('[data-banner-dot]'));
                if (slides.length <= 1) return;

                let activeIndex = Math.max(0, slides.findIndex((slide) => !slide.classList.contains('hidden')));
                let timer;

                const showSlide = (index) => {
                    activeIndex = (index + slides.length) % slides.length;
                    slides.forEach((slide, slideIndex) => slide.classList.toggle('hidden', slideIndex !== activeIndex));
                    dots.forEach((dot, dotIndex) => {
                        dot.classList.toggle('bg-white', dotIndex === activeIndex);
                        dot.classList.toggle('bg-white/40', dotIndex !== activeIndex);
                    });
                };

                const start = () => {
                    window.clearInterval(timer);
                    timer = window.setInterval(() => showSlide(activeIndex + 1), 4800);
                };

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        showSlide(index);
                        start();
                    });
                });

                slider.addEventListener('mouseenter', () => window.clearInterval(timer));
                slider.addEventListener('mouseleave', start);
                showSlide(activeIndex);
                start();
            });
        };

        const setupCopyButtons = () => {
            document.querySelectorAll('[data-copy]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const value = button.dataset.copy || '';
                    if (!value) return;

                    try {
                        await navigator.clipboard.writeText(value);
                        showToast('Kode voucher berhasil disalin.');
                    } catch (error) {
                        const input = document.createElement('input');
                        input.value = value;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        input.remove();
                        showToast('Kode voucher berhasil disalin.');
                    }
                });
            });
        };

        setupBannerSliders();
        setupCopyButtons();

        document.querySelectorAll('[data-cart-toggle]').forEach((button) => button.addEventListener('click', openCart));
        document.querySelectorAll('[data-cart-close]').forEach((button) => button.addEventListener('click', closeCart));
        overlay?.addEventListener('click', closeCart);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeCart();
        });

        document.addEventListener('submit', async (event) => {
            const form = event.target.closest('[data-cart-form]');
            if (!form) return;

            event.preventDefault();
            setSubmitting(form, true);

            try {
                const response = await fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: new FormData(form),
                });

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Keranjang belum bisa diperbarui.');
                }

                updateCartDom(data);
                await refreshVoucherPreview();
                showToast(data.message || 'Keranjang diperbarui.');

                if (form.matches('[data-cart-open="true"]')) {
                    await openCart();
                }
            } catch (error) {
                showToast(error.message || 'Terjadi kesalahan. Coba lagi.', 'error');
            } finally {
                setSubmitting(form, false);
            }
        });
    });
})();
