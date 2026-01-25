<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $isEdit ? 'Edit GPS Device' : 'Add GPS Device' }}</h5>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                Please fix the errors below.
            </div>
        @endif

        <div class="row g-3">


            <div class="col-md-6">
                <label class="form-label">Model</label>
                <select class="form-select" wire:model.defer="model">
                    <option value="">-- Select Model --</option>
                    <option value="FMB120">FMB120</option>
                    <option value="FMB920">FMB920</option>
                </select>
                @error('model') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

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



            <div class="col-md-6">
                <label class="form-label">IMEI (15 digits)</label>
                <input
                        class="form-control"
                        wire:model.defer="imei"
                        placeholder="353691845900000"
                        inputmode="numeric"
                        pattern="\d*"
                        maxlength="15"
                        autocomplete="off"
                        {{ $isEdit ? 'readonly' : '' }}
                >
                @error('imei') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="col-md-6">
                <label class="form-label">Serial (10 digits)</label>
                <input
                        class="form-control"
                        wire:model.defer="serial"
                        placeholder="10-digit serial"
                        inputmode="numeric"
                        pattern="\d*"
                        maxlength="10"
                        autocomplete="off"
                >
                @error('serial') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="col-md-6 position-relative">
                <label class="form-label">REG Number</label>

                <input
                        class="form-control
                        @if($regNotFound) is-invalid
                        @elseif($regIsValid && $regSearch) is-valid
                        @endif"
                        wire:model.live.debounce.300ms="regSearch"
                        placeholder="Type registration number..."
                        autocomplete="off"
                >

                {{-- actual saved value --}}
                <input type="hidden" wire:model.defer="reg_number">

                @error('reg_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                @if($regNotFound && strlen($regSearch) >= 2)
                    <div class="invalid-feedback d-block">
                        Registration number not found (status must be 01).
                    </div>
                @endif

                @if($showRegDropdown && !empty($regSuggestions))
                    <div class="list-group position-absolute w-100 shadow"
                         style="z-index: 2000; max-height: 240px; overflow:auto;">
                        @foreach($regSuggestions as $reg)
                            <button type="button"
                                    class="list-group-item list-group-item-action"
                                    wire:click="selectReg('{{ $reg }}')">
                                {{ $reg }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>


            <div class="col-md-6">
                <label class="form-label">Mobile Number</label>
                <input
                        class="form-control"
                        wire:model.defer="mobile_number"
                        placeholder="+260975..."
                        inputmode="tel"
                        maxlength="16"
                        autocomplete="off"
                >
                @error('mobile_number') <small class="text-danger">{{ $message }}</small> @enderror
                <small class="text-muted d-block mt-1">Allowed digits length: 9 to 13.</small>
            </div>

{{--            <div class="col-md-6">--}}
{{--                <label class="form-label">Connected At</label>--}}
{{--                <input type="datetime-local" class="form-control" wire:model.defer="connected_at">--}}
{{--                @error('connected_at') <small class="text-danger">{{ $message }}</small> @enderror--}}
{{--            </div>--}}

{{--            <div class="col-md-6">--}}
{{--                <label class="form-label">Last Seen</label>--}}
{{--                <input type="datetime-local" class="form-control" wire:model.defer="last_seen_at">--}}
{{--                @error('last_seen_at') <small class="text-danger">{{ $message }}</small> @enderror--}}
{{--            </div>--}}

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" wire:model.defer="status">
                    <option value="inactive">inactive</option>
                    <option value="active">active</option>
                    <option value="offline">offline</option>
                    <option value="online">online</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- forced --}}
            <div class="col-md-6">
                <label class="form-label">Type ID</label>
                <input type="text" class="form-control" wire:model.defer="type_id" readonly>
                @error('type_id') <small class="text-danger">{{ $message }}</small> @enderror
                <small class="text-muted d-block mt-1">Automatically set to 1.</small>
            </div>

            <div class="col-md-4 d-flex align-items-end justify-content-end gap-2">
                <a href="{{ route('gps.index') }}" class="btn btn-light">Cancel</a>

                {{-- disable if reg not selected --}}
                <button class="btn btn-primary"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        @disabled(!$regIsValid)>
                    {{ $isEdit ? 'Update' : 'Save' }}
                </button>
            </div>

        </div>
    </div>
</div>
