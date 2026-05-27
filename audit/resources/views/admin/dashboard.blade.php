@extends('admin.layouts.app')
@section('page_title','Dashboard')
@section('page_description','Ringkasan performa toko, pesanan terbaru, dan aksi penting yang perlu ditindaklanjuti.')
@section('content')
@php
    $statusLabels = [
        'waiting_whatsapp_confirmation' => 'Menunggu WA',
        'confirmed' => 'Dikonfirmasi',
        'processed' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $statusClasses = [
        'waiting_whatsapp_confirmation' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-400/10 dark:text-amber-300 dark:ring-amber-400/20',
        'confirmed' => 'bg-teal-50 text-teal-700 ring-teal-200 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20',
        'processed' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-400/10 dark:text-sky-300 dark:ring-sky-400/20',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20',
        'cancelled' => 'bg-red-50 text-red-700 ring-red-200 dark:bg-red-400/10 dark:text-red-300 dark:ring-red-400/20',
    ];

    $summaryCards = [
        [
            'label' => 'Total Produk',
            'value' => $totalProducts,
            'icon' => 'ph-package',
            'description' => 'Produk aktif dan siap ditawarkan',
            'tone' => 'bg-teal-50 text-teal-700 ring-teal-100 dark:bg-teal-400/10 dark:text-teal-300 dark:ring-teal-400/20',
            'accent' => 'from-teal-500 to-emerald-500',
        ],
        [
            'label' => 'Kategori',
            'value' => $totalCategories,
            'icon' => 'ph-folders',
            'description' => 'Kelompok katalog toko',
            'tone' => 'bg-amber-50 text-amber-700 ring-amber-100 dark:bg-amber-400/10 dark:text-amber-300 dark:ring-amber-400/20',
            'accent' => 'from-amber-500 to-orange-500',
        ],
        [
            'label' => 'Total Order',
            'value' => $totalOrders,
            'icon' => 'ph-receipt',
            'description' => 'Semua pesanan yang masuk',
            'tone' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:ring-slate-700',
            'accent' => 'from-slate-700 to-slate-950',
        ],
        [
            'label' => 'Perlu Follow-up',
            'value' => $pendingOrders,
            'icon' => 'ph-whatsapp-logo',
            'description' => 'Menunggu konfirmasi customer',
            'tone' => 'bg-emerald-50 text-emerald-700 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/20',
            'accent' => 'from-emerald-500 to-teal-500',
        ],
    ];

    $quickActions = [
        [
            'label' => 'Tambah Produk',
            'description' => 'Lengkapi katalog dengan item baru',
            'route' => route('admin.products.create'),
            'icon' => 'ph-plus-circle',
            'tone' => 'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300',
        ],
        [
            'label' => 'Buat Voucher',
            'description' => 'Siapkan promo untuk customer',
            'route' => route('admin.vouchers.create'),
            'icon' => 'ph-ticket',
            'tone' => 'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        ],
        [
            'label' => 'Atur Banner',
            'description' => 'Perbarui tampilan halaman depan',
            'route' => route('admin.banners.create'),
            'icon' => 'ph-images',
            'tone' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        ],
    ];
@endphp

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-5 text-white shadow-soft dark:border-slate-800 sm:p-6 lg:p-7">
    <div class="absolute -right-16 -top-24 h-64 w-64 rounded-full bg-teal-400/20 blur-3xl"></div>
    <div class="absolute -bottom-28 left-10 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>

    <div class="relative grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs font-bold text-teal-100">
                <i class="ph ph-chart-line-up"></i>
                Ringkasan hari ini
            </div>
            <h2 class="mt-4 text-2xl font-extrabold tracking-tight sm:text-3xl">
                Pantau toko lebih cepat dari satu halaman.
            </h2>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                Cek katalog, order terbaru, dan pesanan yang perlu follow-up tanpa berpindah halaman terlalu banyak.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[360px]">
            <a href="{{ route('admin.orders.index') }}" class="group rounded-3xl border border-white/10 bg-white/10 p-4 transition hover:-translate-y-0.5 hover:bg-white/15">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wide text-slate-300">Order</span>
                    <i class="ph ph-arrow-right text-slate-300 transition group-hover:translate-x-1"></i>
                </div>
                <p class="mt-3 text-3xl font-extrabold">{{ $totalOrders }}</p>
                <p class="mt-1 text-xs font-semibold text-slate-300">Total pesanan masuk</p>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="group rounded-3xl border border-emerald-300/20 bg-emerald-400/10 p-4 transition hover:-translate-y-0.5 hover:bg-emerald-400/15">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wide text-emerald-100">Follow-up</span>
                    <i class="ph ph-whatsapp-logo text-emerald-200"></i>
                </div>
                <p class="mt-3 text-3xl font-extrabold">{{ $pendingOrders }}</p>
                <p class="mt-1 text-xs font-semibold text-emerald-100">Perlu dikonfirmasi</p>
            </a>
        </div>
    </div>
</section>

<section class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($summaryCards as $card)
        <article class="group relative overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-card dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $card['accent'] }}"></div>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ number_format($card['value'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs font-semibold leading-5 text-slate-400 dark:text-slate-500">{{ $card['description'] }}</p>
                </div>
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl ring-1 {{ $card['tone'] }}">
                    <i class="ph {{ $card['icon'] }} text-2xl"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

<section class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
    <div class="rounded-[1.7rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 p-5 dark:border-slate-800 sm:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <i class="ph ph-clock-counter-clockwise"></i>
                        Update terbaru
                    </div>
                    <h2 class="mt-3 text-xl font-extrabold text-slate-950 dark:text-white">Order Terbaru</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Pantau pesanan terakhir dan lanjutkan follow-up customer.
                    </p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-extrabold text-slate-700 transition hover:-translate-y-0.5 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Lihat Semua
                    <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="admin-scrollbar overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-4">Invoice</th>
                            <th class="px-3 py-4">Customer</th>
                            <th class="px-3 py-4">Total</th>
                            <th class="px-3 py-4">Status</th>
                            <th class="px-3 py-4">Tanggal</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($latestOrders as $order)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                <td class="px-5 py-4">
                                    <a class="font-extrabold text-slate-950 transition hover:text-teal-700 dark:text-white dark:hover:text-teal-300" href="{{ route('admin.orders.show',$order) }}">
                                        {{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="font-bold text-slate-800 dark:text-slate-100">{{ $order->customer_name }}</div>
                                </td>
                                <td class="px-3 py-4 font-extrabold text-slate-900 dark:text-white">
                                    Rp {{ number_format($order->total,0,',','.') }}
                                </td>
                                <td class="px-3 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-extrabold ring-1 {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' }}">
                                        {{ $statusLabels[$order->status] ?? str_replace('_',' ',$order->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.orders.show',$order) }}" class="inline-flex items-center gap-1 rounded-xl bg-teal-700 px-3.5 py-2 text-xs font-extrabold text-white shadow-lg shadow-teal-700/15 transition hover:-translate-y-0.5 hover:bg-teal-800 dark:bg-teal-500 dark:hover:bg-teal-400">
                                        Detail
                                        <i class="ph ph-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            @include('admin.partials.empty-state', ['colspan' => 6, 'icon' => 'ph-receipt', 'title' => 'Belum ada order', 'description' => 'Order terbaru akan muncul di sini.'])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-3 p-4 md:hidden">
            @forelse($latestOrders as $order)
                <a href="{{ route('admin.orders.show',$order) }}" class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm transition active:scale-[0.99] dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-extrabold text-slate-950 dark:text-white">{{ $order->invoice_number }}</p>
                            <p class="mt-1 truncate text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $order->customer_name }}</p>
                        </div>
                        <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-[11px] font-extrabold ring-1 {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' }}">
                            {{ $statusLabels[$order->status] ?? str_replace('_',' ',$order->status) }}
                        </span>
                    </div>

                    <div class="mt-4 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold text-slate-400">Total</p>
                            <p class="text-base font-extrabold text-slate-950 dark:text-white">Rp {{ number_format($order->total,0,',','.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-400">Tanggal</p>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center dark:border-slate-700 dark:bg-slate-800/50">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-white text-slate-400 dark:bg-slate-900">
                        <i class="ph ph-receipt text-2xl"></i>
                    </div>
                    <h3 class="mt-3 text-sm font-extrabold text-slate-900 dark:text-white">Belum ada order</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">Order terbaru akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <aside class="grid gap-5">
        <section class="rounded-[1.7rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-950 dark:text-white">Aksi Cepat</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Perbarui konten toko tanpa masuk ke banyak menu.
                    </p>
                </div>
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-300">
                    <i class="ph ph-lightning text-xl"></i>
                </span>
            </div>

            <div class="mt-5 grid gap-3">
                @foreach($quickActions as $action)
                    <a href="{{ $action['route'] }}" class="group flex items-center gap-3 rounded-3xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-sm dark:border-slate-800 dark:hover:bg-slate-800/50">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl {{ $action['tone'] }}">
                            <i class="ph {{ $action['icon'] }} text-xl"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold text-slate-900 dark:text-white">{{ $action['label'] }}</span>
                            <span class="mt-0.5 block text-xs font-semibold leading-5 text-slate-500 dark:text-slate-400">{{ $action['description'] }}</span>
                        </span>
                        <i class="ph ph-arrow-right shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-teal-600 dark:group-hover:text-teal-300"></i>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="rounded-[1.7rem] border border-teal-100 bg-gradient-to-br from-teal-50 via-white to-emerald-50 p-5 shadow-soft dark:border-teal-400/20 dark:from-teal-400/10 dark:via-slate-900 dark:to-emerald-400/10 sm:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-teal-700 shadow-sm dark:bg-slate-900 dark:text-teal-300">
                    <i class="ph ph-sparkle text-2xl"></i>
                </span>
                <div>
                    <h2 class="text-base font-extrabold text-slate-950 dark:text-white">Tips Tampilan Toko</h2>
                    <p class="mt-0.5 text-xs font-semibold text-slate-500 dark:text-slate-400">Buat customer lebih yakin sebelum order.</p>
                </div>
            </div>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">
                Gunakan foto produk yang jelas, banner yang fokus pada promo utama, dan deskripsi singkat yang mudah dipahami customer.
            </p>
        </section>
    </aside>
</section>
@endsection
