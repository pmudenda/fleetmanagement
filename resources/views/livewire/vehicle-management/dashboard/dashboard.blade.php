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

                $ds = $decisionSummary ?? null;
            @endphp


            <div class="card shadow-sm mb-2">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="mb-0 fw-bold text-uppercase">GPS Dashboard</h5>
                                <span class="badge bg-info text-dark">Live</span>

                                <span class="badge bg-light text-dark">
                                    Online Window: <b>{{ $onlineWindowMinutes }}</b>m
                                </span>

                                <span class="badge bg-light text-dark">
                                    Alert: <b>{{ $alertOfflineMinutes }}</b>m
                                </span>

                                <span class="badge bg-light text-dark">
                                    Cutoff: <b>{{ $cutoff->toDateTimeString() }}</b>
                                </span>
                            </div>

                            <div class="small" wire:loading>
                                <span class="text-primary">
                                    <i class="fas fa-spinner fa-spin"></i> Updating...
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-end align-items-end">
                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">From</label>
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="dateFrom"
                                       style="max-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">To</label>
                                <input type="date"
                                       class="form-control form-control-sm"
                                       wire:model.live="dateTo"
                                       style="max-width: 150px;">
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">Online</label>
                                <select class="form-select form-select-sm"
                                        wire:model.live="onlineWindowMinutes"
                                        style="max-width: 140px;">
                                    <option value="5">5m</option>
                                    <option value="10">10m</option>
                                    <option value="15">15m</option>
                                    <option value="30">30m</option>
                                    <option value="60">60m</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label mb-0 small fw-semibold text-uppercase text-muted">Alerts</label>
                                <select class="form-select form-select-sm"
                                        wire:model.live="alertOfflineMinutes"
                                        style="max-width: 140px;">
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
                                        <a class="dropdown-item" href="#"
                                           wire:click.prevent="exportCsv">
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


            <div class="row g-2 mb-2">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Total</div>
                            <div class="h4 mb-0 fw-bold">{{ $k['total'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100 border-start border-4 border-success">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Online</div>
                            <div class="h4 mb-0 fw-bold text-success">{{ $k['online'] ?? 0 }}</div>
                            <div class="small text-muted">connected_at ≥ cutoff</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100 border-start border-4 border-warning">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Offline</div>
                            <div class="h4 mb-0 fw-bold text-warning">{{ $k['offline'] ?? 0 }}</div>
                            <div class="small text-muted">connected_at &lt; cutoff</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100 border-start border-4 border-secondary">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Never</div>
                            <div class="h4 mb-0 fw-bold">{{ $k['neverSeen'] ?? 0 }}</div>
                            <div class="small text-muted">no connected_at</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100 border-start border-4 border-primary">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Seen in Range</div>
                            <div class="h4 mb-0 fw-bold text-primary">{{ $k['rangeSeen'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card shadow-sm h-100 border-start border-4 border-{{ $badge }}">
                        <div class="card-body py-2">
                            <div class="small text-muted text-uppercase fw-semibold">Performance</div>
                            <div class="h4 mb-0 fw-bold text-{{ $badge }}">{{ $score }}%</div>
                            <div class="small text-muted">online vs total</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row g-2 mb-2">
                {{-- LEFT --}}
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2">
                                    <div class="fw-bold text-uppercase small mb-0">Connectivity</div>
                                    <div class="small text-muted">Click segments to filter table</div>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsPieChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-2">
                                    <div class="fw-bold text-uppercase small mb-0">Trend</div>
                                    <div class="small text-muted">Connections per day (connected_at)</div>
                                </div>
                                <div class="card-body py-2" wire:ignore>
                                    <canvas id="gpsTrendChart" height="125"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow-sm mt-2">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-robot"></i>
                                <div class="fw-bold text-uppercase small mb-0">AI Insight</div>
                                <span class="badge bg-{{ $badge }}">Performance {{ $score }}%</span>
                            </div>
                            <span class="badge bg-light text-dark">Interactive</span>
                        </div>

                        <div class="card-body py-2">
                            <div class="small text-muted mb-2">{{ $this->aiInsight }}</div>

                            <div class="p-2 border rounded">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Overall performance</span>
                                    <span><b>{{ $score }}%</b></span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $badge }}" style="width: {{ $score }}%"></div>
                                </div>

                                <div class="mt-2 small text-muted">
                                    Online uses <b>connected_at</b> within {{ $onlineWindowMinutes }} minutes. Click charts to drill down.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-2">
                            <div class="fw-bold text-uppercase small mb-0">Decision Summary</div>
                            <div class="small text-muted">Fast signals for action</div>
                        </div>

                        <div class="card-body py-2">

                            @if($ds && !empty($ds['bullets']))
                                <div class="d-grid gap-2">
                                    @foreach($ds['bullets'] as $b)
                                        <div class="p-2 border rounded">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
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
                                    <div class="small text-muted">Connectivity + Trend help identify patterns.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow-sm">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
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

                                $statusLabel = method_exists($d, 'getAttribute') ? ($d->status_label ?? (((int)($d->status ?? 0) === 1) ? 'Active' : 'Inactive')) : (((int)($d->status ?? 0) === 1) ? 'Active' : 'Inactive');

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

            {{-- Modal (no extra libs) --}}
{{--            @if($showSummaryModal)--}}
{{--                <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:click.self="closeSummaryModal">--}}
{{--                    <div class="modal-dialog modal-xl modal-dialog-scrollable">--}}
{{--                        <div class="modal-content" wire:click.stop>--}}
{{--                            <div class="modal-header">--}}
{{--                                <div>--}}
{{--                                    <h5 class="modal-title fw-bold">Summary Report</h5>--}}
{{--                                    <div class="small text-muted">Trends, top types/models, and decision hints</div>--}}
{{--                                </div>--}}
{{--                                <button type="button" class="btn-close" wire:click="closeSummaryModal"></button>--}}
{{--                            </div>--}}

{{--                            <div class="modal-body">--}}
{{--                                <div class="row g-2">--}}
{{--                                    <div class="col-lg-8">--}}
{{--                                        <div class="card shadow-sm">--}}
{{--                                            <div class="card-header bg-white py-2">--}}
{{--                                                <div class="fw-bold text-uppercase small mb-0">Connections Trend</div>--}}
{{--                                                <div class="small text-muted">connected_at grouped by day</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body py-2" wire:ignore>--}}
{{--                                                <canvas id="reportTrendChart" height="120"></canvas>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-lg-4">--}}
{{--                                        <div class="card shadow-sm h-100">--}}
{{--                                            <div class="card-header bg-white py-2">--}}
{{--                                                <div class="fw-bold text-uppercase small mb-0">Decision Notes</div>--}}
{{--                                                <div class="small text-muted">What this report suggests</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body py-2">--}}
{{--                                                <ul class="small text-muted mb-0">--}}
{{--                                                    <li><b>Never</b> high → onboarding/config/power/SIM/APN.</li>--}}
{{--                                                    <li><b>Offline</b> high → network coverage/server/vehicle power.</li>--}}
{{--                                                    <li>Use <b>Drill down</b> buttons to isolate impacted devices.</li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-lg-6">--}}
{{--                                        <div class="card shadow-sm">--}}
{{--                                            <div class="card-header bg-white py-2">--}}
{{--                                                <div class="fw-bold text-uppercase small mb-0">Top Device Types</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body py-2">--}}
{{--                                                <div class="table-responsive">--}}
{{--                                                    <table class="table table-sm mb-0">--}}
{{--                                                        <thead class="table-light">--}}
{{--                                                        <tr><th>Type</th><th class="text-end">Count</th></tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                        @foreach(($summaryReport['topTypes'] ?? []) as $r)--}}
{{--                                                            <tr>--}}
{{--                                                                <td class="fw-semibold">{{ $r['label'] }}</td>--}}
{{--                                                                <td class="text-end">{{ $r['count'] }}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-lg-6">--}}
{{--                                        <div class="card shadow-sm">--}}
{{--                                            <div class="card-header bg-white py-2">--}}
{{--                                                <div class="fw-bold text-uppercase small mb-0">Top Models</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body py-2">--}}
{{--                                                <div class="table-responsive">--}}
{{--                                                    <table class="table table-sm mb-0">--}}
{{--                                                        <thead class="table-light">--}}
{{--                                                        <tr><th>Model</th><th class="text-end">Count</th></tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                        @foreach(($summaryReport['topModels'] ?? []) as $r)--}}
{{--                                                            <tr>--}}
{{--                                                                <td class="fw-semibold">{{ $r['label'] }}</td>--}}
{{--                                                                <td class="text-end">{{ $r['count'] }}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="modal-footer">--}}
{{--                                <button class="btn btn-light" wire:click="closeSummaryModal">Close</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}

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

                            <div class="modal-header align-items-start">
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

                                    {{-- Mini summary strip --}}
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <span class="badge bg-success">Active: {{ $active }}</span>
                                        <span class="badge bg-secondary">Inactive: {{ $inactive }}</span>
                                        <span class="badge bg-success">Online: {{ $online }}</span>
                                        <span class="badge bg-warning text-dark">Offline: {{ $offline }}</span>
                                        <span class="badge bg-dark">Never: {{ $never }}</span>
                                        <span class="badge bg-light text-dark">
                                Data quality: Reg {{ $missingReg }}, Mobile {{ $missingMobile }}, Serial {{ $missingSerial }}
                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">

                                {{-- TOP ROW: Trend + Decision Notes (no empty space) --}}
                                <div class="row g-2 mb-2">
                                    <div class="col-lg-8">
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold text-uppercase small mb-0">Connections Trend</div>
                                                    <div class="small text-muted">connected_at grouped by day</div>
                                                </div>
                                                <span class="badge bg-light text-dark">Interactive</span>
                                            </div>
                                            <div class="card-body py-2" wire:ignore>
                                                <canvas id="reportTrendChart" height="120"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-header bg-white py-2">
                                                <div class="fw-bold text-uppercase small mb-0">Decision Notes</div>
                                                <div class="small text-muted">What this report suggests</div>
                                            </div>
                                            <div class="card-body py-2">
                                                @if(!empty($sr['bullets']))
                                                    <div class="d-grid gap-2">
                                                        @foreach($sr['bullets'] as $b)
                                                            <div class="p-2 border rounded-3">
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

                                                {{-- Performance bar --}}
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

                                {{-- SECOND ROW: Risk queues (fills space and adds operational value) --}}
                                <div class="row g-2 mb-2">
                                    <div class="col-lg-4">
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-header bg-white py-2">
                                                <div class="fw-bold text-uppercase small mb-0">Active but Long Offline</div>
                                                <div class="small text-muted">Immediate operational risk</div>
                                            </div>
                                            <div class="card-body py-2">
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
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-header bg-white py-2">
                                                <div class="fw-bold text-uppercase small mb-0">Never Connected</div>
                                                <div class="small text-muted">Onboarding / install follow-up</div>
                                            </div>
                                            <div class="card-body py-2">
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
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-header bg-white py-2">
                                                <div class="fw-bold text-uppercase small mb-0">Recently Connected</div>
                                                <div class="small text-muted">Latest activity</div>
                                            </div>
                                            <div class="card-body py-2">
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

                                {{-- THIRD ROW: Top Types + Top Models (balanced, no empties) --}}
                                <div class="row g-2">
                                    <div class="col-lg-6">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-uppercase small mb-0">Top Device Types</div>
                                                <span class="badge bg-light text-dark">Count</span>
                                            </div>
                                            <div class="card-body py-2">
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
                                        <div class="card shadow-sm border-0">
                                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-uppercase small mb-0">Top Models</div>
                                                <span class="badge bg-light text-dark">Count</span>
                                            </div>
                                            <div class="card-body py-2">
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

                            <div class="modal-footer">
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
                    // ---- Connectivity Pie
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
                                plugins: { legend: { position: 'bottom' } },
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

                    // ---- Trend
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
                                data: payload.trend.values
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: true } }
                        }
                    });
                }

                document.addEventListener('livewire:init', () => {
                    renderMainCharts(getPayload());
                    renderReportChart(getPayload());

                    Livewire.hook('message.processed', () => {
                        renderMainCharts(getPayload());
                        renderReportChart(getPayload());
                    });

                    Livewire.on('charts-refresh', () => {
                        renderMainCharts(getPayload());
                        renderReportChart(getPayload());
                    });
                });
            </script>

        </div>
    </div>
</section>
