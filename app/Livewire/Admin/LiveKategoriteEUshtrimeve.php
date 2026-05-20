<?php

namespace App\Livewire\Admin;

use App\Models\Human\PjesetETrupit;
use App\Models\Structure\Kategorite;
use App\Models\Structure\NjesiaMatese;
use App\Models\Structure\Ushtrimet;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LiveKategoriteEUshtrimeve extends Component
{
    // ── KATEGORITË ──────────────────────────────
    public string $kat_emri       = '';
    public string $kat_pershkrimi = '';
    public bool   $showKatModal   = false;
    public ?int   $editingKatId   = null;

    // ── USHTRIMET ───────────────────────────────
    public string $ush_emri             = '';
    public string $ush_pershkrimi       = '';
    public array  $kategoritEZgjedhura  = [];
    public bool   $showUshModal         = false;
    public ?int   $editingUshId         = null;

    // RREGULLIM: Njësia matëse
    public ?int $id_njesia_matese       = null;

    // SHTIMI: Variabli për pjesën e trupit të zgjedhur
    public ?int $id_pjesa_trupit        = null;

    protected function rules(): array
    {
        return [
            // kategori
            'kat_emri'       => 'required|string|max:100',
            'kat_pershkrimi' => 'nullable|string|max:255',

            // ushtrim
            'ush_emri'              => 'required|string|max:100',
            'ush_pershkrimi'        => 'nullable|string|max:255',
            'kategoritEZgjedhura'   => 'array',
            'kategoritEZgjedhura.*' => 'exists:excs_kategorite,id',

            // RREGULLIM: Ndryshuar nga id_njesia_matese në emrin e saktë të tabelës (p.sh. njesite_matese ose njesia_matese)
            'id_njesia_matese'      => 'required|exists:njesia_matese,id',

            // RREGULLIM: Validimi për pjesën e trupit sipas tabelës së saktë (p.sh. pjeset_e_trupit)
            'id_pjesa_trupit'       => 'required|exists:pjeset_e_trupit,id',
        ];
    }

    public ?int $filterKatId = null;

    public function filtroKat(?int $id): void
    {
        $this->filterKatId = ($this->filterKatId === $id) ? null : $id;
    }

    // ── KATEGORITË — metodat ────────────────────
    public function openKatModal(): void
    {
        $this->reset(['kat_emri', 'kat_pershkrimi', 'editingKatId']);
        $this->resetErrorBag();
        $this->showKatModal = true;
    }

    public function editKat(Kategorite $kategoria): void
    {
        $this->editingKatId   = $kategoria->id;
        $this->kat_emri       = $kategoria->emri;
        $this->kat_pershkrimi = $kategoria->pershkrimi ?? '';
        $this->resetErrorBag();
        $this->showKatModal   = true;
    }

    public function saveKat(): void
    {
        $this->validateOnly('kat_emri');
        $this->validateOnly('kat_pershkrimi');

        Kategorite::updateOrCreate(
            ['id' => $this->editingKatId],
            ['user_id' => Auth::user()->id, 'emri' => $this->kat_emri, 'pershkrimi' => $this->kat_pershkrimi]
        );

        $this->reset(['kat_emri', 'kat_pershkrimi', 'showKatModal', 'editingKatId']);
    }

    public function deleteKat(Kategorite $kategoria): void
    {
        $kategoria->ushtrimet()->detach();
        $kategoria->delete();
    }

    // ── USHTRIMET — metodat ─────────────────────
    public function openUshModal(): void
    {
        // SHTIMI: Shtuar id_pjesa_trupit te resetimi kur hapet modal i ri
        $this->reset(['ush_emri', 'ush_pershkrimi', 'kategoritEZgjedhura', 'editingUshId', 'id_njesia_matese', 'id_pjesa_trupit']);
        $this->resetErrorBag();
        $this->showUshModal = true;
    }

    public function editUsh(Ushtrimet $ushtrimi): void
    {
        $this->editingUshId           = $ushtrimi->id;
        $this->ush_emri               = $ushtrimi->emri;
        $this->ush_pershkrimi         = $ushtrimi->pershkrimi ?? '';
        $this->kategoritEZgjedhura    = $ushtrimi->kategorite->pluck('id')->toArray();
        $this->id_njesia_matese       = $ushtrimi->id_njesia_matese;
        $this->id_pjesa_trupit        = $ushtrimi->id_pjeses_trupit;


        $this->resetErrorBag();
        $this->showUshModal           = true;
    }

    public function saveUsh(): void
    {
        $this->validateOnly('ush_emri');
        $this->validateOnly('ush_pershkrimi');
        $this->validateOnly('kategoritEZgjedhura');
        /*$this->validateOnly('id_njesia_matese');*/

        // SHTIMI: Validimi specifik për dropdown-in e pjesës së trupit
       /* $this->validateOnly('id_pjesa_trupit');*/

        // SHTIMI: Shtuar 'id_pjesa_trupit' brenda array-it të ruajtjes/përditësimit
        $ushtrimi = Ushtrimet::updateOrCreate(
            ['id' => $this->editingUshId],
            [
                'user_id'          => Auth::user()->id,
                'emri'             => $this->ush_emri,
                'pershkrimi'       => $this->ush_pershkrimi,
                'id_njesia_matese' => $this->id_njesia_matese,
                'id_pjeses_trupit' => $this->id_pjesa_trupit // <-- Këtu: kolona në DB është 'id_pjeses_trupit', variabli i Livewire është $id_pjesa_trupit
            ]
        );

        $syncData = [];
        foreach ($this->kategoritEZgjedhura as $id) {
            $syncData[$id] = ['user_id' => Auth::id()];
        }

        $ushtrimi->kategorite()->sync($syncData);

        // SHTIMI: Shtuar id_pjesa_trupit te resetimi pas ruajtjes së suksesshme
        $this->reset(['ush_emri', 'ush_pershkrimi', 'kategoritEZgjedhura', 'showUshModal', 'editingUshId', 'id_njesia_matese', 'id_pjesa_trupit']);
    }

    public function deleteUsh(Ushtrimet $ushtrimi): void
    {
        $ushtrimi->kategorite()->detach();
        $ushtrimi->delete();
    }

    // ── RENDER ──────────────────────────────────
    public function render()
    {
        $ushtrimet = Ushtrimet::with('kategorite')
            ->when($this->filterKatId, fn($q) =>
            $q->whereHas('kategorite', fn($q2) =>
            $q2->where('excs_kategorite.id', $this->filterKatId)
            )
            )
            ->latest()
            ->get();

        return view('livewire.admin.live-kategorite-e-ushtrimeve', [
            'kategorite'    => Kategorite::withCount('ushtrimet')->latest()->get(),
            'njesia_matese' => NjesiaMatese::get(),
            'pjeset_trupit' => PjesetETrupit::all(),
            'ushtrimet'     => $ushtrimet,
        ])->layout('layouts.dashboard.app');
    }
}
