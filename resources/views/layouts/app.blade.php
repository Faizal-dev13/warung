@php
    $storeSettings = $settings ?? \App\Models\Setting::store();
    $storeName = $storeSettings['store_name'] ?? config('store.name');
    $storeTagline = $storeSettings['store_tagline'] ?? config('store.tagline');
    $headerSubtitle = $storeSettings['header_subtitle'] ?? 'Produk digital siap checkout WA';
    $storeLogoUrl = \App\Models\Setting::logoUrl();
@endphp
<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $storeName)</title>
    <meta name="description" content="{{ $storeTagline }}">
    @php
        $publicAssetBuildReady = file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json'));
    @endphp
    <script>
        (function () {
            try {
                const savedTheme = localStorage.getItem('theme');
                const theme = savedTheme || 'dark';
                if (!savedTheme) localStorage.setItem('theme', 'dark');
                document.documentElement.classList.toggle('dark', theme !== 'light');
            } catch (error) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @if($publicAssetBuildReady)
        @vite(['resources/css/public.css', 'resources/js/public/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'] },
                        boxShadow: { soft: '0 24px 80px rgba(15, 23, 42, .10)' }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="{{ asset('css/store.css') }}">
        <script defer src="{{ asset('js/public-store.js') }}"></script>
    @endif
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-white">
    @php
        $cart = $cart ?? ['items' => [], 'count' => 0, 'subtotal' => 0, 'subtotal_formatted' => 'Rp0'];
    @endphp

    <div class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/80">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center overflow-hidden rounded-2xl bg-slate-950 text-white shadow-soft transition group-hover:-rotate-3 dark:bg-white dark:text-slate-950">
                    @if($storeLogoUrl)
                        <img src="{{ $storeLogoUrl }}" alt="{{ $storeName }}" class="h-full w-full object-cover">
                    @else
                        <i class="ph-bold ph-cube-transparent text-2xl"></i>
                    @endif
                </span>
                <span>
                    <span class="block text-lg font-extrabold tracking-tight">{{ $storeName }}</span>
                    <span class="block text-[11px] leading-tight text-slate-500 dark:text-slate-400 sm:text-xs">{{ $headerSubtitle }}</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-semibold text-slate-600 dark:text-slate-300 md:flex">
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('products.index') }}">Produk</a>
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('home') }}#voucher">Voucher</a>
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('guide') }}">Panduan</a>
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('qna') }}">QnA</a>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" data-cart-toggle class="relative grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-white/10 dark:text-white" aria-label="Buka keranjang">
                    <i class="ph ph-shopping-cart-simple text-xl"></i>
                    <span data-cart-count-badge class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-rose-500 px-1 text-xs font-bold text-white {{ ($cart['count'] ?? 0) > 0 ? '' : 'hidden' }}">{{ $cart['count'] ?? 0 }}</span>
                </button>
                <button type="button" data-theme-toggle class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-white/10 dark:text-white" aria-label="Ganti tema">
                    <i class="ph ph-moon-stars text-xl dark:hidden"></i>
                    <i class="ph ph-sun hidden text-xl dark:block"></i>
                </button>
            </div>
        </nav>
    </div>

    @if(session('success') || session('error'))
        <div class="fixed left-1/2 top-24 z-50 w-[calc(100%-2rem)] max-w-md -translate-x-1/2 rounded-3xl border border-white/60 bg-white/95 p-4 text-sm font-semibold shadow-soft backdrop-blur dark:border-white/10 dark:bg-slate-900/95">
            <div class="flex items-center gap-3 {{ session('error') ? 'text-rose-600' : 'text-emerald-600' }}">
                <i class="ph-fill {{ session('error') ? 'ph-warning-circle' : 'ph-check-circle' }} text-2xl"></i>
                <span>{{ session('success') ?? session('error') }}</span>
            </div>
        </div>
    @endif

    <div data-cart-toast class="pointer-events-none fixed left-1/2 top-24 z-[80] hidden w-[calc(100%-2rem)] max-w-md -translate-x-1/2 rounded-3xl border border-white/70 bg-white/95 p-4 text-sm font-extrabold text-emerald-600 shadow-soft backdrop-blur dark:border-white/10 dark:bg-slate-900/95" role="status" aria-live="polite">
        <div class="flex items-center gap-3">
            <i class="ph-fill ph-check-circle text-2xl"></i>
            <span data-cart-toast-message>Produk masuk keranjang.</span>
        </div>
    </div>

    <main class="pt-20">
        @yield('content')
    </main>

    <aside data-cart-panel data-cart-summary-url="{{ route('cart.summary') }}" class="fixed inset-x-0 bottom-0 z-[60] flex max-h-[94vh] w-full translate-y-full flex-col overflow-hidden rounded-t-[2rem] border-t border-slate-200 bg-white shadow-soft transition duration-300 dark:border-slate-800 dark:bg-slate-950 sm:inset-x-auto sm:inset-y-0 sm:left-auto sm:right-0 sm:max-h-none sm:max-w-md sm:translate-x-full sm:translate-y-0 sm:rounded-none sm:border-l sm:border-t-0" aria-label="Panel checkout">
        <div class="shrink-0 border-b border-slate-200 bg-white px-5 pb-4 pt-3 dark:border-slate-800 dark:bg-slate-950 sm:pt-5">
            <div class="mx-auto mb-3 h-1.5 w-12 rounded-full bg-slate-200 dark:bg-slate-700 sm:hidden"></div>
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-extrabold uppercase tracking-[.18em] text-emerald-600 dark:text-emerald-300">Checkout</p>
                    <h3 class="mt-1 text-xl font-extrabold leading-tight text-slate-950 dark:text-white">Keranjang Belanja</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400 sm:text-sm">Cek produk, isi data singkat, lalu lanjut konfirmasi.</p>
                </div>
                <button type="button" data-cart-close class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700" aria-label="Tutup keranjang">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
        </div>

        <div class="admin-scrollbar flex-1 overflow-y-auto bg-slate-50/80 dark:bg-slate-950">
            <div data-cart-items data-cart-loaded="false" class="p-4 pb-3 sm:p-5 sm:pb-4">
                <div class="rounded-3xl border border-dashed border-slate-200 bg-white/70 p-5 text-center text-sm font-bold text-slate-500 dark:border-slate-800 dark:bg-white/5 dark:text-slate-400">
                    Klik tombol keranjang untuk memuat ringkasan pesanan.
                </div>
            </div>

            <div class="border-t border-slate-200 bg-white p-4 pb-[max(1rem,env(safe-area-inset-bottom))] dark:border-slate-800 dark:bg-slate-950 sm:p-5">
                <div class="mb-4 rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-teal-50 p-4 dark:border-emerald-400/15 dark:from-emerald-500/10 dark:to-teal-500/10">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total Pesanan</p>
                            <strong data-cart-subtotal data-checkout-total class="mt-1 block text-2xl font-extrabold text-slate-950 dark:text-white">{{ $cart['subtotal_formatted'] ?? 'Rp0' }}</strong>
                            <p data-voucher-feedback class="mt-1 hidden text-xs font-extrabold text-emerald-700 dark:text-emerald-300"></p>
                        </div>
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                            <i class="ph-fill ph-whatsapp-logo text-2xl"></i>
                        </span>
                    </div>
                </div>

                <form action="{{ route('checkout.whatsapp') }}" method="post" class="space-y-3">
                    @csrf
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="relative block sm:col-span-2">
                            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input required name="name" placeholder="Nama pembeli" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-300">
                        </label>
                        <label class="relative block">
                            <i class="ph ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input name="phone" placeholder="No. HP" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-300">
                        </label>
                        <label class="relative block">
                            <i class="ph ph-ticket absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input name="voucher" data-voucher-input data-voucher-preview-url="{{ route('voucher.preview') }}" placeholder="Voucher" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold uppercase outline-none transition placeholder:normal-case placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-300">
                        </label>
                    </div>
                    <textarea name="note" rows="2" placeholder="Catatan tambahan, jika ada" class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-300"></textarea>
                    <button class="flex min-h-[3.25rem] w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-4 font-extrabold text-white shadow-lg shadow-emerald-600/25 transition hover:-translate-y-0.5 hover:bg-emerald-700 active:translate-y-0">
                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> Kirim Pesanan ke WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div data-cart-overlay class="pointer-events-none fixed inset-0 z-[55] bg-slate-950/0 transition duration-300"></div>


    @stack('scripts')
</body>
</html>
