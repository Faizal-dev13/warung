<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\SortPositionManager;
use Illuminate\View\View;

class QnaController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->perPage($request);
        $sort = (string) $request->query('sort', 'sort_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $sortable = [
            'question' => 'question',
            'status' => 'is_active',
            'sort_order' => 'sort_order',
            'created_at' => 'created_at',
        ];

        if (! array_key_exists($sort, $sortable)) {
            $sort = 'sort_order';
        }

        $qnas = Qna::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.trim((string) $request->query('q')).'%';
                $query->where(function ($search) use ($keyword) {
                    $search->where('question', 'like', $keyword)
                        ->orWhere('answer', 'like', $keyword);
                });
            })
            ->when($request->query('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->query('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortable[$sort], $direction)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.qnas.index', [
            'qnas' => $qnas,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => (string) $request->query('status', ''),
                'per_page' => $perPage,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.qnas.form', [
            'qna' => new Qna(),
            'nextSortOrder' => SortPositionManager::next(Qna::class),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $payload = $this->payload($request);
            $payload['sort_order'] = SortPositionManager::makeRoomForCreate(Qna::class, $payload['sort_order'] ?? null);

            Qna::create($payload);
        });

        return redirect()->route('admin.qnas.index')->with('success', 'QnA berhasil ditambahkan. Posisi tampil QnA lain sudah otomatis digeser.');
    }

    public function edit(Qna $qna): View
    {
        return view('admin.qnas.form', compact('qna'));
    }

    public function update(Request $request, Qna $qna): RedirectResponse
    {
        DB::transaction(function () use ($request, $qna) {
            $payload = $this->payload($request);
            $payload['sort_order'] = SortPositionManager::move($qna, $payload['sort_order'] ?? null);

            $qna->update($payload);
        });

        return redirect()->route('admin.qnas.index')->with('success', 'QnA berhasil diperbarui. Posisi tampil QnA lain sudah otomatis disesuaikan.');
    }

    public function destroy(Qna $qna): RedirectResponse
    {
        $position = (int) $qna->sort_order;

        DB::transaction(function () use ($qna, $position) {
            $qna->delete();
            SortPositionManager::closeGap(Qna::class, $position);
        });

        return back()->with('success', 'QnA berhasil dihapus. Posisi tampil QnA lain sudah dirapikan.');
    }

    private function payload(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        return [
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort_order' => $data['sort_order'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }
}
