<tr>
    <td colspan="{{ $colspan ?? 1 }}" class="px-5 py-12 text-center">
        <div class="mx-auto max-w-sm">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-3xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                <i class="ph {{ $icon ?? 'ph-folder-simple-dashed' }} text-3xl"></i>
            </span>
            <p class="mt-4 font-extrabold text-slate-950 dark:text-white">{{ $title ?? 'Data belum tersedia' }}</p>
            @isset($description)
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $description }}</p>
            @endisset
        </div>
    </td>
</tr>
