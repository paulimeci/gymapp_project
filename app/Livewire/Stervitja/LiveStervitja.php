<?php

namespace App\Livewire\Stervitja;

use App\Models\Actions\Stervitja;
use App\Models\Actions\StervitjaUshtrimet;
use App\Models\Actions\StervitjaUshtrimetDetaje;
use App\Models\Human\PjesetETrupit;
use App\Models\Structure\Kategorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public bool $modal_fshirje = false;
    public $stervitjaPezull = null; // Mbajmë të dhënat e stërvitjes së gjetur
    public $modal_pending = false;
    public ?int $seancaPerFshirjeId = null;
    public ?int $stervitjaDetajeId = null;
    public ?int $stervitjaId = null;

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
        // Rregullojmë query-n duke përfshirë marrëdhënien e kategorisë dhe kontrolluar saktë 'user_id'
        $stervitjaEkzistuese = Stervitja::with('kategoria')
            ->where('user_id', Auth::id())
            ->where('statusi', 0)
            ->first(); // Përdorim first() që të marrim objektin nëse ekziston

        if ($stervitjaEkzistuese) {
            $this->stervitjaPezull = $stervitjaEkzistuese; // Ruajmë objektin për ta shfaqur te modali
            $this->modal_pending = true;
        } else {
            $this->kategoriaId = $kategoriaId;
            $this->data        = now()->toDateString();
            $this->ushtrimet   = [];
            $this->stervitjaId = null; // Sigurohemi që të jetë null për stërvitje të re

            $kategoria = Kategorite::with('ushtrimet')->find($kategoriaId);
            if (!$kategoria) return;

            foreach ($kategoria->ushtrimet as $u) {
                $this->ushtrimet[$u->id] = [
                    'emri'          => $u->emri,
                    'njesia_matese' => (int) $u->id_njesia_matese,
                    'checked'       => false,
                    'sets'          => [
                        ['reps' => '', 'pesha' => '', 'minuta' => '', 'km' => '', 'modaliteti' => 'dual']
                    ],
                ];
            }

            $this->modalHapur = true;
        }
    }

// Funksioni për të mbyllur modalin e kujdesit dhe pastruar variablen
    public function mbyll_modalKujdes(): void
    {
        $this->modal_pending = false;
        $this->stervitjaPezull = null;
    }

    /*public function mbyllModal(): void
    {
        $this->modalHapur = false;
        $this->reset(['kategoriaId', 'ushtrimet']);
        $this->data = now()->toDateString();
    }*/

    public function editoSeancen(int $id): void
    {
        $this->stervitjaId = $id;
        $stervitja = Stervitja::with(['ushtrimet.detaje', 'kategoria.ushtrimet'])->find($id);

        if (!$stervitja) return;

        $this->kategoriaId = $stervitja->kategoria_id;
        $this->data = $stervitja->data;
        $this->ushtrimet = [];

        // Matrica për të kthyer vlerat e gjuhës shqip nga DB në anglisht për HTML-në tënde
        $konvertimKrahuInvers = [
            'te_dyja'   => 'dual',
            'majtas'    => 'left',
            'djathtas'  => 'right',
            'asnjera'   => 'none',
        ];

        // 1. Mbledhim të gjitha ushtrimet që ka kjo kategori si template bazë
        foreach ($stervitja->kategoria->ushtrimet as $u) {
            $this->ushtrimet[$u->id] = [
                'emri'          => $u->emri,
                'njesia_matese' => (int) $u->id_njesia_matese,
                'checked'       => false,
                'sets'          => [
                    ['reps' => '', 'pesha' => '', 'minuta' => '', 'km' => '', 'modaliteti' => 'dual']
                ],
            ];
        }

        // 2. Mbushim vlerat reale që ka ruajtur përdoruesi në këtë seancë konkrete
        foreach ($stervitja->ushtrimet as $su) {
            if (isset($this->ushtrimet[$su->id_ushtrimit])) {
                $this->ushtrimet[$su->id_ushtrimit]['checked'] = true;
                $this->ushtrimet[$su->id_ushtrimit]['sets'] = [];

                foreach ($su->detaje as $detaj) {
                    $njesia = (int) $this->ushtrimet[$su->id_ushtrimit]['njesia_matese'];

                    $this->ushtrimet[$su->id_ushtrimit]['sets'][] = [
                        'reps'        => $njesia === 1 ? $detaj->reps : '',
                        'pesha'       => $njesia === 1 ? $detaj->pesha : '',
                        'minuta'      => $njesia === 2 ? ($detaj->kohezgjatja_sekonda ? $detaj->kohezgjatja_sekonda / 60 : '') : '',
                        'km'          => $njesia === 2 ? $detaj->distanca : '',
                        'modaliteti'  => $konvertimKrahuInvers[$detaj->krahu] ?? 'dual',
                    ];
                }
            }
        }

        $this->modalHapur = true;
    }

