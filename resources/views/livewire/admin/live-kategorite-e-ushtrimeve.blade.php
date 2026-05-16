<div>
    @section('page-title', 'Ushtrimet & Kategoritë')

    @section('breadcrumb')
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active"><span class="fw-medium">Ushtrimet & Kategoritë</span></li>
            </ol>
        </nav>
    @endsection

    {{-- ═══════════════════════════════════════
         SEKSIONI I KATEGORIVE
    ════════════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1 fw-semibold">Kategoritë</h5>
            <p class="text-secondary fs-13 mb-0">Push, Pull, Legs, Upper, Lower...</p>
        </div>
        <button wire:click="openKatModal" class="btn btn-primary rounded-3">
            <i class="ri-add-line me-1"></i> Shto Kategori
        </button>
    </div>

    <div class="row mb-2">
        @forelse($kategorite as $kategoria)
            @php
                $colors = ['primary','danger','success','warning','info'];
                $color  = $colors[$loop->index % count($colors)];
                $aktive = $filterKatId === $kategoria->id;
            @endphp
            <div class="col-xxl-2 col-xl-2 col-md-3 col-sm-4 col-6">
                <div class="card border-0 rounded-3 mb-3 bg-{{ $color }} bg-opacity-10
                            {{ $aktive ? 'border border-' . $color . ' shadow-sm' : '' }}"
                     wire:click="filtroKat({{ $kategoria->id }})"
                     style="cursor:pointer; transition: transform .15s, box-shadow .15s;
                            {{ $aktive ? 'transform:translateY(-3px)' : '' }}">
                    <div class="card-body p-2 px-3">
                        <div class="d-flex justify-content-end gap-1 mb-1">
                            <button wire:click.stop="editKat({{ $kategoria->id }})"
                                    class="border-0 bg-transparent p-0 lh-1">
                                <i class="material-symbols-outlined fs-14 text-body">edit</i>
                            </button>
                            <button wire:click.stop="deleteKat({{ $kategoria->id }})"
                                    wire:confirm="Fshij kategorinë '{{ $kategoria->emri }}'?"
                                    class="border-0 bg-transparent p-0 lh-1">
                                <i class="material-symbols-outlined fs-14 text-danger">delete</i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2 py-1">
                            <i class="material-symbols-outlined fs-22 text-{{ $color }}">fitness_center</i>
                            <span class="fs-13 fw-bold text-secondary">{{ $kategoria->emri }}</span>
                            @if($aktive)
                                <i class="material-symbols-outlined fs-13 text-{{ $color }} ms-auto">check_circle</i>
                            @endif
                        </div>
                        <span class="fs-11 text-secondary">{{ $kategoria->ushtrimet_count }} ushtrime</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 rounded-3 mb-4">
                    <div class="card-body p-5 text-center">
                        <i class="material-symbols-outlined fs-48 text-secondary mb-3 d-block">category</i>
                        <p class="text-secondary mb-3">Nuk ka kategori ende.</p>
                        <button wire:click="openKatModal" class="btn btn-primary rounded-3">
                            <i class="ri-add-line me-1"></i> Shto kategorinë e parë
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════
         SEKSIONI I USHTRIMEVE
    ════════════════════════════════════════ --}}
    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0 fw-semibold">Ushtrimet</h5>
                    @if($filterKatId)
                        @php $katAktive = $kategorite->firstWhere('id', $filterKatId); @endphp
                        @if($katAktive)
                            <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill px-3 py-2 fs-13">
                                {{ $katAktive->emri }}
                                <button wire:click="filtroKat(null)"
                                        class="border-0 bg-transparent p-0 ms-1 lh-1 text-primary"
                                        title="Hiq filtrin">
                                    <i class="material-symbols-outlined fs-14" style="vertical-align:middle">close</i>
                                </button>
                            </span>
                        @endif
                    @endif
                </div>
                <button wire:click="openUshModal"
                        class="btn btn-outline-primary py-1 px-4 fs-14 fw-medium rounded-3 hover-bg">
                    <i class="ri-add-line"></i> Shto Ushtrim
                </button>
            </div>

            <div class="default-table-area">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Emri</th>
                            <th scope="col">Kategoritë</th>
                            <th scope="col">Përshkrimi</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ushtrimet as $ushtrimi)
                            <tr>
                                <td class="text-secondary fs-13">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined fs-20 text-primary">fitness_center</i>
                                        <span class="fw-medium fs-14">{{ $ushtrimi->emri }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($ushtrimi->kategorite as $k)
                                            @php
                                                $colors = ['primary','danger','success','warning','info'];
                                                $color  = $colors[$loop->index % count($colors)];
                                            @endphp
                                            <span class="badge bg-{{ $color }} bg-opacity-15 text-{{ $color }} rounded-pill px-2 py-1 fs-12">
                                                    {{ $k->emri }}
                                                </span>
                                        @empty
                                            <span class="text-secondary fs-13">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-secondary fs-13">{{ $ushtrimi->pershkrimi ?: '—' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button wire:click="editUsh({{ $ushtrimi->id }})"
                                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                            <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                        </button>
                                        <button wire:click="deleteUsh({{ $ushtrimi->id }})"
                                                wire:confirm="Fshij ushtrimin '{{ $ushtrimi->emri }}'?"
                                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                            <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="material-symbols-outlined fs-48 d-block mb-2">fitness_center</i>
                                    {{ $filterKatId ? 'Nuk ka ushtrime për këtë kategori.' : 'Nuk ka ushtrime ende.' }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         MODAL — KATEGORIA
    ════════════════════════════════════════ --}}
    @if($showKatModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">
                            {{ $editingKatId ? 'Edito Kategorinë' : 'Shto Kategori të Re' }}
                        </h5>
                        <button wire:click="$set('showKatModal', false)" class="btn-close" type="button"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Emri <span class="text-danger">*</span></label>
                            <input wire:model="kat_emri" type="text"
                                   class="form-control rounded-2 @error('kat_emri') is-invalid @enderror"
                                   placeholder="p.sh. Push, Pull, Legs...">
                            @error('kat_emri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-medium">Përshkrimi</label>
                            <textarea wire:model="kat_pershkrimi" rows="3"
                                      class="form-control rounded-2"
                                      placeholder="Përshkrim i shkurtër..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-2">
                        <button wire:click="$set('showKatModal', false)"
                                class="btn btn-outline-secondary rounded-2">Anulo</button>
                        <button wire:click="saveKat" class="btn btn-primary rounded-2">
                            <span wire:loading wire:target="saveKat"
                                  class="spinner-border spinner-border-sm me-1"></span>
                            {{ $editingKatId ? 'Ruaj Ndryshimet' : 'Shto Kategorinë' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════
         MODAL — USHTRIMI
    ════════════════════════════════════════ --}}
    @if($showUshModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">
                            {{ $editingUshId ? 'Edito Ushtrimin' : 'Shto Ushtrim të Ri' }}
                        </h5>
                        <button wire:click="$set('showUshModal', false)" class="btn-close" type="button"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Emri <span class="text-danger">*</span></label>
                            <input wire:model="ush_emri" type="text"
                                   class="form-control rounded-2 @error('ush_emri') is-invalid @enderror"
                                   placeholder="p.sh. Lat Pulldown, Romanian Deadlift...">
                            @error('ush_emri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Përshkrimi</label>
                            <textarea wire:model="ush_pershkrimi" rows="2"
                                      class="form-control rounded-2"
                                      placeholder="Përshkrim i shkurtër..."></textarea>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-medium d-block mb-2">Kategoritë</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($kategorite as $k)
                                    @php
                                        $colors = ['primary','danger','success','warning','info'];
                                        $color  = $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="checkbox"
                                               wire:model="kategoritEZgjedhura"
                                               value="{{ $k->id }}" id="kat_{{ $k->id }}">
                                        <label class="form-check-label badge bg-{{ $color }} bg-opacity-15 text-{{ $color }} rounded-pill px-2 py-1 fs-13"
                                               style="cursor:pointer" for="kat_{{ $k->id }}">
                                            {{ $k->emri }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-2">
                        <button wire:click="$set('showUshModal', false)"
                                class="btn btn-outline-secondary rounded-2">Anulo</button>
                        <button wire:click="saveUsh" class="btn btn-primary rounded-2">
                            <span wire:loading wire:target="saveUsh"
                                  class="spinner-border spinner-border-sm me-1"></span>
                            {{ $editingUshId ? 'Ruaj Ndryshimet' : 'Shto Ushtrimin' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
