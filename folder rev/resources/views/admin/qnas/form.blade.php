@extends('admin.layouts.app')
@section('page_title', $qna->exists ? 'Edit QnA' : 'Tambah QnA')
@section('page_description','Kelola pertanyaan dan jawaban yang tampil di halaman QnA customer.')
@section('page_action')
<a href="{{ route('admin.qnas.index') }}" class="admin-btn-secondary"><i class="ph ph-arrow-left"></i> Kembali</a>
@endsection
@section('content')
<form method="POST" action="{{ $qna->exists ? route('admin.qnas.update',$qna) : route('admin.qnas.store') }}" class="space-y-5">
    @csrf
    @if($qna->exists)@method('PUT')@endif

    <section class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-slate-950 p-5 text-white sm:p-6">
            <div class="flex items-start gap-3">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-teal-600 text-white shadow-lg shadow-teal-950/30"><i class="ph ph-question text-2xl"></i></span>
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[.22em] text-teal-200">QnA Customer</p>
                    <h2 class="mt-1 text-xl font-extrabold">{{ $qna->exists ? 'Perbarui QnA' : 'Tambah QnA Baru' }}</h2>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">Buat jawaban singkat dan jelas agar customer lebih yakin sebelum checkout.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 sm:p-6">
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Pertanyaan</span>
                <input name="question" value="{{ old('question',$qna->question) }}" class="admin-input" placeholder="Contoh: Bagaimana cara konfirmasi pesanan?" required>
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-extrabold">Jawaban</span>
                <textarea name="answer" rows="7" class="admin-textarea" required>{{ old('answer',$qna->answer) }}</textarea>
            </label>
            <div class="grid gap-5 lg:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-extrabold">Urutan</span>
                    <input name="sort_order" type="number" min="1" value="{{ old('sort_order', $qna->exists ? max(1, (int) $qna->sort_order) : ($nextSortOrder ?? 1)) }}" class="admin-input">
                    <small class="mt-2 block text-xs leading-5 text-slate-500 dark:text-slate-400">Jika urutan diubah, QnA lain akan otomatis bergeser.</small>
                </label>
                <label class="flex cursor-pointer items-start gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 transition hover:border-teal-200 hover:bg-teal-50/40 dark:border-slate-800 dark:bg-slate-950/40">
                    <input type="checkbox" name="is_active" value="1" class="mt-1 h-5 w-5 rounded border-slate-300 text-teal-700 focus:ring-teal-600" @checked(old('is_active',$qna->is_active ?? true))>
                    <span><b class="block text-sm text-slate-950 dark:text-white">QnA Aktif</b><small class="mt-1 block text-slate-500 dark:text-slate-400">Tampilkan di halaman customer.</small></span>
                </label>
            </div>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white/90 p-3 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.qnas.index') }}" class="admin-btn-secondary">Batal</a>
        <button class="admin-btn-primary"><i class="ph ph-check-circle"></i> Simpan QnA</button>
    </div>
</form>
@endsection
