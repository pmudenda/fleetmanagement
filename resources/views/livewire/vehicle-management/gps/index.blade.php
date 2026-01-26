<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="All GPS Dashboard"
                      :activeCrumb="'GPS Dashboard'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">GPS Devices</h4>
            <div class="text-muted">
                Total: {{ $total }} |
                Connected: {{ $connected }} |
                Not Connected: {{ $not_connected }}
            </div>
        </div>

        <a href="{{ route('gps.create') }}" class="btn btn-primary">
            + Add Device
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body d-flex gap-2">
            <input type="text" class="form-control" placeholder="Search IMEI / Serial / RE / Model / Mobile..."
                   wire:model.live="search">

            <select class="form-select" style="max-width: 120px" wire:model.live="perPage">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                <tr>
                    <th>Model</th>
                    <th>Type</th>
                    <th>IMEI</th>
                    <th>Serial</th>
                    <th>REG Number</th>
                    <th>Mobile</th>
                    <th>Connected At</th>
                    <th>Last Seen</th>
                    <th>Status</th>
                    <th>Type ID</th>
                    <th>Action</th>
                    <th style="width: 140px"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($devices as $d)
                    <tr>
                        <td>{{ $d->model ?? '--' }}</td>
                        <td>{{ $d->type ?? '--' }}</td>
                        <td>{{ $d->imei }}</td>
                        <td>{{ $d->serial ?? '--' }}</td>
                        <td>{{ $d->reg_number ?? '--' }}</td>
                        <td>{{ $d->mobile_number ?? '--' }}</td>
                        <td>{{ $d->connected_at?->toDateTimeString() ?? '--' }}</td>
                        <td>{{ $d->last_seen_at?->toDateTimeString() ?? '--' }}</td>
                        <td>{{ $d->status ?? '--' }}</td>


                        <td>{{ $d->type_id ?? '--' }}</td>
                        <td class="text-end">

                            {{-- Edit --}}
                            <a href="{{ route('gps.edit', $d->imei) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- Delete --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="if (!confirm('Delete this device?')) return false;"
                                    wire:click="delete('{{ $d->imei }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>

                        </td>

                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center text-muted p-4">No devices found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $devices->links() }}
        </div>
    </div>
</div>
    </div>
</section>
