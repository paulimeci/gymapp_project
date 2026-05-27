<div>
    <div class="main-content-container overflow-hidden">

        {{-- KATEGORITE --}}
        <div class="row mb-2">
            @forelse($kategorite as $kategoria)
                @php
                    $colors = ['primary','danger','success','warning','info'];
                    $color  = $colors[$loop->index % count($colors)];
                @endphp
                <div class="col-xxl-2 col-xl-2 col-md-3 col-sm-4 col-6">
                    <div class="card border border-{{ $color }} border-opacity-25 rounded-3 mb-3 bg-{{ $color }} bg-opacity-10"
                         wire:click="hapModal({{ $kategoria->id }})"
                         style="cursor:pointer; transition: transform .15s, box-shadow .15s;"
                         onmouseover="this.style.transform='translateY(-3px)'"
                         onmouseout="this.style.transform='translateY(0)'">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex align-items-center gap-2 py-1">
                                <i class="material-symbols-outlined fs-22 text-{{ $color }}">fitness_center</i>
                                <span class="fs-13 fw-bold text-body">{{ $kategoria->emri }}</span>
                            </div>
                            <span class="fs-11 text-secondary fw-medium">{{ $kategoria->ushtrimet_count }} ushtrime</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 rounded-3 mb-4">
                        <div class="card-body p-5 text-center">
                            <i class="material-symbols-outlined fs-48 text-secondary mb-3 d-block">category</i>
                            <p class="text-secondary mb-3">Nuk ka kategori ende.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- MODAL --}}
        @if($modalHapur)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-3">

                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">
                                <i class="material-symbols-outlined fs-20 me-1 align-middle">fitness_center</i>
                                Regjistro Stërvitjen
                            </h5>
                            <button type="button" class="btn-close" wire:click="mbyllModal"></button>
                        </div>

                        <div class="modal-body pt-2">

                            {{-- Kategoria + Data --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium fs-13">Kategoria</label>
                                    <select class="form-select form-select-sm" wire:model.live="kategoriaId">
                                        @foreach($kategorite as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->emri }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium fs-13">Data</label>
                                    <input type="date" class="form-control form-control-sm" wire:model="data">
                                </div>
                            </div>

                            {{-- Gabim nëse asnjë i zgjedhur --}}
                            @error('ushtrimet')
                            <div class="alert alert-danger py-2 fs-13 mb-3">{{ $message }}</div>
                            @enderror

                            {{-- Lista e ushtrimeve --}}
                            <div class="fw-medium fs-13 mb-2 text-secondary">Ushtrimet</div>

                            @forelse($ushtrimet as $id => $detaje)
                                @php $njesia = (int) ($detaje['njesia_matese'] ?? 1); @endphp

                                <div class="card border rounded-3 mb-2 {{ $detaje['checked'] ? 'border-primary bg-primary bg-opacity-10' : '' }}"
                                     style="transition: all .2s;">
                                    <div class="card-body p-3">

                                        {{-- Checkbox + Emri --}}
                                        <div class="form-check mb-0">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="ush_{{ $id }}"
                                                   wire:model.live="ushtrimet.{{ $id }}.checked">
                                            <label class="form-check-label fw-medium fs-13" for="ush_{{ $id }}">
                                                {{ $detaje['emri'] }}
                                            </label>
                                        </div>

                                        {{-- Sets --}}
                                        @if($detaje['checked'])
                                            <div class="mt-3">

                                                {{-- Header i sets --}}
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fs-12 text-secondary fw-medium">
                                                @if($njesia === 1) Set &nbsp;·&nbsp; Reps &nbsp;·&nbsp; Pesha (kg) &nbsp;·&nbsp; Modaliteti
                                                @else Set &nbsp;·&nbsp; Minuta &nbsp;·&nbsp; Km &nbsp;·&nbsp; Modaliteti
                                                @endif
                                            </span>
                                                </div>

                                                @foreach($detaje['sets'] as $setIndex => $set)
                                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2 p-2 rounded bg-light bg-opacity-50">

                                                        {{-- Numri i setit --}}
                                                        <span class="fs-12 fw-bold text-secondary" style="min-width:24px; text-align:center;">
                                                    {{ $setIndex + 1 }}
                                                </span>

                                                        @if($njesia === 1)
                                                            <div class="input-group input-group-sm" style="width:110px">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="material-symbols-outlined fs-13">repeat</i>
                                                        </span>
                                                                <input type="number"
                                                                       class="form-control border-start-0"
                                                                       placeholder="Reps"
                                                                       min="0"
                                                                       wire:model="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.reps">
                                                            </div>
                                                            <div class="input-group input-group-sm" style="width:120px">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="material-symbols-outlined fs-13">monitor_weight</i>
                                                        </span>
                                                                <input type="number"
                                                                       class="form-control border-start-0"
                                                                       placeholder="kg"
                                                                       min="0"
                                                                       step="0.5"
                                                                       wire:model="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.pesha">
                                                            </div>

                                                        @elseif($njesia === 2)
                                                            <div class="input-group input-group-sm" style="width:120px">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="material-symbols-outlined fs-13">timer</i>
                                                        </span>
                                                                <input type="number"
                                                                       class="form-control border-start-0"
                                                                       placeholder="Minuta"
                                                                       min="0"
                                                                       wire:model="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.minuta">
                                                            </div>
                                                            <div class="input-group input-group-sm" style="width:120px">
                                                        <span class="input-group-text bg-transparent border-end-0">
                                                            <i class="material-symbols-outlined fs-13">directions_run</i>
                                                        </span>
                                                                <input type="number"
                                                                       class="form-control border-start-0"
                                                                       placeholder="Km"
                                                                       min="0"
                                                                       step="0.1"
                                                                       wire:model="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.km">
                                                            </div>
                                                        @endif

                                                        {{-- Zgjedhja e Anës (Dual, Left, Right, None) --}}
                                                        <div class="d-flex align-items-center gap-1 ms-md-auto" role="group" aria-label="Modaliteti">
                                                            @php
                                                                // Marrim vlerën nga Livewire. Nëse nuk ekziston (null/e zbrazët), supozojmë që është 'dual'
                                                                $vleraAktuale = $ushtrimet[$id]['sets'][$setIndex]['modaliteti'] ?? 'dual';
                                                            @endphp

                                                            <input type="radio" id="dual_{{ $id }}_{{ $setIndex }}" value="dual" wire:model.live="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.modaliteti" class="d-none">
                                                            <input type="radio" id="left_{{ $id }}_{{ $setIndex }}" value="left" wire:model.live="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.modaliteti" class="d-none">
                                                            <input type="radio" id="right_{{ $id }}_{{ $setIndex }}" value="right" wire:model.live="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.modaliteti" class="d-none">
                                                            <input type="radio" id="none_{{ $id }}_{{ $setIndex }}" value="none" wire:model.live="ushtrimet.{{ $id }}.sets.{{ $setIndex }}.modaliteti" class="d-none">

                                                            <label for="dual_{{ $id }}_{{ $setIndex }}"
                                                                   class="btn {{ $vleraAktuale === 'dual' ? 'btn-primary text-white' : 'btn-outline-primary' }} fw-medium py-1 px-2 fs-11 hover-white m-0"
                                                                   style="cursor: pointer;">
                                                                Dual
                                                            </label>

                                                            <label for="left_{{ $id }}_{{ $setIndex }}"
                                                                   class="btn {{ $vleraAktuale === 'left' ? 'btn-success text-white' : 'btn-outline-success' }} fw-medium py-1 px-2 fs-11 hover-white m-0"
                                                                   style="cursor: pointer;">
                                                                Left
                                                            </label>

                                                            <label for="right_{{ $id }}_{{ $setIndex }}"
                                                                   class="btn {{ $vleraAktuale === 'right' ? 'btn-warning text-dark' : 'btn-outline-warning' }} fw-medium py-1 px-2 fs-11 hover-white m-0"
                                                                   style="cursor: pointer;">
                                                                Right
                                                            </label>

                                                            <label for="none_{{ $id }}_{{ $setIndex }}"
                                                                   class="btn {{ $vleraAktuale === 'none' ? 'btn-dark text-white' : 'btn-outline-dark' }} fw-medium py-1 px-2 fs-11 hover-white m-0"
                                                                   style="cursor: pointer;">
                                                                None
                                                            </label>
                                                        </div>

                                                        {{-- Hiq setin --}}
                                                        @if(count($detaje['sets']) > 1)
                                                            <button wire:click="hiqSet({{ $id }}, {{ $setIndex }})"
                                                                    class="border-0 bg-transparent p-0 lh-1 ms-1">
                                                                <i class="material-symbols-outlined fs-16 text-danger">remove_circle</i>
                                                            </button>
                                                        @endif

                                                    </div>
                                                @endforeach

                                                {{-- Shto set --}}
                                                <button wire:click="shtoSet({{ $id }})"
                                                        class="btn btn-sm btn-outline-primary rounded-2 mt-1 py-1 px-2 fs-12">
                                                    <i class="material-symbols-outlined fs-14 align-middle">add</i>
                                                    Shto Set
                                                </button>

                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-secondary">
                                    <i class="material-symbols-outlined fs-36 d-block mb-2">sports_gymnastics</i>
                                    Kjo kategori nuk ka ushtrime ende.
                                </div>
                            @endforelse

                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button"
                                    class="btn btn-light btn-sm rounded-3"
                                    wire:click="mbyllModal">
                                Anulo
                            </button>
                            <button type="button"
                                    class="btn btn-primary btn-sm rounded-3"
                                    wire:click="ruaj">
                        <span wire:loading wire:target="ruaj"
                              class="spinner-border spinner-border-sm me-1"></span>
                                <i class="material-symbols-outlined fs-16 align-middle me-1">save</i>
                                Ruaj Stërvitjen
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL DETAJET --}}
        @if($modalDetaje && $stervitjaAktive)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-3">

                        <div class="modal-header border-0 pb-0">
                            <div>
                                <h5 class="modal-title fw-bold mb-1">
                                    <i class="material-symbols-outlined fs-20 me-1 align-middle">fitness_center</i>
                                    {{ $stervitjaAktive->kategoria?->emri ?? '—' }}
                                </h5>
                                <span class="fs-13 text-secondary">
                            {{ \Carbon\Carbon::parse($stervitjaAktive->data)->format('d M Y') }}
                        </span>
                            </div>
                            <button type="button" class="btn-close" wire:click="mbyllDetajet"></button>
                        </div>

                        <div class="modal-body pt-3">
                            @foreach($stervitjaAktive->ushtrimet as $su)
                                <div class="card border-0 rounded-3 mb-3 bg-light">
                                    <div class="card-body p-3">

                                        {{-- Emri i ushtrimit --}}
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="material-symbols-outlined fs-18 text-primary">fitness_center</i>
                                            <span class="fw-semibold fs-14">{{ $su->ushtrimi?->emri ?? '—' }}</span>
                                            @if($su->ushtrimi?->pjeset_e_trupit)
                                                <span class="fs-12 text-secondary ms-1">
                                            · {{ $su->ushtrimi->pjeset_e_trupit->emri }}
                                        </span>
                                            @endif
                                        </div>

                                        {{-- Sets --}}
                                        @php $njesia = (int) ($su->ushtrimi?->id_njesia_matese ?? 1); @endphp

                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fs-11 text-secondary fw-medium" style="min-width:30px">Set</span>
                                            @if($njesia === 1)
                                                <span class="fs-11 text-secondary fw-medium" style="min-width:80px">Reps</span>
                                                <span class="fs-11 text-secondary fw-medium">Pesha (kg)</span>
                                            @else
                                                <span class="fs-11 text-secondary fw-medium" style="min-width:80px">Minuta</span>
                                                <span class="fs-11 text-secondary fw-medium">Km</span>
                                            @endif
                                        </div>

                                        @foreach($su->detaje as $i => $det)
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fs-12 fw-bold text-secondary" style="min-width:30px">
                                            {{ $i + 1 }}
                                        </span>
                                                @if($njesia === 1)
                                                    <span class="fs-13 fw-medium" style="min-width:80px">
                                                {{ $det->reps }} reps
                                            </span>
                                                    <span class="fs-13 fw-medium">
                                                {{ $det->pesha }} kg
                                            </span>
                                                @else
                                                    <span class="fs-13 fw-medium" style="min-width:80px">
                                                {{ $det->kohezgjatja_sekonda ? round($det->kohezgjatja_sekonda / 60) : '—' }} min
                                            </span>
                                                    <span class="fs-13 fw-medium">
                                                {{ $det->distanca ?? '—' }} km
                                            </span>
                                                @endif
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button"
                                    class="btn btn-light btn-sm rounded-3"
                                    wire:click="mbyllDetajet">
                                Mbyll
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- Modali i Konfirmimit të Fshirjes --}}
        @if($modal_fshirje)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1055;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-3 shadow">

                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-danger">
                                <i class="material-symbols-outlined align-middle me-1">warning</i>
                                Konfirmo Fshirjen
                            </h5>
                            <button type="button" class="btn-close" wire:click="anuloFshirjen()"></button>
                        </div>

                        <div class="modal-body py-3">
                            <p class="mb-0 text-secondary">A jeni i sigurt që dëshironi të fshini këtë seancë stërvitore? Ky veprim do të fshijë përgjithmonë të gjitha ushtrimet dhe setet e regjistruara për këtë ditë dhe nuk mund të kthehet pas.</p>
                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm rounded-2" wire:click="anuloFshirjen()">
                                Anulo
                            </button>
                            <button type="button" class="btn btn-danger btn-sm rounded-2 text-white" wire:click="fshiSeancen">
                                <i class="material-symbols-outlined fs-16 align-middle me-1">delete</i>
                                Po, fshije
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xxl-8">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-4">
                            <h5 class="mb-0 fw-semibold">Historiku i Stërvitjeve</h5>
                        </div>

                        <div class="default-table-area style-two">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Kategoria</th>
                                        <th scope="col">Human Body</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($historiku as $s)
                                        <tr>
                                            <td class="text-secondary fs-13">{{ $historiku->firstItem() + $loop->index }}</td>
                                            <td class="fs-13 fw-medium">
                                                {{ \Carbon\Carbon::parse($s->data)->format('d M Y') }}
                                            </td>
                                            <td>
                                                @php
                                                    $katColors = [
                                                        'push' => 'primary',
                                                        'pull' => 'danger',
                                                        'legs' => 'success',
                                                        'mix'  => 'warning',
                                                    ];
                                                    $katColor = $katColors[strtolower($s->kategoria?->emri ?? '')] ?? 'secondary';
                                                @endphp
                                                <span class="fs-13 fw-medium text-{{ $katColor }}">
                                            {{ $s->kategoria?->emri ?? '—' }}
                                        </span>
                                            </td>
                                            <td>
                                                @php
                                                    $pjeset = $s->ushtrimet
                                                        ->map(fn($su) => $su->ushtrimi?->pjeset_e_trupit)
                                                        ->filter()
                                                        ->unique('id');
                                                @endphp
                                                <div class="d-flex flex-wrap gap-1">
                                                    @forelse($pjeset as $p)
                                                        <span class="fs-12 fw-medium text-secondary">{{ $p->emri }}</span>
                                                        @if(!$loop->last)<span class="text-secondary">,</span>@endif
                                                    @empty
                                                        <span class="text-secondary fs-13">—</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button wire:click="shihDetajet({{ $s->id }})"
                                                            class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                        <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                    </button>
                                                    <button wire:click="editoSeancen({{ $s->id }})"
                                                            class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                        <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                    </button>
                                                    <div wire:key="seanca-{{ $s->id }}">

                                                        <button type="button" wire:click="konfirmoFshirjen({{ $s->id }})"
                                                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                            <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                        </button>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-secondary">
                                                <i class="material-symbols-outlined fs-48 d-block mb-2">fitness_center</i>
                                                Nuk ka stërvitje të regjistruara ende.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- PAGINATION --}}
                            @if($historiku->hasPages())
                                <div class="d-flex justify-content-center justify-content-sm-between align-items-center
                                text-center flex-wrap gap-2 p-4">
                        <span class="fs-12 fw-medium">
                            Duke shfaqur {{ $historiku->firstItem() }}–{{ $historiku->lastItem() }}
                            nga {{ $historiku->total() }} rezultate
                        </span>
                                    <nav aria-label="Pagination">
                                        <ul class="pagination mb-0 justify-content-center">
                                            {{-- Previous --}}
                                            <li class="page-item {{ $historiku->onFirstPage() ? 'disabled' : '' }}">
                                                <button class="page-link icon"
                                                        wire:click="previousPage"
                                                    {{ $historiku->onFirstPage() ? 'disabled' : '' }}>
                                                    <i class="material-symbols-outlined">keyboard_arrow_left</i>
                                                </button>
                                            </li>

                                            {{-- Faqet --}}
                                            @foreach($historiku->getUrlRange(1, $historiku->lastPage()) as $page => $url)
                                                <li class="page-item {{ $page == $historiku->currentPage() ? 'active' : '' }}">
                                                    <button class="page-link" wire:click="gotoPage({{ $page }})">
                                                        {{ $page }}
                                                    </button>
                                                </li>
                                            @endforeach

                                            {{-- Next --}}
                                            <li class="page-item {{ !$historiku->hasMorePages() ? 'disabled' : '' }}">
                                                <button class="page-link icon"
                                                        wire:click="nextPage"
                                                    {{ !$historiku->hasMorePages() ? 'disabled' : '' }}>
                                                    <i class="material-symbols-outlined">keyboard_arrow_right</i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3 mb-lg-4">
                            <h3 class="mb-0">Working Schedule</h3>
                        </div>
                        <div class="calendar-wraps">
                            <div id="calendari"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-medium">Upcoming Events:</span>
                            <div class="swiper-pagination1 text-end" style="width: 100px;"></div>
                        </div>

                        <div class="swiper upcoming-events-slide">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide cursor">
                                    <div class="position-relative d-flex">
                                        <span class="wh-11 bg-primary rounded-1 d-inline-block position-relative top-1"></span>
                                        <div>
                                            <h4 class="fs-12 fw-semibold text-secondary mb-0 ms-1"> Pythons Unleashed: A Development Expedition</h4>
                                            <p class="fs-12"><span class="text-primary">April 15, 2024</span> -  12.00 PM - 6.00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide cursor">
                                    <div class="position-relative d-flex">
                                        <span class="wh-11 bg-primary rounded-1 d-inline-block position-relative top-1"></span>
                                        <div>
                                            <h4 class="fs-12 fw-semibold text-secondary mb-0 ms-1"> Big Data Analytics</h4>
                                            <p class="fs-12"><span class="text-primary">15 Mar 2024</span> -  01.00 PM - 7.00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide cursor">
                                    <div class="position-relative d-flex">
                                        <span class="wh-11 bg-primary rounded-1 d-inline-block position-relative top-1"></span>
                                        <div>
                                            <h4 class="fs-12 fw-semibold text-secondary mb-0 ms-1">Introduction to Blockchain</h4>
                                            <p class="fs-12"><span class="text-primary">10 Mar 2024</span> -  02.00 PM - 9.00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1"></div>
</div>

