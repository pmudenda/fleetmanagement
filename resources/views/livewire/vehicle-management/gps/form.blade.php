<div class="container-fluid py-3">

    {{-- Page Title (like screenshot) --}}
    <h3 class="fw-bold mb-3">
        {{ $isEdit ? 'Edit GPS Device' : 'Add GPS Device' }}
    </h3>

    <div class="row">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    {{-- Top meta (like DOC NUMBER / VERSION section) --}}
                    <div class="mb-6">
                        <div class="text-uppercase fw-bold small">STATUS:</div>
                        <div class="text-muted">{{ $isEdit ? 'Editing' : 'New' }}</div>

                        <div class="text-uppercase fw-bold small mt-6">INFO:</div>
                        <div class="text-muted">Capture device details and link to a vehicle registration.</div>
                    </div>

                    <hr class="my-4">

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 mb-3">
                            <strong>Action required:</strong> Please fix the errors highlighted below.
                        </div>
                    @endif

                    {{-- Form fields (STACKED FULL WIDTH like screenshot) --}}
                    <div class="row g-6">
                        {{-- Model --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">Model</label>
                            <select class="form-select" wire:model.defer="model">
                                <option value="">-- Select Model --</option>
                                <option value="FMB120">FMB120</option>
                                <option value="FMB920">FMB920</option>
                            </select>
                            @error('model') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Device Type --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">Device Type</label>
                            <select class="form-select" wire:model.defer="type">
                                <option value="">-- Select Device Type --</option>
                                <option value="Teltonika">Teltonika</option>
                                <option value="Concox">Concox</option>
                                <option value="Queclink">Queclink</option>
                                <option value="CalAmp">CalAmp</option>
                                <option value="Ruptela">Ruptela</option>
                                <option value="SinoTrack">SinoTrack</option>
                                <option value="Meitrack">Meitrack</option>
                                <option value="Trackimo">Trackimo</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- IMEI --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">IMEI <span class="text-muted">(15 digits)</span></label>
                            <input
                                    type="text"
                                    class="form-control"
                                    wire:model.defer="imei"
                                    placeholder="353691845900000"
                                    inputmode="numeric"
                                    pattern="[0-9]{15}"
                                    minlength="15"
                                    maxlength="15"
                                    autocomplete="off"
                                    oninput="this.value=this.value.replace(/\D/g,'')"
                                    {{ $isEdit ? 'readonly' : '' }}
                            >
                            @error('imei') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Serial --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">Device Serial <span class="text-muted">(9–10 digits)</span></label>
                            <input
                                    type="text"
                                    class="form-control"
                                    wire:model.defer="serial"
                                    placeholder="Enter 9 or 10 digit serial"
                                    inputmode="numeric"
                                    pattern="[0-9]{9,10}"
                                    minlength="9"
                                    maxlength="10"
                                    autocomplete="off"
                                    oninput="this.value=this.value.replace(/\D/g,'')"
                            >
                            @error('serial') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- REG Number (search + dropdown) --}}
                        <div class="col-6 position-relative">
                            <label class="form-label fw-semibold">REG Number</label>

                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input
                                        type="text"
                                        class="form-control
                                        @if($regNotFound) is-invalid
                                        @elseif($regIsValid && $regSearch) is-valid
                                        @endif"
                                        wire:model.live.debounce.300ms="regSearch"
                                        placeholder="Type registration number..."
                                        autocomplete="off"
                                        {{ $isEdit ? 'readonly' : '' }}
                                >
                            </div>

                            {{-- keep actual value --}}
                            <input type="hidden" wire:model.defer="reg_number">

                            @error('reg_number')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                            {{-- Show hint when editing --}}
                            @if($isEdit)
                                <small class="text-muted d-block mt-1">
                                    Registration number cannot be changed after onboarding.
                                </small>
                            @endif

                            {{-- Dropdown only for CREATE --}}
                            @if(!$isEdit && $showRegDropdown && !empty($regSuggestions))
                                <div class="list-group position-absolute w-100 shadow-sm mt-1"
                                     style="z-index: 2000; max-height: 240px; overflow:auto; border-radius: .5rem;">
                                    @foreach($regSuggestions as $reg)
                                        <button
                                                type="button"
                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                wire:click="selectReg('{{ $reg }}')"
                                        >
                                            <span>{{ $reg }}</span>
                                            <span class="badge bg-light text-dark">Select</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    wire:model.defer="mobile_number"
                                    placeholder="+260975000000"
                                    inputmode="tel"
                                    maxlength="16"
                                    autocomplete="off"
                            >
                            @error('mobile_number') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted d-block mt-1">Allowed digits length: 9 to 13.</small>
                        </div>

                        {{-- Odometer --}}
                        <div class="col-6">
                            <label class="form-label fw-semibold">Odometer <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    class="form-control"
                                    wire:model.defer="odometer"
                                    placeholder="e.g. 1250000000"
                                    inputmode="numeric"
                                    pattern="[0-10]*"
                                    autocomplete="off"
                                    oninput="this.value=this.value.replace(/\D/g,'')"
                                    required
                            >
                            @error('odometer') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted d-block mt-1">Numbers only (km).</small>
                        </div>

                        {{-- Actions (Save bottom-left like screenshot) --}}
                        <div class="col-12">
                            <hr class="my-3">

                            <div class="d-flex flex-wrap gap-2">
                                <button
                                        type="button"
                                        class="btn btn-primary px-4"
                                        wire:click="save"
                                        wire:loading.attr="disabled"
                                        @disabled(!$regIsValid)
                                >
                                    <span wire:loading.remove>{{ $isEdit ? 'Update Device' : 'Save Device' }}</span>
                                    <span wire:loading>Saving...</span>
                                </button>

                                <a href="{{ route('gps.index') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>

                            @if(!$regIsValid)
                                <small class="text-muted d-block mt-2">
                                    Tip: Select a registration number from the dropdown to enable Save.
                                </small>
                            @endif
                        </div>

                    </div>{{-- row --}}
                </div>{{-- body --}}
            </div>{{-- card --}}
        </div>
    </div>
</div>
