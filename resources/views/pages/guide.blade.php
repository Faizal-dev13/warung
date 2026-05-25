@extends('layouts.app')
@section('title', 'Panduan Pembelian - '.config('store.name'))
@section('content')
<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-soft dark:border-white/10 dark:bg-white/5">
        <p class="font-bold text-blue-600 dark:text-blue-400">Panduan Pengguna</p>
        <h1 class="mt-2 text-4xl font-extrabold">Cara membeli produk</h1>
        <div class="mt-8 grid gap-4">
            @foreach([
                ['Pilih produk', 'Gunakan pencarian atau filter kategori untuk menemukan produk yang sesuai.'],
                ['Masukkan keranjang', 'Klik tombol plus pada kartu produk atau tombol tambah pada halaman detail.'],
                ['Isi data checkout', 'Buka keranjang, isi nama, nomor HP opsional, voucher, dan catatan.'],
                ['Kirim ke WhatsApp', 'Klik checkout dan sistem akan membuat pesan otomatis ke WhatsApp admin.'],
            ] as $index => $step)
                <div class="flex gap-4 rounded-3xl border border-slate-200 p-5 dark:border-white/10"><span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-slate-950 font-bold text-white dark:bg-white dark:text-slate-950">{{ $index + 1 }}</span><div><h3 class="font-bold">{{ $step[0] }}</h3><p class="mt-1 text-sm leading-7 text-slate-500 dark:text-slate-400">{{ $step[1] }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endsection
