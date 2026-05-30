<div>
    @if($modalMatjeHapur)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3 shadow">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="material-symbols-outlined text-primary fs-24">monitoring</i>
                            Regjistro Matjet Fizike
                        </h5>
                        <button type="button" class="btn-close" wire:click="mbyllModalMatje"></button>
                    </div>

                    <div class="modal-body py-3">
                        <form wire:submit.prevent="ruajMatjet">

                            <div class="mb-3">
                                <label class="form-label fw-medium fs-13">Data e Matjes</label>
                                <input type="date" class="form-control form-control-sm @error('data') is-invalid @enderror" wire:model="data">
                                @error('data') <div class="invalid-feedback fs-12">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium fs-13">Pesha</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.1" min="0" class="form-control @error('pesha') is-invalid @enderror" placeholder="0.0" wire:model="pesha">
                                        <select class="form-select border-start-0" style="max-width: 75px;" wire:model.live="njesiaPeshes">
                                            <option value="kg">kg</option>
                                            <option value="lbs">lbs</option>
                                        </select>
                                        @error('pesha') <div class="invalid-feedback fs-12 d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="form-label fw-medium fs-13">Gjatësia</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.1" min="0" class="form-control @error('gjatesia') is-invalid @enderror" placeholder="0.0" wire:model="gjatesia">
                                        <select class="form-select border-start-0" style="max-width: 75px;" wire:model.live="njesiaGjatesise">
                                            <option value="cm">cm</option>
                                            <option value="in">in</option>
                                        </select>
                                        @error('gjatesia') <div class="invalid-feedback fs-12 d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm rounded-2" wire:click="mbyllModalMatje">
                            Anulo
                        </button>
                        <button type="button" class="btn btn-primary btn-sm rounded-2 px-3" wire:click="ruajMatjet">
                            <span wire:loading wire:target="ruajMatjet" class="spinner-border spinner-border-sm me-1"></span>
                            <i class="material-symbols-outlined fs-16 align-middle me-1">add_circle</i>
                            Shto Matjet
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    <body class="boxed-size">

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card bg-white border-0 rounded-3 mb-4 kanban-for-dark">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="mb-0 fs-16 fw-bold">To Do</h4>

                                <div class="dropdown action-opt position-relative" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="More Option">
                                    <button class="p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="material-symbols-outlined fs-20 text-body hover">more_horiz</i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end bg-white border box-shadow">
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i data-feather="eye"></i> View All</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i data-feather="edit"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i data-feather="trash-2"></i> Delete One</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);"><i data-feather="lock"></i> Block</a></li>
                                    </ul>
                                </div>
                            </div>

                            <button class="btn btn-primary d-flex align-items-center gap-1 py-2 px-3 rounded-3 fs-14 fw-medium" wire:click="hapModalMatje">
                                <i class="material-symbols-outlined fs-18">scale</i>
                                <span>Shto Matje të Reja</span>
                            </button>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">

                            @forelse($this->historikuMatjeve as $matja)
                                <!-- Çdo ditë është një box-buton më vete -->
                                <div class="col">
                                    <button type="button"
                                            wire:click="ShfaqDetajetMatjes({{ $matja['id'] }})"
                                            class="w-100 h-100 text-start border-0 rounded-3 p-3 d-flex flex-column justify-content-between position-relative shadow-sm transition-hover"
                                            style="background-color: {{ $matja['bg_kutise'] }}; min-height: 160px; transition: transform 0.2s, box-shadow 0.2s;">

                                        <!-- Rreshti i Parë: Data dhe Ikona -->
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fs-12 fw-bold text-secondary text-uppercase tracking-wider">
                            {{ $matja['data'] }}
                        </span>
                                                <i class="material-symbols-outlined fs-18 text-{{ $matja['klasa_ngjyres'] }}">event_available</i>
                                            </div>

                                            <!-- Pjesa Qendrore: Pesha dhe Gjatësia -->
                                            <div class="row g-0 my-2 py-2 border-top border-bottom border-black-50 border-opacity-10">
                                                <div class="col-6 border-end border-black-50 border-opacity-10">
                                                    <small class="text-muted d-block fs-11">Peshë</small>
                                                    <span class="fs-14 fw-bold text-dark">{{ $matja['pesha'] }}</span>
                                                </div>
                                                <div class="col-6 ps-2">
                                                    <small class="text-muted d-block fs-11">Gjatësi</small>
                                                    <span class="fs-14 fw-bold text-dark">{{ $matja['gjatesia'] }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rreshti i Fundit: BMI dhe Statusi -->
                                        <div class="w-100 d-flex justify-content-between align-items-center mt-2 pt-1">
                                            <div>
                                                <small class="text-muted d-block fs-11">BMI</small>
                                                <span class="fs-15 fw-black text-{{ $matja['klasa_ngjyres'] }}">{{ $matja['bmi'] }}</span>
                                            </div>
                                            <span class="badge bg-{{ $matja['klasa_ngjyres'] }} bg-opacity-10 text-{{ $matja['klasa_ngjyres'] }} fs-11 px-2 py-1 rounded">
                        {{ $matja['statusi'] }}
                    </span>
                                        </div>

                                    </button>
                                </div>
                            @empty
                                <!-- Nëse tabela është bosh -->
                                <div class="col-12 w-100">
                                    <div class="text-center py-5 text-secondary bg-light rounded-3 border border-dashed">
                                        <i class="material-symbols-outlined fs-40 d-block mb-2 text-muted">history_toggle_off</i>
                                        Nuk u gjet asnjë ditë e regjistruar në historik.
                                    </div>
                                </div>
                            @endforelse

                        </div>

                        <!-- Stili i thjeshtë CSS për t'i dhënë efektin e butonit kur kalohet mausi sipër -->
                        <style>
                            .transition-hover:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important;
                                cursor: pointer;
                            }
                        </style>
                    </div>
            </div>
        </div>

        <div class="flex-grow-1"></div>
    </div>
</div>
