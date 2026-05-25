<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('store.name'))</title>
    <meta name="description" content="{{ config('store.tagline') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui'] },
                    boxShadow: { soft: '0 24px 80px rgba(15, 23, 42, .10)' }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-white">
    <div class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/80">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-white shadow-soft transition group-hover:-rotate-3 dark:bg-white dark:text-slate-950">
                    <i class="ph-bold ph-cube-transparent text-2xl"></i>
                </span>
                <span>
                    <span class="block text-lg font-extrabold tracking-tight">{{ config('store.name') }}</span>
                    <span class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">Produk digital siap checkout WA</span>
                </span>
            </a>
            <div class="hidden items-center gap-8 text-sm font-semibold text-slate-600 dark:text-slate-300 md:flex">
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('home') }}#produk">Produk</a>
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('home') }}#voucher">Voucher</a>
                <a class="hover:text-slate-950 dark:hover:text-white" href="{{ route('guide') }}">Panduan</a>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" data-cart-toggle class="relative grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-white/10 dark:text-white">
                    <i class="ph ph-shopping-cart-simple text-xl"></i>
                    @if(($cart['count'] ?? 0) > 0)
                        <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-rose-500 px-1 text-xs font-bold text-white">{{ $cart['count'] }}</span>
                    @endif
                </button>
                <button type="button" data-theme-toggle class="grid h-11 w-11 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:-translate-y-0.5 hover:shadow-lg dark:border-white/10 dark:bg-white/10 dark:text-white">
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

    <main class="pt-20">
        @yield('content')
    </main>

    <aside data-cart-panel class="fixed inset-y-0 right-0 z-[60] flex w-full translate-x-full flex-col border-l border-slate-200 bg-white shadow-soft transition duration-300 dark:border-white/10 dark:bg-slate-950 sm:max-w-md">
        <div class="flex items-center justify-between border-b border-slate-200 p-5 dark:border-white/10">
            <div>
                <h3 class="text-lg font-bold">Keranjang</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Checkout langsung ke WhatsApp</p>
            </div>
            <button data-cart-close class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 transition hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            @if(empty($cart['items'] ?? []))
                <div class="grid min-h-72 place-items-center rounded-3xl border border-dashed border-slate-300 p-8 text-center dark:border-white/15">
                    <div>
                        <i class="ph ph-shopping-bag-open text-5xl text-slate-400"></i>
                        <p class="mt-4 font-semibold">Keranjang masih kosong.</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pilih produk dulu, lalu lanjut checkout via WhatsApp.</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($cart['items'] as $item)
                        @php
                            $cartImagePath = $item['image_path'] ?? null;
                            $cartImageUrl = $cartImagePath
                                ? (\Illuminate\Support\Str::startsWith($cartImagePath, ['http://', 'https://', '/']) ? $cartImagePath : \Illuminate\Support\Facades\Storage::url($cartImagePath))
                                : null;
                        @endphp
                        <div class="rounded-3xl border border-slate-200 p-4 dark:border-white/10">
                            <div class="flex gap-3">
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-slate-100 dark:bg-white/10">
                                    @if($cartImageUrl)
                                        <img src="{{ $cartImageUrl }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="grid h-full w-full place-items-center">
                                            <i class="ph {{ $item['icon'] ?? 'ph-package' }} text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 font-bold leading-snug">{{ $item['name'] }}</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ \App\Support\Money::rupiah($item['price']) }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <form action="{{ route('cart.update', $item['id']) }}" method="post" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input name="qty" type="number" min="1" max="99" value="{{ $item['qty'] }}" class="h-10 w-20 rounded-2xl border border-slate-200 bg-white px-3 text-sm outline-none focus:border-slate-950 dark:border-white/10 dark:bg-white/10">
                                    <button class="h-10 rounded-2xl bg-slate-100 px-3 text-xs font-bold transition hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20">Update</button>
                                </form>
                                <form action="{{ route('cart.remove', $item['id']) }}" method="post">
                                    @csrf @method('DELETE')
                                    <button class="h-10 rounded-2xl bg-rose-50 px-3 text-xs font-bold text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="border-t border-slate-200 p-5 dark:border-white/10">
            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                <strong class="text-xl">{{ $cart['subtotal_formatted'] ?? 'Rp0' }}</strong>
            </div>
            <form action="{{ route('checkout.whatsapp') }}" method="post" class="space-y-3">
                @csrf
                <input required name="name" placeholder="Nama Anda" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-slate-950 dark:border-white/10 dark:bg-white/10">
                <input name="phone" placeholder="No. HP opsional" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-slate-950 dark:border-white/10 dark:bg-white/10">
                <input name="voucher" placeholder="Kode voucher opsional" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-slate-950 dark:border-white/10 dark:bg-white/10">
                <textarea name="note" rows="2" placeholder="Catatan opsional" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-950 dark:border-white/10 dark:bg-white/10"></textarea>
                <button class="flex h-13 w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    <i class="ph-fill ph-whatsapp-logo text-xl"></i> Checkout ke WhatsApp
                </button>
            </form>
        </div>
    </aside>
    <div data-cart-overlay class="pointer-events-none fixed inset-0 z-[55] bg-slate-950/0 transition duration-300"></div>

    <footer class="border-t border-slate-200 bg-white py-12 dark:border-white/10 dark:bg-slate-950">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-white dark:bg-white dark:text-slate-950"><i class="ph-bold ph-cube-transparent text-2xl"></i></span>
                    <strong class="text-lg">{{ config('store.name') }}</strong>
                </div>
                <p class="mt-4 max-w-md text-sm leading-7 text-slate-500 dark:text-slate-400">{{ config('store.tagline') }} Pilih produk, masukkan keranjang, lalu checkout ke WhatsApp untuk konfirmasi manual.</p>
            </div>
            <div>
                <h4 class="font-bold">Navigasi</h4>
                <div class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <a class="block hover:text-slate-950 dark:hover:text-white" href="{{ route('home') }}#produk">Produk</a>
                    <a class="block hover:text-slate-950 dark:hover:text-white" href="{{ route('home') }}#fitur">Fitur</a>
                    <a class="block hover:text-slate-950 dark:hover:text-white" href="{{ route('guide') }}">Panduan</a>
                </div>
            </div>
            <div>
                <h4 class="font-bold">Dukungan</h4>
                <div class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <p>Checkout WA</p>
                    <p>Konfirmasi manual</p>
                    <p>Support setelah pembelian</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/store.js') }}"></script>
</body>
</html>
