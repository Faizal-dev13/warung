@php
    $activeSort = request('sort', $defaultSort ?? '');
    $activeDirection = request('direction', $defaultDirection ?? 'asc') === 'desc' ? 'desc' : 'asc';
    $isActive = $activeSort === $sort;
    $nextDirection = $isActive && $activeDirection === 'asc' ? 'desc' : 'asc';
    $params = array_merge(request()->except(['page', 'sort', 'direction']), [
        'sort' => $sort,
        'direction' => $nextDirection,
    ]);
    $url = url()->current().'?'.http_build_query($params);
@endphp
<a href="{{ $url }}" class="inline-flex items-center gap-1 rounded-xl px-2 py-1 font-extrabold transition {{ $isActive ? 'bg-teal-700 text-white dark:bg-teal-500 dark:text-white' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
    {{ $label }}
    @if($isActive)
        <i class="ph {{ $activeDirection === 'asc' ? 'ph-caret-up' : 'ph-caret-down' }} text-xs"></i>
    @else
        <i class="ph ph-caret-up-down text-xs opacity-60"></i>
    @endif
</a>
