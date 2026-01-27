<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="All GPS Dashboard"
                      :activeCrumb="'GPS Dashboard'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <div class="container-fluid">
        <div wire:poll.15s>

            @php
                $k = $kpis;
                $total = (int)($k['total'] ?? 0);
                $online = (int)($k['online'] ?? 0);
                $score  = $total > 0 ? (int) round(($online / $total) * 100) : 0;
                $badge  = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
            @endphp

            {{-- Header --}}
            <div class="card shadow-sm mb-2">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="pe-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="mb-0 fw-bold text-uppercase">GPS Operations Dashboard</h5>
                                <span class="badge bg-info text-dark">Live</span>
                                <span class="badge bg-light text-dark">Online Window: <b>{{ $onlineWindowMinutes }}</b>m</span>
                                <span class="badge bg-light text-dark">Alert: <b>{{ $alertOfflineMinutes }}</b>m</span>
                                <span class="badge bg-{{ $badge }}">Performance {{ $score }}%</span>
                            </div>
                            <div class="small text-muted mt-1">
                                Online cutoff (connected_at): {{ $cutoff->toDateTimeString() }}
                            </div>

                            <div class="small mt-1" wire:loading>
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

            {{-- Compact KPI grid (no gaps, equal height) --}}
            <div class="row g-2 mb-2">
                @php
                    $kpiCards = [
                        ['title'=>'Total Devices','value'=>$k['total'] ?? 0,'hint'=>'All units','border'=>'dark','text'=>'dark'],
                        ['title'=>'Online','value'=>$k['online'] ?? 0,'hint'=>'Within window','border'=>'success','text'=>'success'],
                        ['title'=>'Offline','value'=>$k['offline'] ?? 0,'hint'=>'Older than window','border'=>'warning','text'=>'warning'],
                        ['title'=>'Never Connected','value'=>$k['neverSeen'] ?? 0,'hint'=>'No connected_at','border'=>'secondary','text'=>'secondary'],
                        ['title'=>'Seen in Range','value'=>$k['rangeSeen'] ?? 0,'hint'=>'Period activity','border'=>'primary','text'=>'primary'],
                        ['title'=>'Performance','value'=>"$score%",'hint'=>'Online / Total','border'=>$badge,'text'=>$badge],
                    ];
                @endphp

                @foreach($kpiCards as $c)
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card shadow-sm h-100 border-start border-4 border-{{ $c['border'] }}">
                            <div class="card-body py-2 d-flex flex-column justify-content-between">
                                <div class="small text-muted text-uppercase fw-semibold">{{ $c['title'] }}</div>
                                <div class="h4 mb-0 fw-bold text-{{ $c['text'] }}">{{ $c['value'] }}</div>
                                <div class="small text-muted">{{ $c['hint'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Main Grid: Left (charts + insight) / Right (actions) --}}
            <div class="row g-2 mb-2">

                {{-- LEFT --}}
                <div class="col-lg-8">
                    <div class="row g-2">

                        {{-- Connectivity Pie --}}
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2">
                                    <div class="fw-bold text-uppercase small mb-0">Connectivity Mix</div>
                                    <div class="small text-muted">Online/Offline/Never</div>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsPieChart" height="190"></canvas>
                                </div>
                                <div class="card-footer bg-white py-2 small text-muted">
                                    Tip: click segments to filter table.
                                </div>
                            </div>
                        </div>

                        {{-- Trend Line --}}
                        <div class="col-md-8">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-uppercase small mb-0">Connections Trend</div>
                                        <div class="small text-muted">Daily connections (period)</div>
                                    </div>
                                    <span class="badge bg-light text-dark">connected_at</span>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsTrendChart" height="140"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- AI Insight + Performance Gauge + Status Bar --}}
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-robot"></i>
                                        <div>
                                            <div class="fw-bold text-uppercase small mb-0">AI Insight & Performance</div>
                                            <div class="small text-muted">Decision view (click charts to drill down)</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $badge }}">Score {{ $score }}%</span>
                                </div>

                                <div class="card-body py-2">
                                    <div class="small text-muted mb-2">{{ $this->aiInsight }}</div>

                                    <div class="row g-2 align-items-stretch">
                                        {{-- Performance Gauge (replaces 2nd pie) --}}
                                        <div class="col-md-5">
                                            <div class="p-2 border rounded h-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="small text-muted text-uppercase fw-semibold">Performance Gauge</div>
                                                    <span class="badge bg-{{ $badge }}">{{ $score }}%</span>
                                                </div>
                                                <div class="small text-muted mb-2">Online vs total (window-based)</div>

                                                <div wire:ignore>
                                                    <canvas id="perfGauge" height="170"></canvas>
                                                </div>

                                                <div class="small text-muted mt-2">
                                                    Targets: <b>80%+</b> healthy, <b>50–79%</b> watch, <b>&lt;50%</b> critical.
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status Bar --}}
                                        <div class="col-md-7">
                                            <div class="p-2 border rounded h-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="small text-muted text-uppercase fw-semibold">Active Coverage</div>
                                                    <span class="badge bg-dark">
                                                        {{ (int)($chartPayload['performance']['active'] ?? 0) }} Active
                                                    </span>
                                                </div>
                                                <div class="small text-muted mb-2">Status mapping: <b>1 = Active</b>, else Inactive</div>

                                                <div wire:ignore>
                                                    <canvas id="statusBar" height="120"></canvas>
                                                </div>

                                                <div class="small text-muted mt-2">
                                                    Tip: click bars to filter Active/Inactive.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-2">
                            <div class="fw-bold text-uppercase small mb-0">Decision Summary</div>
                            <div class="small text-muted">Fast signals for action</div>
                        </div>
                        <div class="card-body py-2">
                            <div class="p-2 border rounded mb-2">
                                <div class="fw-semibold">If Never Connected is high</div>
                                <div class="small text-muted">Focus: onboarding, SIM/APN, wiring/power, wrong server/port.</div>
                            </div>

                            <div class="p-2 border rounded mb-2">
                                <div class="fw-semibold">If Offline is high</div>
                                <div class="small text-muted">Focus: network coverage, tracking server downtime, vehicle power, device health.</div>
                            </div>

                            <div class="p-2 border rounded">
                                <div class="fw-semibold">Use chart clicks to drill down</div>
                                <div class="small text-muted">Connectivity pie → filters table; Status bar → filters Active/Inactive.</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Table --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <div class="fw-bold text-uppercase small">Devices Table</div>
                        <div class="small text-muted">Connectivity is based on connected_at</div>
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
                            <th class="text-uppercase small">Connected At</th>
                            <th class="text-uppercase small text-end">Conn</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($devices as $d)
                            @php
                                $statusLabel = ((int)($d->status ?? 0) === 1) ? 'Active' : 'Inactive';
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
                            <tr><td colspan="8" class="text-center text-muted p-3">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-body py-2">
                    {{ $devices->links() }}
                </div>
            </div>

            {{-- Scripts --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let gpsPieChart = null;
                let gpsTrendChart = null;
                let statusBar = null;
                let perfGauge = null;

                function getPayload() { return @json($chartPayload); }

                function dispatchFilter(payload) {
                    Livewire.dispatch('set-dashboard-filters', payload);
                }

                function renderCharts(payload) {
                    // 1) Connectivity Pie
                    const pieCanvas = document.getElementById('gpsPieChart');
                    if (pieCanvas) {
                        if (gpsPieChart) gpsPieChart.destroy();
                        gpsPieChart = new Chart(pieCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: payload.pie.labels,
                                datasets: [{ data: payload.pie.values }]
                            },
                            options: {
                                responsive: true,
                                cutout: '65%',
                                plugins: {
                                    legend: { position: 'bottom' },
                                    tooltip: { enabled: true }
                                },
                                onClick: (_, elements) => {
                                    if (!elements?.length) return;
                                    const idx = elements[0].index;
                                    const label = payload.pie.labels[idx];

                                    if (label === 'Online') dispatchFilter({filterConnectivity: 'online'});
                                    if (label === 'Offline') dispatchFilter({filterConnectivity: 'offline'});
                                    if (label === 'Never Connected') dispatchFilter({filterConnectivity: 'never'});
                                }
                            }
                        });
                    }

                    // 2) Trend
                    const trendCanvas = document.getElementById('gpsTrendChart');
                    if (trendCanvas) {
                        if (gpsTrendChart) gpsTrendChart.destroy();
                        gpsTrendChart = new Chart(trendCanvas, {
                            type: 'line',
                            data: {
                                labels: payload.trend.labels,
                                datasets: [{
                                    label: 'Connections',
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

                    // 3) Performance Gauge (semi doughnut) — replaces 2nd pie
                    const p = payload.performance;
                    const perfCanvas = document.getElementById('perfGauge');
                    if (perfCanvas) {
                        if (perfGauge) perfGauge.destroy();

                        const perf = parseInt(p.perf ?? 0);
                        const remaining = Math.max(0, 100 - perf);

                        perfGauge = new Chart(perfCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: ['Performance', 'Remaining'],
                                datasets: [{
                                    data: [perf, remaining],
                                }]
                            },
                            options: {
                                responsive: true,
                                rotation: -90,       // start
                                circumference: 180,  // half circle
                                cutout: '75%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: (ctx) => ctx.label === 'Performance'
                                                ? `Performance: ${perf}%`
                                                : `Remaining: ${remaining}%`
                                        }
                                    }
                                },
                                onClick: () => {
                                    // clicking gauge resets connectivity filter (optional)
                                    dispatchFilter({filterConnectivity: 'all'});
                                }
                            }
                        });
                    }

                    // 4) Status Bar
                    const statusCanvas = document.getElementById('statusBar');
                    if (statusCanvas) {
                        if (statusBar) statusBar.destroy();
                        statusBar = new Chart(statusCanvas, {
                            type: 'bar',
                            data: {
                                labels: ['Active', 'Inactive'],
                                datasets: [{
                                    label: 'Devices',
                                    data: [p.active, p.inactive]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                onClick: (_, elements) => {
                                    if (!elements?.length) return;
                                    const idx = elements[0].index;
                                    if (idx === 0) dispatchFilter({filterStatus: 'active'});
                                    if (idx === 1) dispatchFilter({filterStatus: 'inactive'});
                                }
                            }
                        });
                    }
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
