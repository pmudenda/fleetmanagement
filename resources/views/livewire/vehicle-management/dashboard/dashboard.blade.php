@section('styles')
<style>
    /* Dashboard Card Alignment Improvements */
    .dashboard-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .hover-lift {
        transition: all 0.2s ease;
    }
    
    .hover-lift:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateX(2px);
    }
    
    /* KPI Cards Alignment */
    .dashboard-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100px;
    }
    
    /* Table Improvements */
    .data-table-container {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }
    
    /* Status Badge Alignment */
    .status-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    /* Vehicle Link Styling */
    .vehicle-link {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }
    
    .vehicle-link:hover {
        color: #0b5ed7;
        text-decoration: underline;
    }
    
    /* Chart Container Alignment */
    .chart-card {
        min-height: 300px;
    }
    
    .chart-card-body {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Responsive Improvements */
    @media (max-width: 768px) {
        .dashboard-card .card-body {
            min-height: 80px;
            padding: 1rem 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        
        .status-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
    }
    
    /* Filter Section Alignment */
    .card-header .d-flex {
        align-items: center;
    }
    
    .form-control-sm,
    .form-select-sm {
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Button Alignment */
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    
    /* Loading State */
    .wire-loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    /* Summary Box Alignment */
    .summary-box {
        border-left: 4px solid #0d6efd;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .summary-box:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    }
</style>
@endsection

<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="All GPS Dashboard"
                      :activeCrumb="'GPS Dashboard'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <div class="container-fluid">
        <div wire:poll.2s>

            @php
                // KPI Calculations
                $total = (int)($kpis['total'] ?? 0);
                $online = (int)($kpis['online'] ?? 0);
                $score = $total > 0 ? (int) round(($online / $total) * 100) : 0;
                $badge = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                $ds = $decisionSummary ?? null;
            @endphp

            {{-- Top Filter/Header Card --}}
            <div class="card shadow-sm mb-3 border-0 dashboard-card">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="mb-0 fw-bold text-uppercase">GPS Dashboard</h5>
                                <span class="badge bg-info text-dark">Live</span>

                                <span class="badge bg-light text-dark border">
                                    Online Window: <b>{{ $onlineWindowMinutes }}</b>m
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Alert: <b>{{ $alertOfflineMinutes }}</b>m
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Cutoff: <b>{{ $cutoff->toDateTimeString() }}</b>
                                </span>
                            </div>

                            <div class="small mt-2" wire:loading>
                                <span class="text-primary">
                                    <i class="fas fa-spinner fa-spin"></i> Updating...
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-end align-items-end">
                            <div>
                                <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">From</label>
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="dateFrom"
                                       style="min-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">To</label>
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="dateTo"
                                       style="min-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Online</label>
                                <select class="form-select form-select-sm"
                                        wire:model.live="onlineWindowMinutes"
                                        style="min-width: 120px;">
                                    <option value="5">5m</option>
                                    <option value="10">10m</option>
                                    <option value="15">15m</option>
                                    <option value="30">30m</option>
                                    <option value="60">60m</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Alerts</label>
                                <select class="form-select form-select-sm"
                                        wire:model.live="alertOfflineMinutes"
                                        style="min-width: 120px;">
                                    <option value="15">15m</option>
                                    <option value="30">30m</option>
                                    <option value="60">60m</option>
                                    <option value="120">120m</option>
                                </select>
                            </div>

                            <button class="btn btn-sm btn-outline-primary" wire:click="refreshNow">
                                <i class="fas fa-sync"></i> Refresh
                            </button>

                            <button class="btn btn-sm btn-dark" wire:click="openSummaryModal">
                                <i class="fas fa-chart-line"></i> View Report
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-dark dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" wire:click.prevent="exportCsv">
                                            CSV (Filtered)
                                        </a>
                                    </li>
                                    <li>
                                        <span class="dropdown-item text-muted">PDF / Excel (hook ready)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="row g-3 mb-3">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card hover-lift">
                        <div class="card-body py-3 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-satellite-dish fa-2x text-primary opacity-75"></i>
                            </div>
                            <div class="small text-muted text-uppercase fw-semibold mb-1">Total</div>
                            <div class="h3 mb-0 fw-bold">{{ $kpis['total'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card border-start border-4 border-success">
                        <div class="card-body py-3 text-center">
                            <div class="small text-muted text-uppercase fw-semibold">Online</div>
                            <div class="h4 mb-1 fw-bold text-success">{{ $kpis['online'] ?? 0 }}</div>
                            <div class="small text-muted">connected_at ≥ cutoff</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card border-start border-4 border-warning">
                        <div class="card-body py-3 text-center">
                            <div class="small text-muted text-uppercase fw-semibold">Offline</div>
                            <div class="h4 mb-1 fw-bold text-warning">{{ $kpis['offline'] ?? 0 }}</div>
                            <div class="small text-muted">connected_at &lt; cutoff</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card border-start border-4 border-secondary">
                        <div class="card-body py-3 text-center">
                            <div class="small text-muted text-uppercase fw-semibold">Never</div>
                            <div class="h4 mb-1 fw-bold">{{ $kpis['neverSeen'] ?? 0 }}</div>
                            <div class="small text-muted">no connected_at</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card border-start border-4 border-primary">
                        <div class="card-body py-3 text-center">
                            <div class="small text-muted text-uppercase fw-semibold">Seen in Range</div>
                            <div class="h4 mb-1 fw-bold text-primary">{{ $kpis['rangeSeen'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm h-100 border-0 dashboard-card border-start border-4 border-{{ $badge }}">
                        <div class="card-body py-3 text-center">
                            <div class="small text-muted text-uppercase fw-semibold">Performance</div>
                            <div class="h4 mb-1 fw-bold text-{{ $badge }}">{{ $score }}%</div>
                            <div class="small text-muted">online vs total</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Cards Layout --}}
            <div class="row g-3 mb-3">
                {{-- LEFT SIDE --}}
                <div class="col-lg-8">
                    {{-- Charts --}}
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="card shadow-sm border-0 dashboard-card chart-card">
                                <div class="card-header bg-white py-2 border-0">
                                    <div class="fw-bold text-uppercase small mb-0">Connectivity</div>
                                    <div class="small text-muted">Click segments to filter table</div>
                                </div>
                                <div class="card-body chart-card-body" wire:ignore>
                                    <canvas id="gpsPieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card shadow-sm border-0 dashboard-card chart-card">
                                <div class="card-header bg-white py-2 border-0">
                                    <div class="fw-bold text-uppercase small mb-0">Trend</div>
                                    <div class="small text-muted">Connections per day (connected_at)</div>
                                </div>
                                <div class="card-body chart-card-body" wire:ignore>
                                    <canvas id="gpsTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    {{-- Monthly Operating Cost Trends --}}
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 dashboard-card h-100">
                                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <i class="fas fa-chart-line text-primary"></i>
                                        <div class="fw-bold text-uppercase small mb-0">Monthly Operating Cost Trends</div>
                                        <span class="badge bg-light text-dark border">12 Months</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="refreshMonthlyTrends()">
                                            <i class="fas fa-sync"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-3" wire:ignore>
                                    <div id="monthlyTrendsChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cost Distribution and Top Vehicles --}}
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 dashboard-card h-100">
                                <div class="card-header bg-white py-3 border-0">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <i class="fas fa-chart-pie text-success"></i>
                                        <div class="fw-bold text-uppercase small mb-0">Cost Distribution</div>
                                        <span class="badge bg-light text-dark border">Fuel vs Maintenance</span>
                                    </div>
                                </div>
                                <div class="card-body py-3 d-flex align-items-center justify-content-center" wire:ignore>
                                    <div id="costDistributionChart" style="height: 300px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 dashboard-card h-100">
                                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <i class="fas fa-truck text-warning"></i>
                                        <div class="fw-bold text-uppercase small mb-0">Top Vehicles Performance</div>
                                        <span class="badge bg-light text-dark border">By Operating Cost</span>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary active" onclick="loadTopVehiclesByMetric('total_cost', this)">Operating Cost</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="loadTopVehiclesByMetric('fuel_cost', this)">Fuel Cost</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="loadTopVehiclesByMetric('maintenance_cost', this)">Maintenance Cost</button>
                                        <button type="button" class="btn btn-outline-primary" onclick="loadTopVehiclesByMetric('maintenance_events', this)">Maintenance Events</button>
                                    </div>
                                </div>
                                <div class="card-body py-3" wire:ignore>
                                    <div class="row g-3">
                                        <div class="col-lg-8">
                                            <div id="topVehiclesChart" style="height: 400px;"></div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div id="topVehiclesList" class="small">
                                                <!-- Vehicle list will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AI Insight --}}
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 dashboard-card h-100">
                                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <i class="fas fa-robot"></i>
                                        <div class="fw-bold text-uppercase small mb-0">AI Insight</div>
                                        <span class="badge bg-{{ $badge }}">Performance {{ $score }}%</span>
                                    </div>
                                    <span class="badge bg-light text-dark border">Interactive</span>
                                </div>

                                <div class="card-body py-3">
                                    <div class="small text-muted mb-3">{{ $this->aiInsight }}</div>

                                    <div class="p-3 border rounded-3 bg-light-subtle">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Overall performance</span>
                                            <span><b>{{ $score }}%</b></span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-{{ $badge }}" style="width: {{ $score }}%"></div>
                                        </div>
                                        <div class="mt-2 small text-muted">
                                            Online uses <b>connected_at</b> within {{ $onlineWindowMinutes }} minutes.
                                            Click charts to drill down.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0 dashboard-card">
                        <div class="card-header bg-white py-3 border-0">
                            <div class="fw-bold text-uppercase small mb-0">Decision Summary</div>
                            <div class="small text-muted">Fast signals for action</div>
                        </div>

                        <div class="card-body py-3">
                            @if($ds && !empty($ds['bullets']))
                                <div class="d-grid gap-2">
                                    @foreach($ds['bullets'] as $b)
                                        <div class="p-3 border rounded-3 summary-box">
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                                <div class="fw-semibold">{{ $b['title'] }}</div>
                                                <span class="badge bg-{{ $b['tagBg'] ?? 'dark' }}">{{ $b['tag'] ?? '' }}</span>
                                            </div>
                                            <div class="small text-muted">{{ $b['text'] }}</div>

                                            @if(!empty($b['actionFilter']))
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-dark mt-2"
                                                        onclick="Livewire.dispatch('set-dashboard-filters', @js($b['actionFilter']))">
                                                    Drill down
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 border rounded-3 mb-2 summary-box">
                                    <div class="fw-semibold">If Never Connected is high</div>
                                    <div class="small text-muted">Focus: onboarding, SIM/APN, wiring/power, wrong server/port.</div>
                                </div>

                                <div class="p-3 border rounded-3 mb-2 summary-box">
                                    <div class="fw-semibold">If Offline is high</div>
                                    <div class="small text-muted">Focus: network coverage, tracking server downtime, vehicle power, device health.</div>
                                </div>

                                <div class="p-3 border rounded-3 summary-box">
                                    <div class="fw-semibold">Use chart clicks to drill down</div>
                                    <div class="small text-muted">Connectivity + Trend help identify patterns.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent / Live Device Stats --}}
            <div class="card shadow-sm border-0 dashboard-card">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <div class="fw-bold text-uppercase small">Recent / Live Device Stats</div>
                        <div class="small text-muted">Connectivity is based on connected_at</div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text"
                               class="form-control form-control-sm"
                               placeholder="Search IMEI / Serial / Reg / Model / Mobile..."
                               wire:model.live.debounce.400ms="search"
                               style="max-width: 260px;">

                        <select class="form-select form-select-sm" wire:model.live="filterConnectivity" style="max-width: 170px;">
                            <option value="all">Conn: All</option>
                            <option value="online">Conn: Online</option>
                            <option value="offline">Conn: Offline</option>
                            <option value="never">Conn: Never</option>
                        </select>

                        <select class="form-select form-select-sm" wire:model.live="filterStatus" style="max-width: 150px;">
                            <option value="all">Status: All</option>
                            <option value="active">Status: Active</option>
                            <option value="inactive">Status: Inactive</option>
                        </select>

                        <select class="form-select form-select-sm" wire:model.live="filterType" style="max-width: 160px;">
                            <option value="all">Type: All</option>
                            @foreach($types as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>

                        <select class="form-select form-select-sm" wire:model.live="perPage" style="max-width: 100px;">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small">IMEI</th>
                            <th class="text-uppercase small">Model</th>
                            <th class="text-uppercase small">Type</th>
                            <th class="text-uppercase small">Reg</th>
                            <th class="text-uppercase small">Mobile</th>
                            <th class="text-uppercase small">Status</th>
                            <th class="text-uppercase small">Connected At</th>
                            <th class="text-uppercase small text-end">Conn</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($devices as $d)
                            @php
                                $statusLabel = method_exists($d, 'getAttribute')
                                    ? ($d->status_label ?? (((int)($d->status ?? 0) === 1) ? 'Active' : 'Inactive'))
                                    : (((int)($d->status ?? 0) === 1) ? 'Active' : 'Inactive');

                                $isOnline = $d->connected_at && $d->connected_at >= $cutoff;
                                $isNever  = is_null($d->connected_at);
                            @endphp

                            <tr>
                                <td class="fw-semibold">{{ $d->imei }}</td>
                                <td>{{ $d->model ?? '--' }}</td>
                                <td>{{ $d->type ?? '--' }}</td>
                                <td>{{ $d->reg_number ?? '--' }}</td>
                                <td>{{ $d->mobile_number ?? '--' }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusLabel === 'Active' ? 'success' : 'secondary' }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>{{ $d->connected_at?->toDateTimeString() ?? '--' }}</td>
                                <td class="text-end">
                                    @if($isNever)
                                        <span class="badge bg-secondary">Never</span>
                                    @elseif($isOnline)
                                        <span class="badge bg-success">Online</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Offline</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted p-3">No records found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-body py-2">
                    {{ $devices->links() }}
                </div>
            </div>

            {{-- Fuel Management Data Table --}}
            <div class="card shadow-sm mt-3 border-0 dashboard-card">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <i class="fas fa-gas-pump text-warning"></i>
                            <div class="fw-bold text-uppercase">Fuel Management</div>
                            <span class="badge bg-light text-dark border">{{ count($fuelData) }} records</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.open('{{ route('vehicle.show') }}', '_blank')">
                                <i class="fas fa-plus"></i> Add Fuel Record
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="exportFuelData()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Fuel Filters --}}
                <div class="card-body py-3 bg-light border-bottom">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Search</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="fuelSearch"
                                   placeholder="Reg No, Vehicle, Document...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Reg No</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="fuelRegNo"
                                   placeholder="Registration">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Fuel Type</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="fuelType"
                                   placeholder="Petrol, Diesel...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Status</label>
                            <select class="form-select form-select-sm"
                                    wire:model.live="fuelStatus">
                                <option value="">All Status</option>
                                <option value="normal">Normal</option>
                                <option value="over_issued">Over Issued</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Date Range</label>
                            <div class="d-flex gap-1">
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="fuelDateFrom"
                                       placeholder="From">
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="fuelDateTo"
                                       placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            @if($fuelSearch || $fuelRegNo || $fuelType || $fuelStatus || $fuelDateFrom || $fuelDateTo)
                                <i class="fas fa-filter text-primary"></i> Filters applied
                                <button class="btn btn-xs btn-outline-secondary ms-2" wire:click="resetFuelFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            @else
                                <i class="fas fa-info-circle"></i> Use filters to narrow results
                            @endif
                        </div>
                        <div class="small text-muted">
                            Showing {{ min(count($fuelData), 50) }} of {{ count($fuelData) }} records
                        </div>
                    </div>
                </div>

                <div class="data-table-container">
                    <table class="table table-sm table-hover table-striped mb-0 align-middle">
                        <thead class="table-light sticky-top">
                        <tr>
                            <th class="text-uppercase small">Reg No</th>
                            <th class="text-uppercase small">Vehicle Type</th>
                            <th class="text-uppercase small">Engine Capacity</th>
                            <th class="text-uppercase small">Fuel Type</th>
                            <th class="text-uppercase small">Issue Document</th>
                            <th class="text-uppercase small">Fueling Type</th>
                            <th class="text-uppercase small">QTY Issued Value</th>
                            <th class="text-uppercase small">Issue Date</th>
                            <th class="text-uppercase small text-end">Qty (L)</th>
                            <th class="text-uppercase small text-end">Price/L</th>
                            <th class="text-uppercase small text-end">Total Value</th>
                            <th class="text-uppercase small text-center">Status</th>
                            <th class="text-uppercase small">Fuel Req Unit</th>
                            <th class="text-uppercase small text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(array_slice($fuelData, 0, 50) as $fuel)
                            <tr class="hover-lift {{ ($fuel->qty_issued ?? 0) == 0 ? 'text-muted' : '' }}">
                                <td class="fw-semibold">
                                    <a href="{{ route('vehicle.show', ['reg_no' => $fuel->reg_no ?? '']) }}"
                                       class="vehicle-link" target="_blank">
                                        {{ $fuel->reg_no ?? '--' }}
                                    </a>
                                    @if(($fuel->qty_issued ?? 0) == 0)
                                        <span class="badge bg-warning text-dark ms-1">No Records</span>
                                    @endif
                                </td>
                                <td>{{ $fuel->type_brand ?? '--' }}</td>
                                <td>{{ $fuel->fuel_type ?? '--' }}</td>
                                <td>{{ $fuel->engine_capacity ?? '--' }}</td>
                                <td>{{ $fuel->issue_document ?? '--' }}</td>
                                <td>{{ $fuel->fueling_type_name ?? '--' }}</td>
                                <td>{{ $fuel->qty_issued_value ?? '--' }}</td>

                                <td>{{ $fuel->issue_date ? \Carbon\Carbon::parse($fuel->issue_date)->format('M d, Y') : '--' }}</td>
                                <td class="text-end">{{ number_format($fuel->qty_issued ?? 0, 2) }}</td>
                                <td class="text-end">{{ number_format($fuel->price_per_litre ?? 0, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($fuel->qty_issued_value ?? 0, 2) }}</td>

                                <td class="text-center">
                                    @if(($fuel->qty_issued ?? 0) == 0)
                                        <span class="badge bg-secondary status-badge">No Data</span>
                                    @elseif(($fuel->Over_Issued ?? 'N') === 'Y')
                                        <span class="badge bg-danger status-badge">Over Issued</span>
                                    @else
                                        <span class="badge bg-success status-badge">Normal</span>
                                    @endif
                                </td>
                                <td>{{ $fuel->fuel_req_unit ?? '--' }}</td>

                                <td class="text-center">
                                    <button class="btn btn-xs btn-outline-primary" onclick="showVehicleDetails('{{ $fuel->reg_no ?? '' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
{{--                                    @if(($fuel->qty_issued ?? 0) > 0)--}}
{{--                                        <button class="btn btn-xs btn-outline-info ms-1" onclick="editFuelRecord('{{ $fuel->reg_no ?? '' }}')">--}}
{{--                                            <i class="fas fa-edit"></i>--}}
{{--                                        </button>--}}
{{--                                    @endif--}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted p-4">
                                    <i class="fas fa-gas-pump fa-2x mb-2"></i>
                                    <div>No fuel records found for the selected date range.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($fuelData) > 50)
                    <div class="card-body py-2 text-center">
                        <small class="text-muted">Showing first 50 of {{ count($fuelData) }} records</small>
                    </div>
                @endif
            </div>

            {{-- Fuel Usage Analysis Table --}}
            <div class="card shadow-sm mt-3 border-0 dashboard-card">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <i class="fas fa-chart-line text-primary"></i>
                        <div class="fw-bold text-uppercase">Fuel Usage Analysis</div>
                        <span class="badge bg-light text-dark border">{{ count($fuelUsageAnalysis) }} vehicles</span>
                        <span class="badge bg-{{ $fuelUsageKpis['fuelUsageBadge'] ?? 'secondary' }}">
                            Efficiency {{ $fuelUsageKpis['efficiencyScore'] ?? 0 }}%
                        </span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-primary" onclick="window.open('{{ route('vehicle.show') }}', '_blank')">
                            <i class="fas fa-plus"></i> Add Vehicle
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="exportFuelUsageData()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                <div class="card-body py-3 bg-light">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <div class="small text-muted text-uppercase">Total Consumed</div>
                            <div class="h5 mb-0 fw-bold text-primary">{{ number_format($fuelUsageKpis['totalFuelConsumed'] ?? 0, 2) }} L</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="small text-muted text-uppercase">Total Cost</div>
                            <div class="h5 mb-0 fw-bold text-success">K {{ number_format($fuelUsageKpis['totalFuelCost'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="small text-muted text-uppercase">Avg per Vehicle</div>
                            <div class="h5 mb-0 fw-bold text-info">{{ number_format($fuelUsageKpis['avgFuelPerVehicle'] ?? 0, 2) }} L</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="small text-muted text-uppercase">Efficiency</div>
                            <div class="h5 mb-0 fw-bold text-{{ $fuelUsageKpis['fuelUsageBadge'] ?? 'secondary' }}">{{ $fuelUsageKpis['efficiencyScore'] ?? 0 }}%</div>
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-12">
                            <div class="small text-muted text-uppercase mb-2">Performance Distribution</div>
                            <div class="d-flex gap-1 flex-wrap">
                                <div class="flex-fill text-center">
                                    <div class="small fw-bold text-success">{{ $fuelUsageKpis['excellentVehicles'] ?? 0 }}</div>
                                    <div class="small text-muted">Excellent</div>
                                </div>
                                <div class="flex-fill text-center">
                                    <div class="small fw-bold text-info">{{ $fuelUsageKpis['goodVehicles'] ?? 0 }}</div>
                                    <div class="small text-muted">Good</div>
                                </div>
                                <div class="flex-fill text-center">
                                    <div class="small fw-bold text-warning">{{ $fuelUsageKpis['fairVehicles'] ?? 0 }}</div>
                                    <div class="small text-muted">Fair</div>
                                </div>
                                <div class="flex-fill text-center">
                                    <div class="small fw-bold text-danger">{{ $fuelUsageKpis['poorVehicles'] ?? 0 }}</div>
                                    <div class="small text-muted">Poor</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="data-table-container">
                    <table class="table table-sm table-hover table-striped mb-0 align-middle">
                        <thead class="table-light sticky-top">
                        <tr>
                            <th class="text-uppercase small">Reg No</th>
                            <th class="text-uppercase small">Vehicle Type</th>
                            <th class="text-uppercase small">Fuel Type</th>
                            <th class="text-uppercase small text-end">Consumed (L)</th>
                            <th class="text-uppercase small text-end">Cost</th>
                            <th class="text-uppercase small text-end">Events</th>
                            <th class="text-uppercase small text-end">Avg/Event</th>
                            <th class="text-uppercase small text-center">Efficiency</th>
                            <th class="text-uppercase small text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(array_slice($fuelUsageAnalysis, 0, 50) as $usage)
                            <tr class="hover-lift">
                                <td class="fw-semibold">
                                    <a href="{{ route('vehicle.show', ['reg_no' => $usage->reg_no ?? '']) }}"
                                       class="vehicle-link" target="_blank">
                                        {{ $usage->reg_no ?? '--' }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small">{{ $usage->type_brand ?? '--' }}</div>
                                    <div class="text-muted">{{ $usage->engine_capacity ?? '--' }} CC</div>
                                </td>
                                <td>{{ $usage->fuel_type ?? '--' }}</td>
                                <td class="text-end fw-bold">{{ number_format($usage->total_fuel_consumed ?? 0, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($usage->total_fuel_cost ?? 0, 2) }}</td>
                                <td class="text-end">{{ $usage->fueling_events ?? 0 }}</td>
                                <td class="text-end">{{ number_format($usage->avg_fuel_per_transaction ?? 0, 2) }}</td>
                                <td class="text-center">
                                        <span class="badge bg-{{
                                            ($usage->efficiency_rating ?? 'Unknown') === 'Excellent' ? 'success' :
                                            (($usage->efficiency_rating ?? 'Unknown') === 'Good' ? 'info' :
                                            (($usage->efficiency_rating ?? 'Unknown') === 'Fair' ? 'warning' : 'danger'))
                                        }} status-badge">
                                            {{ $usage->efficiency_rating ?? 'Unknown' }}
                                        </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-outline-primary" onclick="showVehicleUsageDetails('{{ $usage->reg_no ?? '' }}')">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                    <button class="btn btn-xs btn-outline-info ms-1" onclick="showVehicleDetails('{{ $usage->reg_no ?? '' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted p-4">
                                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                                    <div>No fuel usage data found for the selected date range.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($fuelUsageAnalysis) > 50)
                    <div class="card-body py-2 text-center">
                        <small class="text-muted">Showing first 50 of {{ count($fuelUsageAnalysis) }} vehicles</small>
                    </div>
                @endif
            </div>

            {{-- Maintenance Data Table --}}
            <div class="card shadow-sm mt-3 border-0 dashboard-card">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <i class="fas fa-wrench text-info"></i>
                            <div class="fw-bold text-uppercase">Maintenance Records</div>
                            <span class="badge bg-light text-dark border">{{ count($maintenanceData) }} records</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.open('{{ route('vehicle.show') }}', '_blank')">
                                <i class="fas fa-plus"></i> Add Maintenance
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="exportMaintenanceData()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Maintenance Filters --}}
                <div class="card-body py-3 bg-light border-bottom">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Search</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="maintenanceSearch"
                                   placeholder="Reg No, Vehicle, Article...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Reg No</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="maintenanceRegNo"
                                   placeholder="Registration">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Vehicle Type</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live="maintenanceType"
                                   placeholder="Truck, Car...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Status</label>
                            <select class="form-select form-select-sm"
                                    wire:model.live="maintenanceStatus">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1 small fw-semibold text-uppercase text-muted">Date Range</label>
                            <div class="d-flex gap-1">
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="maintenanceDateFrom"
                                       placeholder="From">
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="maintenanceDateTo"
                                       placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            @if($maintenanceSearch || $maintenanceRegNo || $maintenanceType || $maintenanceStatus || $maintenanceDateFrom || $maintenanceDateTo)
                                <i class="fas fa-filter text-primary"></i> Filters applied
                                <button class="btn btn-xs btn-outline-secondary ms-2" wire:click="resetMaintenanceFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            @else
                                <i class="fas fa-info-circle"></i> Use filters to narrow results
                            @endif
                        </div>
                        <div class="small text-muted">
                            Showing {{ min(count($maintenanceData), 50) }} of {{ count($maintenanceData) }} records
                        </div>
                    </div>
                </div>

                <div class="data-table-container">
                    <table class="table table-sm table-hover table-striped mb-0 align-middle">
                        <thead class="table-light sticky-top">
                        <tr>
                            <th class="text-uppercase small">Reg No</th>
                            <th class="text-uppercase small">Vehicle Type</th>
                            <th class="text-uppercase small">Job Card No</th>

                            <th class="text-uppercase small">Issue  No</th>
                            <th class="text-uppercase small">Requi_No</th>
{{--                            <th class="text-uppercase small">Document Date</th>--}}

                            <th class="text-uppercase small">Vehicle Assignment </th>
                            <th class="text-uppercase small">Value Amount</th>

                            <th class="text-uppercase small">Document Date</th>
                            <th class="text-uppercase small text-end">Cost</th>
                            <th class="text-uppercase small text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(array_slice($maintenanceData, 0, 50) as $maintenance)
                            <tr class="hover-lift {{ ($maintenance->value_amount ?? 0) == 0 ? 'text-muted' : '' }}">
                                <td class="fw-semibold">
                                    <a href="{{ route('vehicle.show', ['reg_no' => $maintenance->reg_no ?? '']) }}"
                                       class="vehicle-link" target="_blank">
                                        {{ $maintenance->reg_no ?? '--' }}
                                    </a>
                                    @if(($maintenance->value_amount ?? 0) == 0)
                                        <span class="badge bg-warning text-dark ms-1">No Records</span>
                                    @endif
                                </td>
                                <td>{{ $maintenance->type_brand ?? '--' }}</td>
                                <td>{{ $maintenance->job_card_no ?? '--' }}</td>
                                <td>{{ $maintenance->issue_no ?? '--' }}</td>
                                <td>{{ $maintenance->requi_number ?? '--' }}</td>

                                <td>
{{--                                    <div class="small">{{ $maintenance->article_description ?? '--' }}</div>--}}
                                    <div class="text-muted">{{ $maintenance->article_code ?? '--' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $maintenance->vehicle_assignment ?? '--' }}</div>
                                    <div class="text-muted">{{ $maintenance->ORGANIZATIONALUNIT ?? '--' }}</div>
                                </td>
                                <td>{{ $maintenance->document_date ? \Carbon\Carbon::parse($maintenance->document_date)->format('M d, Y') : '--' }}</td>
                                <td class="text-end fw-bold">{{ number_format($maintenance->value_amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-outline-primary" onclick="showVehicleDetails('{{ $maintenance->reg_no ?? '' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(($maintenance->value_amount ?? 0) > 0)
                                        <button class="btn btn-xs btn-outline-info ms-1" onclick="editMaintenanceRecord('{{ $maintenance->reg_no ?? '' }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">
                                    <i class="fas fa-wrench fa-2x mb-2"></i>
                                    <div>No maintenance records found for the selected date range.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($maintenanceData) > 50)
                    <div class="card-body py-2 text-center">
                        <small class="text-muted">Showing first 50 of {{ count($maintenanceData) }} records</small>
                    </div>
                @endif
            </div>

            {{-- Summary Modal --}}
            @if($showSummaryModal)
                <div class="modal fade show d-block"
                     tabindex="-1"
                     style="background: rgba(0,0,0,.55);"
                     wire:click.self="closeSummaryModal">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg rounded-4" wire:click.stop>

                            @php
                                $sr = $summaryReport ?? [];

                                $c = $sr['counts'] ?? [];
                                $active   = (int)($c['active'] ?? 0);
                                $inactive = (int)($c['inactive'] ?? 0);

                                $online = (int)($c['online'] ?? 0);
                                $offline = (int)($c['offline'] ?? 0);
                                $never = (int)($c['never'] ?? 0);

                                $totalConnBasis = max(1, ($online + $offline + $never));
                                $perf = (int) round(($online / $totalConnBasis) * 100);
                                $perfBadge = $perf >= 80 ? 'success' : ($perf >= 50 ? 'warning' : 'danger');

                                $q = $sr['quality'] ?? [];
                                $missingReg = (int)($q['missingReg'] ?? 0);
                                $missingMobile = (int)($q['missingMobile'] ?? 0);
                                $missingSerial = (int)($q['missingSerial'] ?? 0);
                            @endphp

                            <div class="modal-header align-items-start border-0">
                                <div class="w-100">
                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                        <div>
                                            <h5 class="modal-title fw-bold mb-0">Summary Report</h5>
                                            <div class="small text-muted">
                                                Executive summary, risk queues, and type/model insights
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-{{ $perfBadge }} px-3 py-2">
                                                Performance {{ $perf }}%
                                            </span>
                                            <button type="button" class="btn-close" wire:click="closeSummaryModal"></button>
                                        </div>
                                    </div>

                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <span class="badge bg-success">Active: {{ $active }}</span>
                                        <span class="badge bg-secondary">Inactive: {{ $inactive }}</span>
                                        <span class="badge bg-success">Online: {{ $online }}</span>
                                        <span class="badge bg-warning text-dark">Offline: {{ $offline }}</span>
                                        <span class="badge bg-dark">Never: {{ $never }}</span>
                                        <span class="badge bg-light text-dark border">
                                            Data quality: Reg {{ $missingReg }}, Mobile {{ $missingMobile }}, Serial {{ $missingSerial }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-lg-8">
                                        <div class="card shadow-sm h-100 border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold text-uppercase small mb-0">Connections Trend</div>
                                                    <div class="small text-muted">connected_at grouped by day</div>
                                                </div>
                                                <span class="badge bg-light text-dark border">Interactive</span>
                                            </div>
                                            <div class="card-body py-3" wire:ignore>
                                                <canvas id="reportTrendChart" height="120"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0">
                                                <div class="fw-bold text-uppercase small mb-0">Decision Notes</div>
                                                <div class="small text-muted">What this report suggests</div>
                                            </div>
                                            <div class="card-body py-3">
                                                @if(!empty($sr['bullets']))
                                                    <div class="d-grid gap-2">
                                                        @foreach($sr['bullets'] as $b)
                                                            <div class="p-3 border rounded-3">
                                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                                    <div class="fw-semibold">{{ $b['title'] }}</div>
                                                                    <span class="badge bg-{{ $b['tagBg'] ?? 'dark' }}">
                                                                        {{ $b['tag'] ?? '' }}
                                                                    </span>
                                                                </div>
                                                                <div class="small text-muted">{{ $b['text'] }}</div>

                                                                @if(!empty($b['actionFilter']))
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-outline-dark mt-2"
                                                                            onclick="Livewire.dispatch('set-dashboard-filters', @js($b['actionFilter']))">
                                                                        Drill down
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <ul class="small text-muted mb-0">
                                                        <li><b>Never</b> high → onboarding/config/power/SIM/APN.</li>
                                                        <li><b>Offline</b> high → network coverage/server/vehicle power.</li>
                                                        <li>Use <b>Drill down</b> buttons to isolate impacted devices.</li>
                                                    </ul>
                                                @endif

                                                <div class="mt-3">
                                                    <div class="d-flex justify-content-between small text-muted">
                                                        <span>Online rate</span>
                                                        <span><b>{{ $perf }}%</b></span>
                                                    </div>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-{{ $perfBadge }}" style="width: {{ $perf }}%"></div>
                                                    </div>
                                                    <div class="small text-muted mt-2">
                                                        Online is based on <b>connected_at</b> window.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0">
                                                <div class="fw-bold text-uppercase small mb-0">Active but Long Offline</div>
                                                <div class="small text-muted">Immediate operational risk</div>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>IMEI</th>
                                                            <th>Reg</th>
                                                            <th class="text-end">Connected</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse(($sr['risk']['longOfflineActive'] ?? []) as $r)
                                                            <tr>
                                                                <td class="fw-semibold">{{ $r->imei }}</td>
                                                                <td>{{ $r->reg_number ?? '--' }}</td>
                                                                <td class="text-end">{{ $r->connected_at?->toDateTimeString() ?? '--' }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="3" class="text-center text-muted">None</td></tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0">
                                                <div class="fw-bold text-uppercase small mb-0">Never Connected</div>
                                                <div class="small text-muted">Onboarding / install follow-up</div>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>IMEI</th>
                                                            <th>Reg</th>
                                                            <th class="text-end">Created</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse(($sr['risk']['neverConnected'] ?? []) as $r)
                                                            <tr>
                                                                <td class="fw-semibold">{{ $r->imei }}</td>
                                                                <td>{{ $r->reg_number ?? '--' }}</td>
                                                                <td class="text-end">{{ $r->created_at?->toDateTimeString() ?? '--' }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="3" class="text-center text-muted">None</td></tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0">
                                                <div class="fw-bold text-uppercase small mb-0">Recently Connected</div>
                                                <div class="small text-muted">Latest activity</div>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>IMEI</th>
                                                            <th>Reg</th>
                                                            <th class="text-end">Connected</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse(($sr['risk']['recentlyConnected'] ?? []) as $r)
                                                            <tr>
                                                                <td class="fw-semibold">{{ $r->imei }}</td>
                                                                <td>{{ $r->reg_number ?? '--' }}</td>
                                                                <td class="text-end">{{ $r->connected_at?->toDateTimeString() ?? '--' }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="3" class="text-center text-muted">None</td></tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="card shadow-sm border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-uppercase small mb-0">Top Device Types</div>
                                                <span class="badge bg-light text-dark border">Count</span>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr><th>Type</th><th class="text-end">Count</th></tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse(($sr['topTypes'] ?? []) as $r)
                                                            <tr>
                                                                <td class="fw-semibold">{{ $r['label'] }}</td>
                                                                <td class="text-end">{{ $r['count'] }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="card shadow-sm border-0 dashboard-card">
                                            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-uppercase small mb-0">Top Models</div>
                                                <span class="badge bg-light text-dark border">Count</span>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                        <tr><th>Model</th><th class="text-end">Count</th></tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse(($sr['topModels'] ?? []) as $r)
                                                            <tr>
                                                                <td class="fw-semibold">{{ $r['label'] }}</td>
                                                                <td class="text-end">{{ $r['count'] }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button class="btn btn-light" wire:click="closeSummaryModal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Scripts --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let gpsPieChart = null;
                let gpsTrendChart = null;
                let reportTrendChart = null;

                function getPayload() {
                    return @json($chartPayload);
                }

                function dispatchFilter(payload) {
                    Livewire.dispatch('set-dashboard-filters', payload);
                }

                function renderMainCharts(payload) {
                    const pieCanvas = document.getElementById('gpsPieChart');
                    if (pieCanvas) {
                        if (gpsPieChart) gpsPieChart.destroy();
                        gpsPieChart = new Chart(pieCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: payload.pie.labels,
                                datasets: [{
                                    data: payload.pie.values,
                                    backgroundColor: ['#0d6efd', '#dc3545', '#6c757d'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '68%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            boxWidth: 8
                                        }
                                    }
                                },
                                onClick: (_, elements) => {
                                    if (!elements?.length) return;
                                    const idx = elements[0].index;
                                    const label = payload.pie.labels[idx];
                                    if (label === 'Online') dispatchFilter({ filterConnectivity: 'online' });
                                    if (label === 'Offline') dispatchFilter({ filterConnectivity: 'offline' });
                                    if (label === 'Never Connected') dispatchFilter({ filterConnectivity: 'never' });
                                }
                            }
                        });
                    }

                    const trendCanvas = document.getElementById('gpsTrendChart');
                    if (trendCanvas) {
                        if (gpsTrendChart) gpsTrendChart.destroy();
                        gpsTrendChart = new Chart(trendCanvas, {
                            type: 'line',
                            data: {
                                labels: payload.trend.labels,
                                datasets: [
                                    {
                                        label: 'Connections',
                                        data: payload.trend.connections,
                                        borderColor: 'rgb(13, 110, 253)',
                                        backgroundColor: 'rgba(13, 110, 253, 0.15)',
                                        tension: 0.3,
                                        fill: true,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Fuel Costs',
                                        data: payload.trend.fuel_costs,
                                        borderColor: 'rgb(255, 159, 64)',
                                        backgroundColor: 'rgba(255, 159, 64, 0.15)',
                                        tension: 0.3,
                                        fill: false,
                                        yAxisID: 'y1'
                                    },
                                    {
                                        label: 'Maintenance Costs',
                                        data: payload.trend.maintenance_costs,
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(255, 99, 132, 0.15)',
                                        tension: 0.3,
                                        fill: false,
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: { maxRotation: 45 },
                                        grid: { display: false }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        title: {
                                            display: true,
                                            text: 'Connections'
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        title: {
                                            display: true,
                                            text: 'Costs'
                                        },
                                        grid: { drawOnChartArea: false }
                                    }
                                }
                            }
                        });
                    }
                }

                function renderReportChart(payload) {
                    const canvas = document.getElementById('reportTrendChart');
                    if (!canvas) return;

                    if (reportTrendChart) reportTrendChart.destroy();
                    reportTrendChart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: payload.trend.labels,
                            datasets: [{
                                label: 'Connections per day',
                                data: payload.trend.values,
                                backgroundColor: 'rgba(13, 110, 253, 0.75)',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true }
                            }
                        }
                    });
                }

                function showVehicleDetails(regNo) {
                    if (!regNo || regNo === '--') return;
                    
                    try {
                        if (typeof showVehicleOverview === 'function') {
                            showVehicleOverview(regNo);
                        } else {
                            window.open(`/vehicle-management/onboarding/show-vehicle-details?reg_no=${regNo}`, '_blank');
                        }
                    } catch (error) {
                        console.error('Error showing vehicle details:', error);
                        window.open(`/vehicle-management/onboarding/show-vehicle-details?reg_no=${regNo}`, '_blank');
                    }
                }

                function editFuelRecord(regNo) {
                    if (!regNo || regNo === '--') return;
                    window.open(`/vehicle-management/fuel/edit?reg_no=${regNo}`, '_blank');
                }

                function editMaintenanceRecord(regNo) {
                    if (!regNo || regNo === '--') return;
                    window.open(`/vehicle-management/maintenance/edit?reg_no=${regNo}`, '_blank');
                }

                function showVehicleUsageDetails(regNo) {
                    if (!regNo || regNo === '--') return;
                    
                    try {
                        const usageData = @json($fuelUsageAnalysis);
                        const vehicleData = usageData.find(v => v.reg_no === regNo);

                        if (vehicleData) {
                            const details = formatVehicleUsageDetails(regNo, vehicleData);
                            alert(details);
                        } else {
                            alert('No usage data found for vehicle: ' + regNo);
                        }
                    } catch (error) {
                        console.error('Error showing vehicle usage details:', error);
                        alert('Error loading vehicle usage data for: ' + regNo);
                    }
                }

                function formatVehicleUsageDetails(regNo, data) {
                    return `
Vehicle: ${regNo} (${data.Type_Brand})
Type: ${data.FUEL_TYPE}
Engine: ${data.ENGINE_CAPACITY} CC

Consumption Summary:
- Total Consumed: ${data.total_fuel_consumed} L
- Total Cost: ${data.total_fuel_cost}
- Fueling Events: ${data.fueling_events}
- Average per Event: ${data.avg_fuel_per_transaction} L
- Average Price per Liter: ${data.avg_price_per_liter}

Efficiency Metrics:
- Efficiency Rating: ${data.efficiency_rating}
- Over-Issued Count: ${data.over_issued_count}
- Normal Fueling: ${data.normal_fueling_count}
- Out-of-Town Fueling: ${data.out_of_town_count}
- Override Fueling: ${data.override_count}

Tank Information:
- Main Tank: ${data.main_tank} L
- Sub Tank: ${data.sub_tank} L
- Total Capacity: ${data.total_tank_capacity} L

Performance:
- Estimated Distance Traveled: ${data.estimated_distance_traveled} km
- Days Between Fueling: ${data.days_between_fueling}
- Assigned Unit: ${data.assigned_unit}
                    `.trim();
                }

                function exportFuelData() {
                    const fuelData = @json($fuelData);
                    const csv = [
                        ['Reg No', 'Vehicle Type', 'Fuel Type', 'Issue Date', 'Qty (L)', 'Price/L', 'Total Value', 'Status'],
                        ...fuelData.map(fuel => [
                            fuel.reg_no || '',
                            fuel.Type_Brand || '',
                            fuel.FUEL_TYPE || '',
                            fuel.issue_date || '',
                            fuel.qty_issued || 0,
                            fuel.price_per_litre || 0,
                            fuel.qty_issued_value || 0,
                            fuel.Over_Issued === 'Y' ? 'Over Issued' : 'Normal'
                        ])
                    ].map(row => row.join(',')).join('\n');

                    downloadCSV(csv, 'fuel_data_export.csv');
                }

                function exportMaintenanceData() {
                    const maintenanceData = @json($maintenanceData);
                    const csv = [
                        ['Reg No', 'Vehicle Type', 'Job Card No', 'Article', 'Assignment', 'Document Date', 'Cost'],
                        ...maintenanceData.map(maintenance => [
                            maintenance.reg_no || '',
                            maintenance.Type_Brand || '',
                            maintenance.job_card_no || '',
                            maintenance.article_description || '',
                            maintenance.vehicle_assignment || '',
                            maintenance.document_date || '',
                            maintenance.value_amount || 0
                        ])
                    ].map(row => row.join(',')).join('\n');

                    downloadCSV(csv, 'maintenance_data_export.csv');
                }

                function exportFuelUsageData() {
                    const fuelUsageData = @json($fuelUsageAnalysis);
                    const csv = [
                        ['Reg No', 'Vehicle Type', 'Engine Capacity', 'Fuel Type', 'Total Consumed (L)', 'Total Cost',
                            'Fueling Events', 'Avg per Transaction', 'Avg Price/L', 'Efficiency Rating', 'Over-Issued Count',
                            'Normal Fueling', 'Out-of-Town', 'Override', 'Assigned Unit'],
                        ...fuelUsageData.map(usage => [
                            usage.reg_no || '',
                            usage.Type_Brand || '',
                            usage.ENGINE_CAPACITY || '',
                            usage.FUEL_TYPE || '',
                            usage.total_fuel_consumed || 0,
                            usage.total_fuel_cost || 0,
                            usage.fueling_events || 0,
                            usage.avg_fuel_per_transaction || 0,
                            usage.avg_price_per_liter || 0,
                            usage.efficiency_rating || '',
                            usage.over_issued_count || 0,
                            usage.normal_fueling_count || 0,
                            usage.out_of_town_count || 0,
                            usage.override_count || 0,
                            usage.assigned_unit || ''
                        ])
                    ].map(row => row.join(',')).join('\n');

                    downloadCSV(csv, 'fuel_usage_analysis_export.csv');
                }

                function downloadCSV(csv, filename) {
                    const blob = new Blob([csv], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.setAttribute('hidden', '');
                    a.setAttribute('href', url);
                    a.setAttribute('download', filename);
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }

                function showRefreshIndicator() {
                    const indicator = document.createElement('div');
                    indicator.className = 'refresh-indicator';
                    indicator.innerHTML = '<i class="fas fa-sync fa-spin"></i> Refreshing';
                    indicator.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: rgba(13,110,253,0.95);
                        color: white;
                        padding: 8px 14px;
                        border-radius: 30px;
                        font-size: 12px;
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        box-shadow: 0 10px 30px rgba(0,0,0,.12);
                    `;
                    document.body.appendChild(indicator);

                    setTimeout(() => {
                        indicator.remove();
                    }, 1000);
                }

                document.addEventListener('livewire:init', () => {
                    renderMainCharts(getPayload());
                    renderReportChart(getPayload());

                    Livewire.hook('message.processed', () => {
                        renderMainCharts(getPayload());
                        renderReportChart(getPayload());
                        showRefreshIndicator();
                    });

                    Livewire.on('charts-refresh', () => {
                        renderMainCharts(getPayload());
                        renderReportChart(getPayload());
                    });

                    const script = document.createElement('script');
                    script.src = '/modules/vehicleManagement/assets/js/vehicle_over_view.js';
                    script.onload = function() {
                        console.log('Vehicle overview script loaded successfully');
                    };
                    script.onerror = function() {
                        console.log('Vehicle overview script not found, using fallback');
                    };
                    document.head.appendChild(script);
                });
            </script>

            {{-- Optimized Custom CSS --}}
            <style>
                /* Dashboard Components */
                .dashboard-card {
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .dashboard-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
                }

                .dashboard-card .card-header {
                    border-bottom: 1px solid rgba(0,0,0,0.05);
                    background: #fff;
                    padding: 1rem 1.25rem;
                }

                .dashboard-card .card-body {
                    padding: 1.25rem;
                }

                /* Row spacing consistency */
                .row.g-3 > * {
                    margin-bottom: 1rem;
                }

                /* Card height alignment */
                .h-100 {
                    height: 100% !important;
                }

                /* Chart containers */
                .chart-container {
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                /* Interactive Elements */
                .hover-lift {
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .hover-lift:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
                }

                .summary-box {
                    background: #fff;
                    transition: background 0.2s ease;
                }

                .summary-box:hover {
                    background: #fafbfc;
                }

                /* Links & Badges */
                .vehicle-link {
                    color: #0d6efd;
                    text-decoration: none;
                    transition: color 0.2s ease;
                }

                .vehicle-link:hover {
                    color: #0a58ca;
                    text-decoration: underline;
                }

                .status-badge {
                    font-size: 0.75rem;
                    padding: 0.35rem 0.6rem;
                    border-radius: 999px;
                    font-weight: 500;
                }

                /* Chart Cards */
                .chart-card {
                    height: 280px;
                }

                .chart-card-body {
                    height: 220px;
                    padding: 1rem;
                    position: relative;
                }

                .chart-card-body canvas {
                    max-width: 100%;
                    max-height: 100%;
                }

                /* Fuel and Maintenance Cards Alignment */
                .dashboard-card .card-body .row.g-3 {
                    margin: 0;
                }

                .dashboard-card .card-body .row.g-3 > * {
                    padding: 0.5rem;
                }

                /* Ensure consistent card heights in rows */
                .row.g-3 .dashboard-card {
                    display: flex;
                    flex-direction: column;
                }

                .row.g-3 .dashboard-card .card-body {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                /* Button group alignment */
                .btn-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.25rem;
                }

                .btn-group .btn {
                    flex: 1;
                    min-width: 100px;
                }

                /* Tables & Data */
                .data-table-container {
                    max-height: 400px;
                    overflow-y: auto;
                    border-radius: 8px;
                }

                .data-table-container::-webkit-scrollbar {
                    width: 6px;
                }

                .data-table-container::-webkit-scrollbar-track {
                    background: #f8f9fa;
                    border-radius: 3px;
                }

                .data-table-container::-webkit-scrollbar-thumb {
                    background: #dee2e6;
                    border-radius: 3px;
                    transition: background 0.2s ease;
                }

                .data-table-container::-webkit-scrollbar-thumb:hover {
                    background: #adb5bd;
                }

                .table > :not(caption) > * > * {
                    vertical-align: middle;
                }

                /* Progress & Buttons */
                .progress {
                    border-radius: 999px;
                    overflow: hidden;
                }

                .btn-xs {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                    line-height: 1.2;
                    border-radius: 0.375rem;
                }

                /* Loading States */
                .loading-overlay {
                    position: absolute;
                    inset: 0;
                    background: rgba(255,255,255,0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                }

                .pulse-animation {
                    animation: pulse 2s infinite;
                }

                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }

                /* Responsive Design */
                @media (max-width: 1199.98px) {
                    .dashboard-card .card-header {
                        padding: 0.75rem 1rem;
                    }
                    .dashboard-card .card-body {
                        padding: 1rem;
                    }
                }

                @media (max-width: 991.98px) {
                    .dashboard-card .card-header {
                        padding: 0.75rem;
                    }
                    .dashboard-card .card-body {
                        padding: 0.75rem;
                    }
                    .btn-group .btn {
                        min-width: 80px;
                        font-size: 0.875rem;
                    }
                }

                @media (max-width: 768px) {
                    .dashboard-card .card-header {
                        padding: 0.5rem 0.75rem;
                    }
                    .dashboard-card .card-body {
                        padding: 0.75rem;
                    }
                    .h3 { font-size: 1.5rem !important; }
                    .h4 { font-size: 1.25rem !important; }
                    .h5 { font-size: 1rem !important; }
                    .table-sm { font-size: 0.82rem; }
                    .btn-group {
                        flex-direction: column;
                    }
                    .btn-group .btn {
                        min-width: auto;
                    }
                }

                @media (max-width: 576px) {
                    .dashboard-card .card-header {
                        padding: 0.5rem;
                    }
                    .dashboard-card .card-body {
                        padding: 0.5rem;
                    }
                    .h3 { font-size: 1.25rem !important; }
                    .h4 { font-size: 1.1rem !important; }
                    .row.g-3 > div { margin-bottom: 0.5rem; }
                    .dashboard-card .card-body .row.g-3 > * {
                        padding: 0.25rem;
                    }
                }
            </style>

            {{-- Enhanced Dashboard JavaScript --}}
            <script>
                // Initialize dashboard charts when page loads
                document.addEventListener('DOMContentLoaded', function() {
                    initializeDashboardCharts();
                });

                function initializeDashboardCharts() {
                    // Load charts with error handling
                    loadMonthlyTrends();
                    loadCostDistribution();
                    loadTopVehiclesByMetric('total_cost', null);
                }

                function loadMonthlyTrends() {
                    // Check if required libraries are available
                    if (typeof $ === 'undefined') {
                        console.warn('jQuery not available, skipping trends chart');
                        return;
                    }
                    
                    $.ajax({
                        url: '/vehicle-management/analytics/trends',
                        method: 'GET',
                        data: { months: 12 },
                        dataType: 'json',
                        beforeSend: function() {
                            if (typeof echarts !== 'undefined') {
                                const chartDom = document.getElementById('monthlyTrendsChart');
                                if (chartDom) {
                                    const myChart = echarts.init(chartDom);
                                    myChart.setOption({
                                        title: { text: 'Loading...' },
                                        graphic: [{
                                            type: 'text',
                                            left: 'center',
                                            top: 'center',
                                            style: { text: 'Loading trends...', fontSize: 16 }
                                        }]
                                    });
                                }
                            }
                        },
                        success: function(response) {
                            if (response.success && response.data) {
                                renderMonthlyTrendsChart(response.data);
                            } else {
                                console.error('Trends API returned error:', response.message);
                                showChartError('monthlyTrendsChart', 'Error loading trends');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Trends AJAX Error:', { status, error, responseText: xhr.responseText });
                            showChartError('monthlyTrendsChart', 'Failed to load trends data');
                        }
                    });
                }

                function renderMonthlyTrendsChart(trendData) {
                    const chartDom = document.getElementById('monthlyTrendsChart');
                    if (!chartDom) return;
                    
                    const myChart = echarts.init(chartDom);
                    
                    const periods = trendData.map(d => d.period);
                    const fuelCosts = trendData.map(d => parseFloat(d.fuel_cost || 0));
                    const maintenanceCosts = trendData.map(d => parseFloat(d.maintenance_cost || 0));
                    const totalCosts = trendData.map(d => parseFloat(d.total_operating_cost || 0));
                    
                    const option = {
                        title: {
                            text: 'Monthly Operating Cost Trends',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'cross' },
                            formatter: function(params) {
                                let result = params[0].name + '<br/>';
                                params.forEach(param => {
                                    result += param.marker + param.seriesName + ': ' + accounting.formatMoney(param.value, 'ZMW ') + '<br/>';
                                });
                                return result;
                            }
                        },
                        legend: {
                            data: ['Fuel Cost', 'Maintenance Cost', 'Total Cost'],
                            top: 30
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                mark: {show: true},
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar', 'stack']},
                                restore: {show: true},
                                saveAsImage: {show: true},
                            },
                            right: 20
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: periods,
                            axisLabel: { rotate: 45 }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Cost (ZMW)',
                            axisLabel: {
                                formatter: function(value) {
                                    return accounting.formatMoney(value, 'ZMW ');
                                }
                            }
                        },
                        series: [
                            {
                                name: 'Fuel Cost',
                                type: 'line',
                                data: fuelCosts,
                                smooth: true,
                                itemStyle: { color: '#91cc75' },
                                areaStyle: { opacity: 0.3 }
                            },
                            {
                                name: 'Maintenance Cost',
                                type: 'line',
                                data: maintenanceCosts,
                                smooth: true,
                                itemStyle: { color: '#fac858' },
                                areaStyle: { opacity: 0.3 }
                            },
                            {
                                name: 'Total Cost',
                                type: 'line',
                                data: totalCosts,
                                smooth: true,
                                itemStyle: { color: '#ee6666' },
                                lineStyle: { width: 3, type: 'dashed' }
                            }
                        ]
                    };
                    
                    myChart.setOption(option);
                    
                    // Responsive chart
                    window.addEventListener('resize', function() {
                        myChart.resize();
                    });
                }

                function loadCostDistribution() {
                    // Check if required libraries are available
                    if (typeof $ === 'undefined') {
                        console.warn('jQuery not available, skipping cost distribution chart');
                        return;
                    }
                    
                    $.ajax({
                        url: '/vehicle-management/analytics/cost-distribution',
                        method: 'GET',
                        data: { days: 30 },
                        success: function(response) {
                            if (response.success && response.data) {
                                renderCostDistributionChart(response.data);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle authentication redirects gracefully
                            if (xhr.status === 302 || xhr.status === 401) {
                                console.warn('Cost distribution requires authentication - skipping chart');
                                        return;
                            }
                            showChartError('costDistributionChart', 'Failed to load cost distribution');
                        }
                    });
                }

                function renderCostDistributionChart(data) {
                    const chartDom = document.getElementById('costDistributionChart');
                    if (!chartDom) return;
                    
                    const myChart = echarts.init(chartDom);
                    
                    const fuelCost = parseFloat(data.fuel_cost || 0);
                    const maintenanceCost = parseFloat(data.maintenance_cost || 0);
                    const total = fuelCost + maintenanceCost;
                    
                    const fuelPercentage = total > 0 ? ((fuelCost / total) * 100).toFixed(2) : 0;
                    const maintenancePercentage = total > 0 ? ((maintenanceCost / total) * 100).toFixed(2) : 0;
                    
                    const option = {
                        title: {
                            text: 'Cost Distribution',
                            subtext: 'Fuel vs Maintenance',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b}: {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['Fuel', 'Maintenance']
                        },
                        series: [
                            {
                                name: 'Cost Breakdown',
                                type: 'pie',
                                radius: '50%',
                                data: [
                                    {value: fuelCost, name: 'Fuel', itemStyle: {color: '#91cc75'}},
                                    {value: maintenanceCost, name: 'Maintenance', itemStyle: {color: '#fac858'}}
                                ],
                                emphasis: {
                                    itemStyle: {
                                        shadowBlur: 10,
                                        shadowOffsetX: 0,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                },
                                label: {
                                    formatter: '{b}: ' + accounting.formatMoney('{c}', 'ZMW ') + ' ({d}%)'
                                }
                            }
                        ]
                    };
                    
                    myChart.setOption(option);
                    
                    // Responsive chart
                    window.addEventListener('resize', function() {
                        myChart.resize();
                    });
                }

                function loadTopVehiclesByMetric(metric, buttonElement) {
                    // Check if required libraries are available
                    if (typeof $ === 'undefined') {
                        console.warn('jQuery not available, skipping top vehicles chart');
                        return;
                    }
                    
                    // Update active button only if buttonElement is provided
                    if (buttonElement) {
                        document.querySelectorAll('.btn-group .btn').forEach(btn => {
                            btn.classList.remove('active');
                        });
                        buttonElement.classList.add('active');
                    }
                    
                    $.ajax({
                        url: '/vehicle-management/analytics/top-vehicles',
                        method: 'GET',
                        data: { metric: metric, limit: 10 },
                        beforeSend: function() {
                            const chartDom = document.getElementById('topVehiclesChart');
                            if (chartDom && typeof echarts !== 'undefined') {
                                const myChart = echarts.init(chartDom);
                                myChart.setOption({
                                    title: { text: 'Loading...' },
                                    graphic: [{
                                        type: 'text',
                                        left: 'center',
                                        top: 'center',
                                        style: { text: 'Loading vehicle data...', fontSize: 16 }
                                    }]
                                });
                            }
                        },
                        success: function(response) {
                            if (response.success && response.data) {
                                renderTopVehiclesChart(response.data, metric);
                                updateTopVehiclesList(response.data, metric);
                            } else {
                                showChartError('topVehiclesChart', 'Error loading vehicle data');
                            }
                        },
                        error: function(xhr, status, error) {
                            showChartError('topVehiclesChart', 'Failed to load vehicle data');
                        }
                    });
                }

                function renderTopVehiclesChart(vehicleData, metric) {
                    const chartDom = document.getElementById('topVehiclesChart');
                    if (!chartDom) return;
                    
                    const myChart = echarts.init(chartDom);
                    
                    const vehicles = vehicleData.slice(0, 10);
                    const vehicleNames = vehicles.map(v => v.reg_no || v.vehicle_reg_no);
                    const values = vehicles.map(v => parseFloat(v[metric] || 0));
                    
                    const metricLabels = {
                        'total_cost': 'Operating Cost',
                        'fuel_cost': 'Fuel Cost',
                        'maintenance_cost': 'Maintenance Cost',
                        'maintenance_events': 'Maintenance Events'
                    };
                    
                    const option = {
                        title: {
                            text: 'Top Vehicles by ' + metricLabels[metric],
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: function(params) {
                                const value = params[0].value;
                                if (metric === 'maintenance_events') {
                                    return params[0].name + '<br/>' + params[0].seriesName + ': ' + value + ' events';
                                } else {
                                    return params[0].name + '<br/>' + params[0].seriesName + ': ' + accounting.formatMoney(value, 'ZMW ');
                                }
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '15%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'value',
                            name: metric === 'maintenance_events' ? 'Events' : 'Cost (ZMW)',
                            axisLabel: {
                                formatter: function(value) {
                                    if (metric === 'maintenance_events') {
                                        return value;
                                    }
                                    return accounting.formatMoney(value, 'ZMW ');
                                }
                            }
                        },
                        yAxis: {
                            type: 'category',
                            data: vehicleNames,
                            axisLabel: { interval: 0 }
                        },
                        series: [
                            {
                                name: metricLabels[metric],
                                type: 'bar',
                                data: values,
                                itemStyle: {
                                    color: function(params) {
                                        const colors = ['#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de'];
                                        return colors[params.dataIndex % colors.length];
                                    }
                                }
                            }
                        ]
                    };
                    
                    myChart.setOption(option);
                    
                    // Responsive chart
                    window.addEventListener('resize', function() {
                        myChart.resize();
                    });
                }

                function updateTopVehiclesList(vehicleData, metric) {
                    const listContainer = document.getElementById('topVehiclesList');
                    if (!listContainer) return;
                    
                    const vehicles = vehicleData.slice(0, 5);
                    const metricLabels = {
                        'total_cost': 'Operating Cost',
                        'fuel_cost': 'Fuel Cost',
                        'maintenance_cost': 'Maintenance Cost',
                        'maintenance_events': 'Maintenance Events'
                    };
                    
                    let html = '<div class="fw-bold mb-2">Top 5 Vehicles</div>';
                    vehicles.forEach((vehicle, index) => {
                        const regNo = vehicle.reg_no || vehicle.vehicle_reg_no;
                        const value = parseFloat(vehicle[metric] || 0);
                        const model = vehicle.model_name || 'Unknown Model';
                        
                        let formattedValue;
                        if (metric === 'maintenance_events') {
                            formattedValue = value + ' events';
                        } else {
                            formattedValue = accounting.formatMoney(value, 'ZMW ');
                        }
                        
                        html += `
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-semibold">${regNo}</div>
                                    <div class="text-muted small">${model}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">${formattedValue}</div>
                                    <div class="text-muted small">#${index + 1}</div>
                                </div>
                            </div>
                        `;
                    });
                    
                    listContainer.innerHTML = html;
                }

                function showChartError(chartId, message) {
                    const chartDom = document.getElementById(chartId);
                    if (chartDom && typeof echarts !== 'undefined') {
                        const myChart = echarts.init(chartDom);
                        myChart.setOption({
                            title: { text: 'Error' },
                            graphic: [{
                                type: 'text',
                                left: 'center',
                                top: 'center',
                                style: { 
                                    text: message, 
                                    fontSize: 14,
                                    fill: '#dc3545'
                                }
                            }]
                        });
                    }
                }

                function refreshMonthlyTrends() {
                    loadMonthlyTrends();
                }

                // Auto-refresh dashboard data
                setInterval(function() {
                    if (document.visibilityState === 'visible') {
                        loadMonthlyTrends();
                        loadCostDistribution();
                    }
                }, 300000); // Refresh every 5 minutes
            </script>

        </div>
    </div>
</section>