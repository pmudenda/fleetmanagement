<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0">{{ $isEdit ? 'Edit GPS Device' : 'Add GPS Device' }}</h5>
            <small class="text-muted">Capture device details and link to a vehicle registration.</small>
        </div>
        <span class="badge bg-light text-dark">
            {{ $isEdit ? 'Editing' : 'New' }}
        </span>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <strong>Action required:</strong> Please fix the errors highlighted below.
            </div>
        @endif

        <div class="row g-3">
            {{-- Model --}}
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <select class="form-select" wire:model.defer="model">
                    <option value="">-- Select Model --</option>
                    <option value="FMB120">FMB120</option>
                    <option value="FMB920">FMB920</option>
                </select>
                @error('model') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Device Type --}}
            <div class="col-md-6">
                <label class="form-label">Device Type</label>
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
            <div class="col-md-6">
                <label class="form-label">IMEI <span class="text-muted">(15 digits)</span></label>
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
            <div class="col-md-6">
                <label class="form-label">Device Serial <span class="text-muted">(9–10 digits)</span></label>
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
            <div class="col-md-6 position-relative">
                <label class="form-label">REG Number</label>

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
                    >
                </div>

                <input type="hidden" wire:model.defer="reg_number">

                @error('reg_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                @if($regNotFound && strlen($regSearch) >= 2)
                    <div class="invalid-feedback d-block mt-1">
                        Registration number not found (status must be 01).
                    </div>
                @endif

                @if($showRegDropdown && !empty($regSuggestions))
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
            <div class="col-md-6">
                <label class="form-label">Mobile Number</label>
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

            {{-- Actions --}}
            <div class="col-12">
                <hr class="my-2">
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('gps.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button
                            type="button"
                            class="btn btn-primary"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            @disabled(!$regIsValid)
                    >
                        <span wire:loading.remove>{{ $isEdit ? 'Update Device' : 'Save Device' }}</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>

                @if(!$regIsValid)
                    <small class="text-muted d-block mt-2">
                        Tip: Select a registration number from the dropdown to enable Save.
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>
