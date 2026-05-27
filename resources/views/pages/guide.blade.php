@extends('layouts.app')
@section('title', ($settings['guide_title'] ?? 'Panduan Pembelian').' - '.($settings['store_name'] ?? config('store.name')))
@section('content')
@php
    $guideTitle = $settings['guide_title'] ?? 'Cara membeli produk';
    $guideSubtitle = $settings['guide_subtitle'] ?? 'Ikuti langkah sederhana berikut agar pesanan kamu bisa dikonfirmasi dengan cepat.';
    $steps = [
        ['Pilih produk', 'Gunakan pencarian atau filter kategori untuk menemukan produk yang sesuai.'],
        ['Masukkan keranjang', 'Klik tombol plus pada kartu produk atau tombol tambah pada halaman detail.'],
        ['Isi data checkout', 'Buka keranjang, isi nama, nomor HP opsional, voucher, dan catatan.'],
        ['Kirim ke WhatsApp', 'Klik checkout dan sistem akan membuat pesan otomatis ke WhatsApp admin.'],
    ];
@endphp
<section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[.85fr_1.15fr] lg:items-stretch">
        <div class="overflow-hidden rounded-[2rem] bg-slate-950 p-7 text-white shadow-soft sm:p-8 lg:p-10">
            <div class="flex h-full flex-col justify-between gap-12">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[.18em] text-teal-100 ring-1 ring-white/10"><i class="ph ph-map-trifold"></i> Panduan</span>
                    <h1 class="mt-6 text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ $guideTitle }}</h1>
                    <p class="mt-5 text-sm leading-7 text-white/70 sm:text-base">{{ $guideSubtitle }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                    <div class="flex items-start gap-3">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-500 text-white"><i class="ph-fill ph-whatsapp-logo text-xl"></i></span>
                        <div>
                            <h2 class="font-extrabold">Alur checkout singkat</h2>
                            <p class="mt-1 text-sm leading-6 text-white/65">Pesanan akan diarahkan ke WhatsApp agar konfirmasi lebih mudah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-soft dark:border-white/10 dark:bg-white/5 sm:p-7 lg:p-8">
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($steps as $index => $step)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:bg-white/10">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 font-extrabold text-white dark:bg-white dark:text-slate-950">{{ $index + 1 }}</span>
                        <h3 class="mt-5 font-extrabold text-slate-950 dark:text-white">{{ $step[0] }}</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-500 dark:text-slate-400">{{ $step[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
