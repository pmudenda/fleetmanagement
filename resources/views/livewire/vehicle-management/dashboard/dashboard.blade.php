<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="All GPS Dashboard"
                      :activeCrumb="'GPS Dashboard'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <div class="container-fluid">
        <div wire:poll.15s>

            <div class="card shadow-sm mb-2">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="mb-0 fw-bold text-uppercase">GPS Dashboard</h5>
                                <span class="badge bg-info text-dark">Live</span>
                                <span class="badge bg-light text-dark">
                                    Online Window: <b>{{ $onlineWindowMinutes }}</b>m
                                </span>
                                <span class="badge bg-light text-dark">
                                    Alert: <b>{{ $alertOfflineMinutes }}</b>m
                                </span>
                            </div>
                            <div class="small text-muted">
                                Cutoff online: {{ $cutoff->toDateTimeString() }} |
                                Alert cutoff: {{ $alertCutoff->toDateTimeString() }}
                            </div>

                            <div class="small" wire:loading>
                                <span class="text-primary"><i class="fas fa-spinner fa-spin"></i> Updating...</span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-end align-items-end">
                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">From</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="dateFrom" style="max-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">To</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="dateTo" style="max-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">Online</label>
                                <select class="form-select form-select-sm" wire:model.live="onlineWindowMinutes" style="max-width: 140px;">
                                    <option value="5">5m</option>
                                    <option value="10">10m</option>
                                    <option value="15">15m</option>
                                    <option value="30">30m</option>
                                    <option value="60">60m</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">Alerts</label>
                                <select class="form-select form-select-sm" wire:model.live="alertOfflineMinutes" style="max-width: 140px;">
                                    <option value="15">15m</option>
                                    <option value="30">30m</option>
                                    <option value="60">60m</option>
                                    <option value="120">120m</option>
                                </select>
                            </div>

                            <button class="btn btn-sm btn-outline-primary" wire:click="refreshNow">
                                <i class="fas fa-sync"></i> Refresh
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" wire:click.prevent="exportCsv">
                                            CSV (Filtered)
                                        </a>
                                    </li>
                                    <li><span class="dropdown-item text-muted">PDF / Excel (hook ready)</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mini KPI Row (more cards fit on one line) --}}
            <div class="row g-2 mb-2">
                @php
                    $k = $kpis;
                    $total = (int)($k['total'] ?? 0);
                    $online = (int)($k['online'] ?? 0);
                    $never  = (int)($k['neverSeen'] ?? 0);
                    $score  = $total > 0 ? (int) round(($online / $total) * 100) : 0;
                    $badge = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                @endphp

                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Total</div>
                            <div class="h4 mb-0 fw-bold">{{ $k['total'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm border-start border-4 border-success">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Online</div>
                            <div class="h4 mb-0 fw-bold text-success">{{ $k['online'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm border-start border-4 border-warning">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Offline</div>
                            <div class="h4 mb-0 fw-bold text-warning">{{ $k['offline'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm border-start border-4 border-secondary">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Never</div>
                            <div class="h4 mb-0 fw-bold">{{ $k['neverSeen'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm border-start border-4 border-primary">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Seen in Range</div>
                            <div class="h4 mb-0 fw-bold text-primary">{{ $k['rangeSeen'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <div class="card shadow-sm border-start border-4 border-{{ $badge }}">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">AI Health</div>
                            <div class="h4 mb-0 fw-bold text-{{ $badge }}">{{ $score }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts + Alerts / Root Cause in same row --}}
            <div class="row g-2 mb-2">
                <div class="col-lg-7">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2">
                                    <div class="fw-bold text-uppercase small mb-0">Connectivity</div>
                                    <div class="small text-muted">Pie</div>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsPieChart" height="170"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-uppercase small mb-0">Reporting Trend</div>
                                        <div class="small text-muted">Selected period</div>
                                    </div>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsTrendChart" height="110"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AI Insight (compact) --}}
                    <div class="card shadow-sm mt-2">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-robot"></i>
                                <div class="fw-bold text-uppercase small mb-0">AI Insight</div>
                                <span class="badge bg-{{ $badge }}">Performance {{ $score }}%</span>
                            </div>
                            <span class="badge bg-light text-dark">Auto</span>
                        </div>
                        <div class="card-body py-2">
                            <div class="small text-muted">{{ $this->aiInsight }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    {{-- Alerts --}}
                    <div class="card shadow-sm mb-2">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-uppercase small">🚨 Alerts</div>
                            <span class="badge bg-light text-dark">Offline &gt; {{ $alertOfflineMinutes }}m</span>
                        </div>
                        <div class="card-body py-2">
                            <div class="small fw-semibold text-uppercase text-muted mb-1">Active but long-offline</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-2">
                                    <thead class="table-light">
                                    <tr>
                                        <th>IMEI</th>
                                        <th>Reg</th>
                                        <th class="text-end">Last Seen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($alerts['offlineLong'] as $a)
                                        <tr>
                                            <td class="fw-semibold">{{ $a->imei }}</td>
                                            <td>{{ $a->reg_number ?? '--' }}</td>
                                            <td class="text-end">{{ $a->last_seen_at?->toDateTimeString() ?? '--' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No critical offline alerts.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="small fw-semibold text-uppercase text-muted mb-1">Never seen (new / onboarding)</div>
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
                                    @forelse($alerts['neverSeen'] as $a)
                                        <tr>
                                            <td class="fw-semibold">{{ $a->imei }}</td>
                                            <td>{{ $a->reg_number ?? '--' }}</td>
                                            <td class="text-end">{{ $a->created_at?->toDateTimeString() ?? '--' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No never-seen devices.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Root causes --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-2">
                            <div class="fw-bold text-uppercase small">🧠 Root-cause Grouping</div>
                            <div class="small text-muted">Patterns from device behavior</div>
                        </div>
                        <div class="card-body py-2">
                            <div class="row g-2">
                                @foreach($rootCauses as $rc)
                                    <div class="col-12">
                                        <div class="p-2 border rounded">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="fw-semibold">{{ $rc['title'] }}</div>
                                                <span class="badge bg-dark">{{ $rc['count'] }}</span>
                                            </div>
                                            <div class="small text-muted">{{ $rc['hint'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-uppercase small">📈 Uptime Score (Top 10)</div>
                            <span class="badge bg-light text-dark">Approx</span>
                        </div>
                        <div class="card-body py-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>IMEI</th>
                                        <th>Reg</th>
                                        <th class="text-end">Score</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($uptime as $u)
                                        @php
                                            $c = $u['score'] >= 80 ? 'success' : ($u['score'] >= 50 ? 'warning' : 'danger');
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $u['imei'] }}</td>
                                            <td>{{ $u['reg'] ?? '--' }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $c }}">{{ $u['score'] }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="small text-muted mt-2">
                                Note: Score is an approximation from <b>activity in date range</b> + <b>current online status</b>.
                                For true uptime %, we’d compute from tracking pings/history.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <div class="fw-bold text-uppercase small">Recent / Live Device Stats</div>
                                <div class="small text-muted">Filter without losing live refresh</div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       placeholder="Search IMEI / Serial / Reg / Model / Mobile..."
                                       wire:model.live.debounce.400ms="search"
                                       style="max-width: 240px;">

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
                                    <th class="text-uppercase small">Last Seen</th>
                                    <th class="text-uppercase small text-end">Conn</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($devices as $d)
                                    @php
                                        $isOnline = $d->last_seen_at && $d->last_seen_at >= $cutoff;
                                        $isNever  = is_null($d->last_seen_at);
                                        $rowWarn  = (!$isOnline && ($d->status ?? 'inactive') === 'active' && !$isNever);
                                    @endphp

                                    <tr class="{{ $rowWarn ? 'table-warning' : '' }}">
                                        <td class="fw-semibold">{{ $d->imei }}</td>
                                        <td>{{ $d->model ?? '--' }}</td>
                                        <td>{{ $d->type ?? '--' }}</td>
                                        <td>{{ $d->reg_number ?? '--' }}</td>
                                        <td>{{ $d->mobile_number ?? '--' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($d->status ?? 'inactive') === 'active' ? 'success' : 'secondary' }}">
                                                {{ $d->status ?? 'inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $d->last_seen_at?->toDateTimeString() ?? '--' }}</td>
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
                                    <tr><td colspan="8" class="text-center text-muted p-3">No records found.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-body py-2">
                            {{ $devices->links() }}
                        </div>
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let gpsPieChart = null;
                let gpsTrendChart = null;

                function renderCharts(payload) {
                    const pieCanvas = document.getElementById('gpsPieChart');
                    const trendCanvas = document.getElementById('gpsTrendChart');

                    if (pieCanvas) {
                        if (gpsPieChart) gpsPieChart.destroy();
                        gpsPieChart = new Chart(pieCanvas, {
                            type: 'pie',
                            data: {
                                labels: payload.pie.labels,
                                datasets: [{ data: payload.pie.values }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { position: 'bottom' } }
                            }
                        });
                    }

                    if (trendCanvas) {
                        if (gpsTrendChart) gpsTrendChart.destroy();
                        gpsTrendChart = new Chart(trendCanvas, {
                            type: 'line',
                            data: {
                                labels: payload.trend.labels,
                                datasets: [{
                                    label: 'Devices reporting',
                                    data: payload.trend.values,
                                    tension: 0.25,
                                    fill: false
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: true } },
                                scales: { x: { ticks: { maxRotation: 0 } } }
                            }
                        });
                    }
                }

                function getPayload() {
                    return @json($chartPayload);
                }

                document.addEventListener('livewire:init', () => {
                    renderCharts(getPayload());

                    Livewire.hook('message.processed', () => {
                        renderCharts(getPayload());
                    });

                    Livewire.on('charts-refresh', () => {
                        renderCharts(getPayload());
                    });
                });
            </script>
        </div>
    </div>
</section>