// 3. Përditëso metodën mbyllModal() që të fshijë edhe stervitjaId
    public function mbyllModal(): void
    {
        $this->modalHapur = false;
        $this->reset(['kategoriaId', 'ushtrimet', 'stervitjaId']);
        $this->data = now()->toDateString();
    }

// 4. Modifiko metodën ruaj() që të punojë edhe për update
    // Pranon statusin si parametër (0 = Progres, 1 = Përfunduar)
    public function ruaj(int $statusi = 0): void
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

        try {
            DB::beginTransaction();

            // 1. Ndryshimi: Përfshijmë edhe statusin që vjen nga butoni
            $stervitja = Stervitja::updateOrCreate(
                ['id' => $this->stervitjaId],
                [
                    'user_id'      => Auth::id(),
                    'kategoria_id' => $this->kategoriaId,
                    'data'         => $this->data,
                    'statusi'      => $statusi, // Ruhet 0 ose 1
                ]
            );

            // Pas ruajtjes së parë, mbajmë mend ID-në që herën tjetër të bëhet UPDATE dhe jo INSERT
            $this->stervitjaId = $stervitja->id;

            // Nëse po editojmë ose ruajmë progresin e radhës, fshijmë të vjetrat
            $vjetraIds = StervitjaUshtrimet::where('id_stervitjes', $stervitja->id)->pluck('id');
            StervitjaUshtrimetDetaje::whereIn('id_ushtrimit_exct', $vjetraIds)->delete();
            StervitjaUshtrimet::where('id_stervitjes', $stervitja->id)->delete();

            $konvertimKrahu = [
                'dual'  => 'te_dyja',
                'left'  => 'majtas',
                'right' => 'djathtas',
                'none'  => 'asnjera',
            ];

            foreach ($zgjedhur as $ushtrimiId => $detaje) {
                $su = StervitjaUshtrimet::create([
                    'id_stervitjes' => $stervitja->id,
                    'id_ushtrimit'  => $ushtrimiId,
                ]);

                $njesia = (int) $detaje['njesia_matese'];

                foreach ($detaje['sets'] as $set) {
                    $modalitetiHtml = $set['modaliteti'] ?? 'dual';
                    $vleraPerDatabaze = $konvertimKrahu[$modalitetiHtml] ?? 'te_dyja';

                    StervitjaUshtrimetDetaje::create([
                        'id_ushtrimit_exct'   => $su->id,
                        'reps'                => $njesia === 1 ? ($set['reps'] ?: 0) : 0,
                        'pesha'               => $njesia === 1 ? ($set['pesha'] ?: 0) : 0,
                        'kohezgjatja_sekonda' => $njesia === 2 ? (!empty($set['minuta']) ? (int)$set['minuta'] * 60 : null) : null,
                        'distanca'            => $njesia === 2 ? ($set['km'] ?: null) : null,
                        'krahu'               => $vleraPerDatabaze,
                    ]);
                }
            }

            DB::commit();

            // 2. Ndryshimi: Ndajmë sjelljen e mesazheve bazuar te statusi
            if ($statusi === 1) {
                // Nëse përfundon stërvitjen, mbyllet modali
                $this->mbyllModal();

                $this->dispatch('showSweetAlert', [
                    'type'    => 'success',
                    'title'   => 'U krye!',
                    'message' => 'Stërvitja u përfundua dhe u regjistrua me sukses.',
                ]);
            } else {
                // Nëse është thjesht ruajtje progresi, modali rri hapur
                $this->dispatch('showSweetAlert', [
                    'type'    => 'success',
                    'title'   => 'Progresi u ruajt!',
                    'message' => 'Të dhënat u azhurnuan pa e mbyllur stërvitjen.',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showSweetAlert', [
                'type'    => 'error',
                'title'   => 'Gabim!',
                'message' => 'Diçka shkoi gabim gjatë ruajtjes.',
            ]);
        }
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

    public function konfirmoFshirjen(int $id): void
    {
        $this->seancaPerFshirjeId = $id;
        $this->modal_fshirje = true; // Hap modalin
    }


// 2. Ky funksion thirret nëse përdoruesi klikon "Anulo"
    public function anuloFshirjen(): void
    {
        $this->modal_fshirje = false;
        $this->seancaPerFshirjeId = null;
    }

// 3. Ky funksion ekzekutohet kur klikohet "Po, fshije"
    public function fshiSeancen(): void
    {
        if (!$this->seancaPerFshirjeId) return;

        try {
            \DB::beginTransaction();

            $stervitja = Stervitja::findOrFail($this->seancaPerFshirjeId);

            $idUshtrimeve = StervitjaUshtrimet::where('id_stervitjes', $stervitja->id)->pluck('id');
            StervitjaUshtrimetDetaje::whereIn('id_ushtrimit_exct', $idUshtrimeve)->delete();
            StervitjaUshtrimet::where('id_stervitjes', $stervitja->id)->delete();
            $stervitja->delete();

            \DB::commit();

            $this->dispatch('showSweetAlert', [
                'type'    => 'success',
                'title'   => 'U fshi!',
                'message' => 'Seanca u fshi me sukses.',
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->dispatch('showSweetAlert', [
                'type'    => 'error',
                'title'   => 'Gabim!',
                'message' => 'Diçka shkoi gabim.',
            ]);
        }

        // Mbyllim modalin dhe pastrojmë ID-në
        $this->anuloFshirjen();
    }

    public function hapDetajetNgaData(string $data): void
    {
        $stervitja = Stervitja::where('user_id', Auth::id())
            ->whereDate('data', $data)
            ->first();

        if ($stervitja) {
            $this->shihDetajet($stervitja->id);
        }
    }


    public function updatedKategoriaId($value): void
    {
        $this->ushtrimet = [];

        $kategoria = Kategorite::with('ushtrimet')->find($value);
        if (!$kategoria) return;

        foreach ($kategoria->ushtrimet as $u) {
            $this->ushtrimet[$u->id] = [
                'emri'          => $u->emri,
                'njesia_matese' => (int) $u->id_njesia_matese,
                'checked'       => false,
                'sets'          => [
                    ['reps' => '', 'pesha' => '', 'minuta' => '', 'km' => '', 'modaliteti' => 'dual']
                ],
            ];
        }
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
            ->get();

        $historikuPaginate = Stervitja::with([
            'kategoria',
            'ushtrimet.ushtrimi.pjeset_e_trupit',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.stervitja.live-stervitja', [
            'kategorite'        => Kategorite::withCount('ushtrimet')->latest()->get(),
            'historiku'         => $historikuPaginate,
            'eventetKalendarit' => $this->getStervitjetPerKalendar($historiku),
            'stervitjaAktive'   => $this->stervitjaDetajeId
                ? Stervitja::with([
                    'kategoria',
                    'ushtrimet.ushtrimi',
                    'ushtrimet.detaje',
                ])->find($this->stervitjaDetajeId)
                : null,
        ])->layout('layouts.dashboard.app');
    }
}
