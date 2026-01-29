<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="All GPS Dashboard"
                      :activeCrumb="'GPS Dashboard'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <div class="container-fluid">
        <div>
            {{-- Header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h4 class="mb-1">GPS Devices</h4>
                    <div class="text-muted small">
                        Total: <strong>{{ number_format($total) }}</strong> |
                        Connected: <strong>{{ number_format($connected) }}</strong> |
                        Not Connected: <strong>{{ number_format($not_connected) }}</strong>
                    </div>

                    {{-- Active filter chips --}}
                    <div class="mt-2 d-flex flex-wrap gap-2">
                        @if($search !== '')
                            <span class="badge bg-light text-dark border">Search: {{ $search }}</span>
                        @endif

                        @if($filterStatus !== 'all')
                            <span class="badge bg-light text-dark border">Status: {{ strtoupper($filterStatus) }}</span>
                        @endif

                        @if($filterConnectivity !== 'all')
                            <span class="badge bg-light text-dark border">Connectivity: {{ strtoupper($filterConnectivity) }}</span>
                        @endif

                        @if($filterType !== 'all')
                            <span class="badge bg-light text-dark border">Type: {{ $filterType }}</span>
                        @endif

                        @if($search !== '' || $filterStatus !== 'all' || $filterConnectivity !== 'all' || $filterType !== 'all')
                            <button class="btn btn-sm btn-outline-secondary" wire:click="resetFilters">
                                <i class="fas fa-rotate-left"></i> Reset
                            </button>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('gps.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Device
                    </a>

                    <div class="btn-group">
                        <button class="btn btn-outline-dark" type="button" wire:click="copyVisible">
                            <i class="fas fa-copy"></i> Copy
                        </button>

                        <button class="btn btn-outline-dark" type="button" wire:click="exportCsv">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </div>
            </div>

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            {{-- Toolbar --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-lg-5">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text"
                                       class="form-control"
                                       placeholder="Search IMEI / Serial / REG NUMBER / Model / Mobile..."
                                       wire:model.live.debounce.250ms="search">
                                @if($search !== '')
                                    <button class="btn btn-outline-secondary" type="button" wire:click="$set('search','')">
                                        Clear
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="col-6 col-lg-2">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <select class="form-select" wire:model.live="filterConnectivity">
                                <option value="all">All Connectivity</option>
                                <option value="connected">Connected</option>
                                <option value="not_connected">Not Connected</option>
                                <option value="never">Never Seen</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <select class="form-select" wire:model.live="filterType">
                                <option value="all">All Types</option>
                                @foreach($typeOptions as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-lg-1">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2 text-muted small">
                        Showing <strong>{{ $devices->count() }}</strong> on this page •
                        Filtered total <strong>{{ number_format($filteredTotal) }}</strong>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th role="button" class="text-nowrap" wire:click="sortBy('model')">
                                Model {!! $sortIcon('model') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('type')">
                                Type {!! $sortIcon('type') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('imei')">
                                IMEI {!! $sortIcon('imei') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('serial')">
                                Serial {!! $sortIcon('serial') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('reg_number')">
                                REG Number {!! $sortIcon('reg_number') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('mobile_number')">
                                Mobile {!! $sortIcon('mobile_number') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('connected_at')">
                                Connected At {!! $sortIcon('connected_at') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('last_seen_at')">
                                Last Seen {!! $sortIcon('last_seen_at') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('odometer')">
                                Odometer {!! $sortIcon('odometer') !!}
                            </th>
                            <th role="button" class="text-nowrap" wire:click="sortBy('status')">
                                Status {!! $sortIcon('status') !!}
                            </th>

                            <th class="text-end text-nowrap">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($devices as $d)
                            <tr>
                                <td>{{ $d->model ?? '--' }}</td>
                                <td>{{ $d->type ?? '--' }}</td>
                                <td class="text-nowrap">{{ $d->imei }}</td>
                                <td>{{ $d->serial ?? '--' }}</td>
                                <td>{{ $d->reg_number ?? '--' }}</td>
                                <td>{{ $d->mobile_number ?? '--' }}</td>
                                <td class="text-nowrap">{{ $d->connected_at?->toDateTimeString() ?? '--' }}</td>
                                <td class="text-nowrap">{{ $d->last_seen_at?->toDateTimeString() ?? '--' }}</td>
                                <td class="text-nowrap">
                                    {{ $d->odometer !== null ? number_format((int) $d->odometer) : '--' }}
                                </td>
                                <td>
                                    @if((int) $d->status === 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-end text-nowrap">
                                    <a href="{{ route('gps.edit', $d->imei) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="if (!confirm('Delete this device?')) return false;"
                                            wire:click="delete('{{ $d->imei }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted p-4">No devices found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="text-muted small">
                        Page {{ $devices->currentPage() }} of {{ $devices->lastPage() }}
                    </div>
                    <div>
                        {{ $devices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copy to clipboard --}}
    <script>
        window.addEventListener('gps-copy', async (e) => {
            try {
                await navigator.clipboard.writeText(e.detail.text);
            } catch (err) {
                alert('Copy failed: ' + err);
            }
        });
    </script>

    {{-- Optional UX (no font changes) --}}
    <style>
        .table-responsive thead th {
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</section>
