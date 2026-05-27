@if($paginator->total() > 0)
    <div class="mt-4 flex flex-col gap-3 rounded-[1.25rem] border border-slate-200 bg-white p-4 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
        <p class="font-semibold text-slate-500 dark:text-slate-400">
            Menampilkan <span class="font-extrabold text-slate-900 dark:text-white">{{ $paginator->firstItem() }}</span>-<span class="font-extrabold text-slate-900 dark:text-white">{{ $paginator->lastItem() }}</span> dari <span class="font-extrabold text-slate-900 dark:text-white">{{ $paginator->total() }}</span> data
        </p>
        @if($paginator->hasPages())
            <div class="flex flex-wrap items-center gap-2">
                @if($paginator->onFirstPage())
                    <span class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-300 dark:border-slate-800 dark:text-slate-600">Prev</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Prev</a>
                @endif

                @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-teal-700 px-3 text-xs font-extrabold text-white dark:bg-teal-500 dark:text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">{{ $page }}</a>
                    @endif
                @endforeach

                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Next</a>
                @else
                    <span class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-xs font-extrabold text-slate-300 dark:border-slate-800 dark:text-slate-600">Next</span>
                @endif
            </div>
        @endif
    </div>
@endif
