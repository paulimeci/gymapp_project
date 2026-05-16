<?php

namespace App\Livewire\Admin;

use App\Models\Structure\Kategorite;
use App\Models\Structure\Ushtrimet;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        ];
    }

    public ?int $filterKatId = null;

    public function filtroKat(?int $id): void
    {
        // klik përsëri mbi të njëjtën → hiq filtrin
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
            ['emri' => $this->kat_emri, 'pershkrimi' => $this->kat_pershkrimi]
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
        $this->reset(['ush_emri', 'ush_pershkrimi', 'kategoritEZgjedhura', 'editingUshId']);
        $this->resetErrorBag();
        $this->showUshModal = true;
    }

    public function editUsh(Ushtrimet $ushtrimi): void
    {
        $this->editingUshId           = $ushtrimi->id;
        $this->ush_emri               = $ushtrimi->emri;
        $this->ush_pershkrimi         = $ushtrimi->pershkrimi ?? '';
        $this->kategoritEZgjedhura    = $ushtrimi->kategorite->pluck('id')->toArray();
        $this->resetErrorBag();
        $this->showUshModal           = true;
    }

    public function saveUsh(): void
    {
        $this->validateOnly('ush_emri');
        $this->validateOnly('ush_pershkrimi');
        $this->validateOnly('kategoritEZgjedhura');

        $ushtrimi = Ushtrimet::updateOrCreate(
            ['id' => $this->editingUshId],
            ['emri' => $this->ush_emri, 'pershkrimi' => $this->ush_pershkrimi]
        );

        $ushtrimi->kategorite()->sync($this->kategoritEZgjedhura);

        $this->reset(['ush_emri', 'ush_pershkrimi', 'kategoritEZgjedhura', 'showUshModal', 'editingUshId']);
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
            'kategorite' => Kategorite::withCount('ushtrimet')->latest()->get(),
            'ushtrimet'  => $ushtrimet,
        ])->layout('layouts.dashboard.app');
    }
}
