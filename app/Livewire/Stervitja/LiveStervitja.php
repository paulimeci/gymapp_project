<?php

namespace App\Livewire\Stervitja;

use App\Models\Actions\Stervitja;
use App\Models\Actions\StervitjaUshtrimet;
use App\Models\Actions\StervitjaUshtrimetDetaje;
use App\Models\Human\PjesetETrupit;
use App\Models\Structure\Kategorite;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LiveStervitja extends Component
{
    use WithPagination;
    public bool $modalHapur = false;

    public $kategoriaId = null;
    public $data;
    public array $ushtrimet = [];
    public $id_pjesa_trupit;
    public bool $modalDetaje = false;
    public ?int $stervitjaDetajeId = null;

    public function shihDetajet(int $id): void
    {
        $this->stervitjaDetajeId = $id;
        $this->modalDetaje = true;
    }

    public function mbyllDetajet(): void
    {
        $this->modalDetaje = false;
        $this->stervitjaDetajeId = null;
    }

    public function mount(): void
    {
        $this->data = now()->toDateString();
    }

    public function hapModal($kategoriaId): void
    {
        $this->kategoriaId = $kategoriaId;
        $this->data        = now()->toDateString();
        $this->ushtrimet   = [];

        $kategoria = Kategorite::with('ushtrimet')->find($kategoriaId);
        if (!$kategoria) return;

        foreach ($kategoria->ushtrimet as $u) {
            $this->ushtrimet[$u->id] = [
                'emri'          => $u->emri,
                'njesia_matese' => (int) $u->id_njesia_matese,
                'checked'       => false,
                'sets'          => [
                    ['reps' => '', 'pesha' => '', 'minuta' => '', 'km' => '']
                ],
            ];
        }

        $this->modalHapur = true;
    }

    public function mbyllModal(): void
    {
        $this->modalHapur = false;
        $this->reset(['kategoriaId', 'ushtrimet']);
        $this->data = now()->toDateString();
    }

    public function ruaj(): void
    {
        $this->validate([
            'kategoriaId' => 'required|exists:excs_kategorite,id',
            'data'        => 'required|date',
        ]);

        $zgjedhur = collect($this->ushtrimet)->filter(fn($u) => $u['checked']);

        if ($zgjedhur->isEmpty()) {
            $this->addError('ushtrimet', 'Selekto të paktën një ushtrim.');
            return;
        }

        $stervitja = Stervitja::create([
            'user_id'      => Auth::id(),
            'kategoria_id' => $this->kategoriaId,
            'data'         => $this->data,
        ]);

        foreach ($zgjedhur as $ushtrimiId => $detaje) {
            $su = StervitjaUshtrimet::create([
                'id_stervitjes' => $stervitja->id,
                'id_ushtrimit'  => $ushtrimiId,
            ]);

            $njesia = (int) $detaje['njesia_matese'];

            foreach ($detaje['sets'] as $set) {
                StervitjaUshtrimetDetaje::create([
                    'id_ushtrimit_exct'   => $su->id,
                    'reps'                => $njesia === 1 ? ($set['reps'] ?: 0) : 0,
                    'pesha'               => $njesia === 1 ? ($set['pesha'] ?: 0) : 0,
                    'kohezgjatja_sekonda' => $njesia === 2 ? (!empty($set['minuta']) ? (int)$set['minuta'] * 60 : null) : null,
                    'distanca'            => $njesia === 2 ? ($set['km'] ?: null) : null,
                ]);
            }
        }

        $this->mbyllModal();

        $this->dispatch('showSweetAlert', [
            'type'    => 'success',
            'title'   => 'Ruajtur!',
            'message' => 'Stërvitja u ruajt me sukses.',
        ]);
    }

    public function shtoSet(int $ushtrimiId): void
    {
        $this->ushtrimet[$ushtrimiId]['sets'][] = [
            'reps'   => '',
            'pesha'  => '',
            'minuta' => '',
            'km'     => '',
        ];
    }

    public function hiqSet(int $ushtrimiId, int $setIndex): void
    {
        if (count($this->ushtrimet[$ushtrimiId]['sets']) <= 1) return; // min 1 set
        array_splice($this->ushtrimet[$ushtrimiId]['sets'], $setIndex, 1);
    }

    /*public function getStervitjetPerKalendar($historiku)
    {
        // Harta e ngjyrave sipas emrit të kategorisë (mund t'i shtosh si të dëshirosh)
        $ngjyratEKategorive = [
            'push'   => '#0d6efd', // Primary Blue
            'pull'   => '#dc3545', // Danger Red
            'legs'   => '#198754', // Success Green
            'arms'   => '#ffc107', // Warning Yellow
            'cardio' => '#0dcaf0', // Info Cyan
        ];

        return $historiku->map(function ($stervitje) use ($ngjyratEKategorive) {
            $emriKategorise = strtolower(trim($stervitje->kategoria?->emri ?? ''));

            // Gjen ngjyrën nga harta ose vendos një ngjyrë gri si default nëse nuk njihet kategoria
            $ngjyra = $ngjyratEKategorive[$emriKategorise] ?? '#6c757d';

            return [
                'title'           => $stervitje->kategoria?->emri ?? 'Stërvitje',
                'start'           => $stervitje->data,
                'allDay'          => true,
                'backgroundColor' => $ngjyra,
                'borderColor'     => $ngjyra,
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'stervitja_id' => $stervitje->id
                ]
            ];
        })->toArray();
    }*/
    public function getStervitjetPerKalendar($historiku)
    {
        $ngjyrat = [
            'push' => '#0d6efd', // primary — blu
            'pull' => '#dc3545', // danger — kuq
            'legs' => '#198754', // success — gjelbër
            'mix'  => '#ffc107', // warning — verdhë
        ];

        return $historiku->map(function ($s) use ($ngjyrat) {
            $emri   = strtolower(trim($s->kategoria?->emri ?? ''));
            $ngjyra = $ngjyrat[$emri] ?? '#6c757d';

            return [
                'title'           => $s->kategoria?->emri ?? 'Stërvitje',
                'start'           => $s->data,
                'allDay'          => true,
                'backgroundColor' => $ngjyra,
                'borderColor'     => $ngjyra,
                'textColor'       => '#ffffff',
                'extendedProps'   => ['stervitja_id' => $s->id],
            ];
        })->toArray();
    }
    public function render()
    {
        $historiku = Stervitja::with([
            'kategoria',
            'ushtrimet.ushtrimi.pjeset_e_trupit',
            'ushtrimet.detaje',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get(); // mbaj get() për kalendar

        $historikuPaginate = Stervitja::with([
            'kategoria',
            'ushtrimet.ushtrimi.pjeset_e_trupit',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $this->dispatch('kalendari-perditeso', evente: $this->getStervitjetPerKalendar($historiku));

        return view('livewire.stervitja.live-stervitja', [
            'kategorite'      => Kategorite::withCount('ushtrimet')->latest()->get(),
            'historiku'       => $historikuPaginate,
            'stervitjaAktive' => $this->stervitjaDetajeId
                ? Stervitja::with([
                    'kategoria',
                    'ushtrimet.ushtrimi',
                    'ushtrimet.detaje',
                ])->find($this->stervitjaDetajeId)
                : null,
        ])->layout('layouts.dashboard.app');
    }
}
