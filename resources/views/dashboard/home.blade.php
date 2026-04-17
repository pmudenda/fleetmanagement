@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        /*
         |--------------------------------------------------------------------------
         | STATUS COLOR POLICY (SINGLE SOURCE OF TRUTH)
         |--------------------------------------------------------------------------
         | workshop         = orange
         | active           = green
         | pending disposal = purple
         | inactive         = blue
         | grounded         = red
         |
         | Note: Only these + neutrals are used anywhere in this file.
         */
        :root{
            --st-workshop: #f59e0b;  /* orange */
            --st-active:   #22c55e;  /* green  */
            --st-disposal: #8b5cf6;  /* purple */
            --st-inactive: #3b82f6;  /* blue   */
            --st-grounded: #ef4444;  /* red    */

            --ui-bg: #ffffff;
            --ui-text: #111827;
            --ui-muted: #6b7280;
            --ui-border: rgba(17,24,39,.08);
            --ui-shadow: rgba(0,0,0,.06);
            --ui-shadow-strong: rgba(0,0,0,.10);
            --ui-grey: #9ca3af;
        }

        /* ---------- Dashboard Modern UI ---------- */
        .dash-kpi {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background: var(--ui-bg);
            box-shadow: 0 10px 25px var(--ui-shadow);
            transition: transform .15s ease, box-shadow .15s ease;
            position: relative;
            min-height: 120px;
        }
        .dash-kpi:hover{
            transform: translateY(-2px);
            box-shadow: 0 14px 30px var(--ui-shadow-strong);
        }
        .dash-kpi .kpi-body{
            padding: 18px 18px 16px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .kpi-meta{ display:flex; flex-direction:column; gap:6px; }
        .kpi-title{
            font-size: 13px; color: var(--ui-muted); margin: 0;
            font-weight: 800; letter-spacing: .2px; text-transform: uppercase;
        }
        .kpi-value{
            font-size: 30px; line-height: 1;
            font-weight: 900; margin: 0; color: var(--ui-text);
        }
        .kpi-sub{
            font-size: 12px; color: var(--ui-muted); margin:0;
            display:flex; align-items:center; gap:8px; flex-wrap: wrap;
        }
        .kpi-chip{
            font-size: 11px; font-weight: 900;
            padding: 3px 9px; border-radius: 999px;
            display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap;
            border: 1px solid rgba(17,24,39,.06);
        }

        /* only allowed chip colors (green/red) + neutral grey */
        .chip-up      { background: rgba(34,197,94,.12);  color: var(--st-active);   }
        .chip-down    { background: rgba(239,68,68,.12);  color: var(--st-grounded); }
        .chip-neutral { background: rgba(156,163,175,.12); color: var(--ui-text);    }

        .kpi-icon{
            width: 46px; height: 46px;
            border-radius: 14px;
            display:flex; align-items:center; justify-content:center;
            flex: 0 0 auto;
            color: #fff;
            background: var(--ui-grey); /* neutral only */
            box-shadow: 0 10px 20px rgba(0,0,0,.12);
        }
        .kpi-icon i{ font-size: 18px; }

        .kpi-footer{
            padding: 10px 18px;
            border-top: 1px solid rgba(17,24,39,.06);
            display:flex; justify-content: space-between; align-items:center;
            font-size: 12px; color: var(--ui-muted);
            background: rgba(249,250,251,.6);
        }

        /* Cards */
        .dash-card{
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background: var(--ui-bg);
            box-shadow: 0 10px 25px var(--ui-shadow);
        }
        .dash-card .card-header{
            background: transparent;
            border-bottom: 1px solid rgba(17,24,39,.06);
            padding: 14px 16px;
        }
        .dash-card .card-title h3,
        .dash-card .card-title h4{
            margin: 0;
            font-weight: 900;
            color: var(--ui-text);
        }
        .dash-subtitle{
            font-size:12px;
            color: var(--ui-muted);
            margin-top:10px;
            line-height: 1.35;
        }
        .dash-card .card-body{ padding: 14px 16px 16px 16px; }

        .chart-holder{
            border-radius: 14px;
            background: var(--ui-bg);
            border: 1px solid var(--ui-border);
            padding: 10px;
        }

        /* Filter UI */
        .filter-row{
            display:flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
        }
        .filter-item{ min-width: 180px; }
        .filter-label{
            font-size: 12px;
            color: var(--ui-muted);
            font-weight: 800;
            margin-bottom: 6px;
            display:block;
        }
        .filter-meta{
            font-size: 12px;
            color: var(--ui-muted);
            display:flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items:center;
        }

        /* Table enhancement */
        #listTable thead th { font-weight: 900; color: var(--ui-text); }
        #listTable tbody td { vertical-align: middle; }

        .header-badges{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
        }
        .mini-stat{
            font-size: 12px;
            color: var(--ui-muted);
            font-weight: 700;
            background: rgba(249,250,251,.9);
            border: 1px solid var(--ui-border);
            padding: 6px 10px;
            border-radius: 999px;
            display:inline-flex;
            gap:6px;
            align-items:center;
        }
        .mini-stat b { color: var(--ui-text); font-weight: 900; }

        /* KPI base color */
        .kpi-primary {
            background: linear-gradient(135deg, #af701e, #af701e); /* Modern blue */
            color: #ffffff;
        }

        /* Force all text to white */
        .kpi-primary .kpi-title,
        .kpi-primary .kpi-value,
        .kpi-primary .kpi-sub,
        .kpi-primary .kpi-footer,
        .kpi-primary span,
        .kpi-primary i {
            color: #ffffff;
        }

        /* Icon styling */
        .kpi-primary .kpi-icon {
            font-size: 3rem;
            opacity: 0.25;
        }

        /* Chip override */
        .kpi-primary .kpi-chip {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* Footer subtle separation */
        .kpi-primary .kpi-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.75rem;
        }

        /* SUCCESS / ACTIVE KPI */
        .kpi-success {
            background: linear-gradient(135deg, #10b981, #059669); /* Modern emerald green */
            color: #ffffff;
        }

        /* Force all text white */
        .kpi-success .kpi-title,
        .kpi-success .kpi-value,
        .kpi-success .kpi-sub,
        .kpi-success .kpi-footer,
        .kpi-success span,
        .kpi-success i {
            color: #ffffff;
        }

        /* Icon styling */
        .kpi-success .kpi-icon {
            font-size: 3rem;
            opacity: 0.25;
        }

        /* Chip styling */
        .kpi-success .kpi-chip {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Footer separator */
        .kpi-success .kpi-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.75rem;
        }

        /* INFO / ACTIVE USERS KPI */
        .kpi-info {
            background: linear-gradient(135deg, #06b6d4, #0891b2); /* Modern cyan */
            color: #ffffff;
        }

        /* Force all text white */
        .kpi-info .kpi-title,
        .kpi-info .kpi-value,
        .kpi-info .kpi-sub,
        .kpi-info .kpi-footer,
        .kpi-info span,
        .kpi-info i {
            color: #ffffff;
        }

        /* Icon styling */
        .kpi-info .kpi-icon {
            font-size: 3rem;
            opacity: 0.25;
        }

        /* Chip styling */
        .kpi-info .kpi-chip {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Footer separator */
        .kpi-info .kpi-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.75rem;
        }

        /* DRIVER / CAPACITY KPI */
        .kpi-purple {
            background: linear-gradient(135deg, #af701e, #2a8611); /* Modern violet */
            color: #ffffff;
        }

        /* Force all text white */
        .kpi-purple .kpi-title,
        .kpi-purple .kpi-value,
        .kpi-purple .kpi-sub,
        .kpi-purple .kpi-footer,
        .kpi-purple span,
        .kpi-purple i {
            color: #ffffff;
        }

        /* Icon styling */
        .kpi-purple .kpi-icon {
            font-size: 3rem;
            opacity: 0.25;
        }

        /* Chip styling */
        .kpi-purple .kpi-chip {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Footer separator */
        .kpi-purple .kpi-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.75rem;
        }


    </style>
@endpush

@section('content')
    <x-content-header :pageTitle="'Dashboard'"/>

    <section class="content">
        <div class="container-fluid">

            {{-- KPI CARDS --}}
            @can(config('rights.view_dashboard'))
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi kpi-primary">
                            <div class="kpi-body">
                                <div class="kpi-meta">
                                    <p class="kpi-title">Total Fleet</p>
                                    <p class="kpi-value">{{ number_format($vehicleData->count()) }}</p>
                                    <p class="kpi-sub">
                    <span class="kpi-chip chip-neutral">
                        <i class="fas fa-chart-line"></i> Overview
                    </span>
                                        <span>All registered vehicles</span>
                                    </p>
                                </div>
                                <div class="kpi-icon"><i class="fas fa-truck"></i></div>
                            </div>
                            <div class="kpi-footer">
                                <span>Updated: {{ now()->format('d M Y, H:i') }}</span>
                                <span></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi kpi-success">
                            <div class="kpi-body">
                                <div class="kpi-meta">
                                    <p class="kpi-title">Mechanics</p>
                                    <p class="kpi-value">{{ number_format($mechanics) }}</p>
                                    <p class="kpi-sub">
                    <span class="kpi-chip chip-up">
                        <i class="fas fa-arrow-up"></i> Active
                    </span>
                                        <span>Workforce ready</span>
                                    </p>
                                </div>
                                <div class="kpi-icon"><i class="fas fa-wrench"></i></div>
                            </div>
                            <div class="kpi-footer">
                                <span>Maintenance capacity</span>
                                <span></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi kpi-info">
                            <div class="kpi-body">
                                <div class="kpi-meta">
                                    <p class="kpi-title">Active Users</p>
                                    <p class="kpi-value">{{ number_format($activeUsers) }}</p>
                                    <p class="kpi-sub">
                    <span class="kpi-chip chip-neutral">
                        <i class="fas fa-user-check"></i> Live
                    </span>
                                        <span>Currently enabled</span>
                                    </p>
                                </div>
                                <div class="kpi-icon"><i class="fas fa-users"></i></div>
                            </div>
                            <div class="kpi-footer">
                                <span>System usage</span>
                                <span></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi kpi-purple">
                            <div class="kpi-body">
                                <div class="kpi-meta">
                                    <p class="kpi-title">Registered Drivers</p>
                                    <p class="kpi-value">{{ number_format($activeDrivers) }}</p>
                                    <p class="kpi-sub">
                    <span class="kpi-chip chip-neutral">
                        <i class="fas fa-id-card"></i> Total
                    </span>
                                        <span>Available drivers</span>
                                    </p>
                                </div>
                                <div class="kpi-icon"><i class="fas fa-id-badge"></i></div>
                            </div>
                            <div class="kpi-footer">
                                <span>Driver pool</span>
                                <span></span>
                            </div>
                        </div>
                    </div>


                </div>
            @endcan


            <div class="row">
                <div class="col-md-12 pl-0">
                    <div class="card dash-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">
                                <h3>My Tasks</h3>
                                <div class="dash-subtitle">Quick actions pending your approval</div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table aria-label="tasks table"
                                       id="listTable"
                                       class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Originator</th>
                                        <th>Date Requested</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($approvalTasks as $rec)
                                        <tr>
                                            <td>
                                                <a href="{{ URL::signedRoute($rec->url, ['ref'=>  $rec->reference]) }}">
                                                    {{ $rec->reference }}
                                                </a>
                                            </td>
                                            <td>{{ $rec->subject ?? '--' }}</td>
                                            <td>{{ $rec->description }}</td>
                                            <td>{{ $rec->originator }}</td>
                                            <td>{{ Carbon::parse($rec->date_acted)->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ URL::signedRoute($rec->url,['ref'=> $rec->reference]) }}"
                                                   class="btn btn-sm btn-success">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- VEHICLE DASHBOARD --}}
            @can(config('rights.view_vehicle_dashboard'))
                <div class="row mt-3">

                    {{-- FILTER CARD --}}
                    <div class="col-12">
                        <div class="card dash-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title">
                                    <h4>Quick Filters</h4>
                                    <div class="dash-subtitle">
                                        Filter charts client-side by status only (does not change backend data)
                                    </div>
                                </div>

                                <div class="header-badges">
                                    <span class="mini-stat">
                                        Showing <b id="flt_showing">0</b> / <b id="flt_total">0</b>
                                    </span>
                                    <span class="mini-stat">
                                        Change <b id="flt_delta">0%</b>
                                        <span id="flt_delta_icon" class="kpi-chip chip-neutral" style="padding:2px 8px;">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <label class="filter-label" for="statusFilter">Status</label>
                                        <select id="statusFilter" class="form-control form-control-sm">
                                            <option value="">All Statuses</option>
                                        </select>
                                    </div>

                                    <div class="filter-item" style="min-width: 200px;">
                                        <button id="btnReset" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="fas fa-undo mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 filter-meta">
                                    <span class="mini-stat">Top Status: <b id="flt_top_status">--</b></span>
                                    <span class="mini-stat">Top Count: <b id="flt_top_count">--</b></span>
                                    <span class="mini-stat">Query: <b id="flt_query">--</b></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BAR --}}
                    <div class="col-12 col-lg-7 mt-3">
                        <div class="card dash-card">
                            <div class="card-header">
                                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                                    <h4 class="mb-0">Vehicles by Status</h4>
                                    <div class="dash-subtitle text-muted text-end">
                                        Counts grouped by current filtered dataset
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-holder">
                                    <div id="main" style="height: 360px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PIE --}}
                    <div class="col-12 col-lg-5 mt-3">
                        <div class="card dash-card">
                            <div class="card-header">
                                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                                    <h4 class="mb-0">Status Share</h4>
                                    <div class="dash-subtitle text-muted text-end">
                                        Actual percentage breakdown for decision overview
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-holder">
                                    <div id="pie" style="height: 360px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TREND --}}
                    <div class="col-12 mt-3">
                        <div class="card dash-card">
                            <div class="card-header">
                                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                                    <h4 class="mb-0">Trend Overview</h4>
                                    <div class="dash-subtitle text-muted text-end">
                                        Actual status ranking trend + cumulative contribution (Pareto) from filtered dataset
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-holder">
                                    <div id="trend" style="height: 330px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DECISION MAKER --}}
                    <div class="col-12 mt-3">
                        <div class="card dash-card">
                            <div class="card-header">
                                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                                    <h4 class="mb-0">Decision Overview</h4>
                                    <div class="dash-subtitle text-muted text-end">
                                        Readiness score + risk grouping + top bottlenecks (all computed from actual fleet status)
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-lg-4">
                                        <div class="chart-holder mb-3 mb-lg-0">
                                            <div id="gauge" style="height: 300px;"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="chart-holder mb-3 mb-lg-0">
                                            <div id="risk" style="height: 300px;"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="chart-holder">
                                            <div id="bottlenecks" style="height: 300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endcan

        </div>
    </section>
@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <script>
        window.vehicleData = {!! json_encode($vehicleData) !!};

        (function () {
            $('#listTable').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[4,'desc']]
            });

            const allVehicles = Array.isArray(window.vehicleData) ? window.vehicleData : [];
            const baselineTotal = allVehicles.length;

            if (typeof echarts === 'undefined') {
                console.warn('ECharts not loaded. Please include ECharts library.');
                return;
            }

            // ----------------------------
            // STATUS COLOR POLICY (single source of truth)
            // workshop         = orange
            // active           = green
            // pending disposal = purple
            // inactive         = blue
            // grounded         = red
            // ----------------------------
            const COLORS = {
                workshop:  '#f59e0b', // orange
                active:    '#22c55e', // green
                disposal:  '#8b5cf6', // purple
                inactive:  '#3b82f6', // blue
                grounded:  '#ef4444', // red
                unknown:   '#9ca3af', // neutral grey (not a status)
                ink:       '#130f04'  // neutral (line/text)
            };


            const STATUS_RULES = [

                { key: 'disposal',  match: /(pending\s*disposal|disposal\s*pending|pending\s*scrap|scrap\s*pending|awaiting\s*disposal)/i },
                { key: 'workshop',  match: /(workshop|maintenance|repair|servicing|garage)/i },
                { key: 'grounded',  match: /(grounded|down|breakdown|offline|out\s*of\s*service|out-of-service)/i },
                { key: 'inactive',  match: /(inactive|disabled|suspended|blocked)/i },
                { key: 'active',    match: /(active|available|in\s*service|serviceable|running|online|ready|on\s*duty)/i },
            ];

            function colorForStatus(statusName) {
                const s = (statusName ?? '').toString().trim();
                for (const r of STATUS_RULES) {
                    if (r.match.test(s)) return COLORS[r.key];
                }
                return COLORS.unknown;
            }

            function norm(v){ return (v ?? '').toString().toLowerCase().trim(); }
            function getStatusName(v) { return v.status_name || v.status || 'Unknown'; }

            function buildStatusAgg(vehicles) {
                const map = {};
                for (const v of vehicles) {
                    const key = getStatusName(v) || 'Unknown';
                    map[key] = (map[key] || 0) + 1;
                }
                const seriesData = Object.keys(map).map(k => ({ name: k, value: map[k] }));
                seriesData.sort((a,b)=> b.value - a.value);

                const total = seriesData.reduce((a,b)=> a + b.value, 0);
                const top = seriesData[0] || { name: '--', value: 0 };
                return { seriesData, total, top };
            }

            function uniqueStatuses(vehicles) {
                const set = new Set();
                for (const v of vehicles) set.add(getStatusName(v) || 'Unknown');
                return Array.from(set).filter(Boolean).sort((a,b)=> a.localeCompare(b));
            }

            function applyFilter() {
                const status = document.getElementById('statusFilter')?.value || '';
                let filtered = allVehicles.slice();
                if (status) filtered = filtered.filter(v => (getStatusName(v) || 'Unknown') === status);
                return { filtered, status };
            }

            function formatDeltaPct(current, base) {
                if (!base) return 0;
                return ((current - base) / base) * 100;
            }

            function updateDeltaUI(currentTotal) {
                const showingEl = document.getElementById('flt_showing');
                const totalEl = document.getElementById('flt_total');
                const deltaEl = document.getElementById('flt_delta');
                const deltaIconEl = document.getElementById('flt_delta_icon');

                if (showingEl) showingEl.textContent = currentTotal.toString();
                if (totalEl) totalEl.textContent = baselineTotal.toString();

                const pctRounded = Math.round(formatDeltaPct(currentTotal, baselineTotal) * 10) / 10;
                if (deltaEl) deltaEl.textContent = `${pctRounded}%`;

                if (deltaIconEl) {
                    deltaIconEl.classList.remove('chip-up','chip-down','chip-neutral');
                    if (pctRounded > 0) {
                        deltaIconEl.classList.add('kpi-chip','chip-up');
                        deltaIconEl.innerHTML = '<i class="fas fa-arrow-up"></i>';
                    } else if (pctRounded < 0) {
                        deltaIconEl.classList.add('kpi-chip','chip-down');
                        deltaIconEl.innerHTML = '<i class="fas fa-arrow-down"></i>';
                    } else {
                        deltaIconEl.classList.add('kpi-chip','chip-neutral');
                        deltaIconEl.innerHTML = '<i class="fas fa-minus"></i>';
                    }
                }
            }

            function updateMetaUI(topStatus, topCount, status) {
                const topStatusEl = document.getElementById('flt_top_status');
                const topCountEl = document.getElementById('flt_top_count');
                const queryEl = document.getElementById('flt_query');

                if (topStatusEl) topStatusEl.textContent = topStatus || '--';
                if (topCountEl) topCountEl.textContent = (topCount ?? '--').toString();
                if (queryEl) queryEl.textContent = status ? `status=${status}` : 'status=All';
            }


            function classifyStatus(name) {
                const s = norm(name);

                if (/(pending\s*disposal|disposal\s*pending|awaiting\s*disposal)/i.test(s)) return 'Pending Disposal';
                if (/(workshop|maintenance|repair|servicing|garage)/i.test(s)) return 'Workshop';
                if (/(grounded|down|breakdown|offline|out\s*of\s*service|out-of-service)/i.test(s)) return 'Grounded';
                if (/(inactive|disabled|suspended|blocked)/i.test(s)) return 'Inactive';
                if (/(active|available|in\s*service|serviceable|running|online|ready|on\s*duty)/i.test(s)) return 'Active';

                return 'Unknown';
            }

            function decisionMetrics(agg) {
                const total = agg.total || 0;

                let active = 0, workshop = 0, grounded = 0, inactive = 0, disposal = 0, unknown = 0;

                for (const item of agg.seriesData) {
                    const g = classifyStatus(item.name);
                    if (g === 'Active') active += item.value;
                    else if (g === 'Workshop') workshop += item.value;
                    else if (g === 'Grounded') grounded += item.value;
                    else if (g === 'Inactive') inactive += item.value;
                    else if (g === 'Pending Disposal') disposal += item.value;
                    else unknown += item.value;
                }


                const readiness = total ? Math.round((active / total) * 100) : 0;


                const bottlenecks = agg.seriesData
                    .slice()
                    .filter(x => classifyStatus(x.name) !== 'Active')
                    .slice(0, 6);

                return { total, active, workshop, grounded, inactive, disposal, unknown, readiness, bottlenecks };
            }


            const statusSelect = document.getElementById('statusFilter');
            if (statusSelect) {
                uniqueStatuses(allVehicles).forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    statusSelect.appendChild(opt);
                });
            }


            const barChart = document.getElementById('main') ? echarts.init(document.getElementById('main')) : null;
            const pieChart = document.getElementById('pie') ? echarts.init(document.getElementById('pie')) : null;
            const trendChart = document.getElementById('trend') ? echarts.init(document.getElementById('trend')) : null;

            const gaugeChart = document.getElementById('gauge') ? echarts.init(document.getElementById('gauge')) : null;
            const riskChart = document.getElementById('risk') ? echarts.init(document.getElementById('risk')) : null;
            const bottlenecksChart = document.getElementById('bottlenecks') ? echarts.init(document.getElementById('bottlenecks')) : null;

            function commonToolbox() {
                return {
                    show: true,
                    right: 8,
                    feature: { saveAsImage: { title: 'Save' }, restore: { title: 'Reset' } }
                };
            }

            function colorizeSeriesData(seriesData) {
                return seriesData.map(item => ({
                    ...item,
                    itemStyle: { color: colorForStatus(item.name) }
                }));
            }

            function barDataWithColors(seriesData) {
                return seriesData.map(item => ({
                    value: item.value,
                    itemStyle: { color: colorForStatus(item.name), borderRadius: [8,8,0,0] }
                }));
            }

            function setCharts(vehicles) {
                const agg = buildStatusAgg(vehicles);
                const dm = decisionMetrics(agg);

                updateDeltaUI(agg.total);
                updateMetaUI(agg.top.name, agg.top.value, document.getElementById('statusFilter')?.value || '');

                const coloredPie = colorizeSeriesData(agg.seriesData);


                if (barChart) {
                    barChart.setOption({
                        toolbox: commonToolbox(),
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: (params) => {
                                const p = params[0];
                                return `${p.name}<br/>Vehicles: <b>${p.value}</b>`;
                            }
                        },
                        grid: { left: 40, right: 20, top: 30, bottom: 70 },
                        xAxis: {
                            type: 'category',
                            data: agg.seriesData.map(x => x.name),
                            axisLabel: { rotate: 25, interval: 0, overflow: 'truncate', width: 115 }
                        },
                        yAxis: { type: 'value', splitLine: { lineStyle: { type: 'dashed' } } },
                        series: [{
                            name: 'Vehicles',
                            type: 'bar',
                            barMaxWidth: 34,
                            data: barDataWithColors(agg.seriesData)
                        }]
                    }, true);
                }


                if (pieChart) {
                    pieChart.setOption({
                        toolbox: commonToolbox(),
                        tooltip: { trigger: 'item', formatter: '{b}<br/>Vehicles: <b>{c}</b> ({d}%)' },
                        legend: { type: 'scroll', orient: 'vertical', right: 10, top: 12, bottom: 10 },
                        series: [{
                            name: 'Status',
                            type: 'pie',
                            radius: ['50%', '72%'],
                            center: ['38%', '52%'],
                            data: coloredPie,
                            label: { show: false },
                            labelLine: { show: false },
                            emphasis: {
                                scale: true,
                                scaleSize: 8,
                                itemStyle: { shadowBlur: 12, shadowOffsetX: 0, shadowColor: 'rgba(0,0,0,0.25)' }
                            }
                        }],
                        graphic: [{
                            type: 'text',
                            left: '38%',
                            top: '48%',
                            style: {
                                text: `${agg.total}\nTotal`,
                                textAlign: 'center',
                                fill: COLORS.ink,
                                fontSize: 14,
                                fontWeight: 900
                            }
                        }]
                    }, true);
                }


                if (trendChart) {
                    const labels = agg.seriesData.map(x => x.name);
                    const values = agg.seriesData.map(x => x.value);
                    const trendSource = (agg.seriesData || []).map(x => [x.name, x.value]);


                    let running = 0;
                    const cumPct = values.map(v => {
                        running += v;
                        return dm.total ? Math.round((running / dm.total) * 1000) / 10 : 0;
                    });

                    trendChart.setOption({
                        toolbox: commonToolbox(),
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: (params) => {
                                const bar = params.find(p => p.seriesName === 'Count');
                                const line = params.find(p => p.seriesName === 'Cumulative %');
                                const name = params[0]?.axisValue ?? '';
                                const c = bar ? (bar.data?.value ?? bar.data) : 0;
                                const p = line ? line.data : 0;
                                return `${name}<br/>Count: <b>${c}</b><br/>Cumulative: <b>${p}%</b>`;
                            }
                        },
                        grid: { left: 40, right: 55, top: 30, bottom: 70 },
                        xAxis: {
                            type: 'category',
                            data: labels,
                            axisLabel: { rotate: 20, interval: 0, overflow: 'truncate', width: 120 }
                        },
                        yAxis: [
                            { type: 'value', name: 'Count', splitLine: { lineStyle: { type: 'dashed' } } },
                            { type: 'value', name: '%', min: 0, max: 100, splitLine: { show: false } }
                        ],
                        series: [
                            {
                                name: 'Count',
                                type: 'bar',
                                barMaxWidth: 28,
                                data: labels.map((label, idx) => ({
                                    value: values[idx],
                                    itemStyle: { color: colorForStatus(label), borderRadius: [8,8,0,0] }
                                }))
                            },
                            {
                                name: 'Cumulative %',
                                type: 'line',
                                yAxisIndex: 1,
                                smooth: true,
                                symbol: 'circle',
                                symbolSize: 7,
                                data: cumPct,
                                lineStyle: { width: 3, color: COLORS.ink },
                                itemStyle: { color: COLORS.ink }
                            }
                        ]
                    }, true);
                }

                if (gaugeChart) {
                    const val = Math.max(0, Math.min(100, Number(dm.readiness ?? 0))); // clamp 0..100
                    const frac = val / 100;

                    gaugeChart.setOption({
                        toolbox: commonToolbox(),
                        title: {
                            text: 'Readiness Score',
                            left: 'center',
                            top: 0,
                            textStyle: { fontSize: 14, fontWeight: 900 }
                        },
                        series: [{
                            type: 'gauge',
                            startAngle: 200,
                            endAngle: -20,
                            min: 0,
                            max: 100,

                            // filled arc stops at the pointer
                            progress: {
                                show: true,
                                width: 14,
                                roundCap: true,
                                itemStyle: { color: COLORS.active }
                            },

                            // ring color: green up to value, grey after
                            axisLine: {
                                lineStyle: {
                                    width: 14,
                                    roundCap: true,
                                    color: [
                                        [frac, COLORS.active],
                                        [1, COLORS.unknown] // neutral remainder
                                    ]
                                }
                            },

                            axisTick: { show: false },
                            splitLine: { length: 10, lineStyle: { width: 2 } },
                            axisLabel: { distance: 12 },

                            pointer: { width: 5, length: '65%' },

                            detail: {
                                valueAnimation: true,
                                formatter: '{value}%',
                                fontSize: 22,
                                fontWeight: 900,
                                offsetCenter: [0, '45%']
                            },

                            data: [{ value: val }]
                        }]
                    }, true);
                }


                if (riskChart) {
                    const riskData = [
                        { name: 'Active',           value: dm.active,   itemStyle: { color: COLORS.active } },
                        { name: 'Workshop',         value: dm.workshop, itemStyle: { color: COLORS.workshop } },
                        { name: 'Pending Disposal', value: dm.disposal, itemStyle: { color: COLORS.disposal } },
                        { name: 'Inactive',         value: dm.inactive, itemStyle: { color: COLORS.inactive } },
                        { name: 'Grounded',         value: dm.grounded, itemStyle: { color: COLORS.grounded } },
                        { name: 'Unknown',          value: dm.unknown,  itemStyle: { color: COLORS.unknown } },
                    ].filter(x => x.value > 0 || dm.total === 0);

                    riskChart.setOption({
                        toolbox: commonToolbox(),
                        title: { text: 'Operational Risk Grouping', left: 'center', top: 0, textStyle: { fontSize: 14, fontWeight: 900 } },
                        tooltip: { trigger: 'item', formatter: '{b}<br/>Vehicles: <b>{c}</b> ({d}%)' },
                        series: [{
                            type: 'pie',
                            radius: ['45%', '70%'],
                            center: ['50%', '56%'],
                            data: riskData,
                            label: { show: true, formatter: '{b}: {d}%' }
                        }]
                    }, true);
                }


                if (bottlenecksChart) {
                    const items = (dm.bottlenecks.length ? dm.bottlenecks : agg.seriesData.slice(0, 6));
                    bottlenecksChart.setOption({
                        toolbox: commonToolbox(),
                        title: { text: 'Top Bottlenecks (Statuses)', left: 'center', top: 0, textStyle: { fontSize: 14, fontWeight: 900 } },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: (params) => {
                                const p = params[0];
                                const v = p.value?.value ?? p.value;
                                return `${p.name}<br/>Vehicles: <b>${v}</b>`;
                            }
                        },
                        grid: { left: 12, right: 12, top: 38, bottom: 10, containLabel: true },
                        xAxis: { type: 'value', splitLine: { lineStyle: { type: 'dashed' } } },
                        yAxis: {
                            type: 'category',
                            data: items.map(x => x.name).reverse(),
                            axisLabel: { overflow: 'truncate', width: 160 }
                        },
                        series: [{
                            type: 'bar',
                            barMaxWidth: 18,
                            data: items
                                .map(x => ({ value: x.value, itemStyle: { color: colorForStatus(x.name), borderRadius: [0,8,8,0] } }))
                                .reverse()
                        }]
                    }, true);
                }
            }


            const totalEl = document.getElementById('flt_total');
            if (totalEl) totalEl.textContent = baselineTotal.toString();
            setCharts(allVehicles);


            if (statusSelect) statusSelect.addEventListener('change', () => setCharts(applyFilter().filtered));

            const btnReset = document.getElementById('btnReset');
            if (btnReset) {
                btnReset.addEventListener('click', () => {
                    if (statusSelect) statusSelect.value = '';
                    setCharts(allVehicles);
                });
            }


            window.addEventListener('resize', () => {
                barChart && barChart.resize();
                pieChart && pieChart.resize();
                trendChart && trendChart.resize();
                gaugeChart && gaugeChart.resize();
                riskChart && riskChart.resize();
                bottlenecksChart && bottlenecksChart.resize();
            });

        })();
    </script>
@endpush
