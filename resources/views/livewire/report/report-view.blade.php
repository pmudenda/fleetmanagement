<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="strtoupper($report->name) . ' REPORT'"
                      :activeCrumb="$report->name"
                      :link="'report.index'"
                      :linkText="'Reports'"/>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- Filters Card -->
                @if($report->filters && count($report->filters) > 0)
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Filters</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Display Errors -->
                            @if(!empty($errors))
                                @foreach($errors as $field => $error)
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                        </button>
                                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            <div class="row">
                                @foreach($report->filters as $filter)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ $filter['label'] }}</label>

                                            @if($filter['type'] === 'date_range')
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="date"
                                                               wire:model="filters.{{ $filter['field'] }}_start"
                                                               class="form-control {{ isset($errors[$filter['field']]) ? 'is-invalid' : '' }}"
                                                               placeholder="Start Date">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="date"
                                                               wire:model="filters.{{ $filter['field'] }}_end"
                                                               class="form-control {{ isset($errors[$filter['field']]) ? 'is-invalid' : '' }}"
                                                               placeholder="End Date">
                                                    </div>
                                                </div>

                                            @elseif($filter['type'] === 'text_search')
                                                <input type="text"
                                                       wire:model.debounce.300ms="filters.{{ $filter['field'] }}"
                                                       class="form-control"
                                                       placeholder="Search...">

                                            @elseif($filter['type'] === 'multi_select')
                                                <select multiple
                                                        wire:model="filters.{{ $filter['field'] }}"
                                                        class="form-control select2"
                                                        id="select-{{ $filter['field'] }}"
                                                        style="width: 100%;">
                                                    @foreach($filterOptions[$filter['field']] ?? [] as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>

                                            @elseif($filter['type'] === 'number_range')
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                               wire:model="filters.{{ $filter['field'] }}_min"
                                                               class="form-control {{ isset($errors[$filter['field']]) ? 'is-invalid' : '' }}"
                                                               placeholder="Min">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                               wire:model="filters.{{ $filter['field'] }}_max"
                                                               class="form-control {{ isset($errors[$filter['field']]) ? 'is-invalid' : '' }}"
                                                               placeholder="Max">
                                                    </div>
                                                </div>

                                            @elseif($filter['type'] === 'select')
                                                <div class="form-group" wire:ignore>
                                                    <select wire:model="filters.{{ $filter['field'] }}"
                                                            class="form-control select2"
                                                            id="select-{{ $filter['field'] }}"
                                                            style="width: 100%;">
                                                        <option value="">--ALL--</option>
                                                        @foreach($filterOptions[$filter['field']] ?? [] as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            @if(isset($errors[$filter['field']]))
                                                <div class="invalid-feedback d-block">
                                                    {{ $errors[$filter['field']] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <x-button wire:click="applyFilters" class="btn btn-primary" wire:target="applyFilters">
                                <i class="fas fa-filter"></i> Apply Filters
                            </x-button>
                            <x-button wire:click="resetFilters" class="btn btn-default" wire:target="resetFilters">
                                <i class="fas fa-redo"></i> Reset
                            </x-button>
                        </div>
                    </div>
                @endif

                <!-- Results Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="d-flex flex-column gap-2">
                                <span class="h3 text-uppercase ">Total Records: {{$results->total()}}</span>
                            </div>
                        </div>
                        <div class="card-tools mr-2">
                            <x-button wire:click="export" wire:target="export" class="btn btn-primary" >
                                Export
                            </x-button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            @foreach($columns as $column)
                                                <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($results as $result)
                                            <tr>
                                                @foreach($columns as $column)
                                                    <td>{{ $result->$column ?? '' }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{$results->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {

            // Update Livewire when Select2 changes
            $('.select2').on('change', function (e) {
                const fieldName = $(this).attr('wire:model');
                const value = $(this).val();
                @this.
                set(fieldName, value);
                @this.call('applyFilters');
            });

        });

        document.addEventListener('livewire:update', () => {
            console.log('Livewire updated');
            setTimeout(() => {
                $('.select2').select2({
                    placeholder: "Select...",
                    allowClear: true
                });
            }, 100);
        });

    </script>

@endpush