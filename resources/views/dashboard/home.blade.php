@php
    use App\Models\Common\MaterialHeader;
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        /* ---------- Dashboard Modern UI ---------- */
        .dash-kpi {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
            transition: transform .15s ease, box-shadow .15s ease;
            position: relative;
            min-height: 120px;
        }
        .dash-kpi:hover{
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0,0,0,.10);
        }
        .dash-kpi .kpi-body{
            padding: 18px 18px 16px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .kpi-meta{
            display:flex;
            flex-direction:column;
            gap:6px;
        }
        .kpi-title{
            font-size: 13px;
            color: #6b7280;
            margin: 0;
            font-weight: 800;
            letter-spacing: .2px;
            text-transform: uppercase;
        }
        .kpi-value{
            font-size: 30px;
            line-height: 1;
            font-weight: 900;
            margin: 0;
            color: #111827;
        }
        .kpi-sub{
            font-size: 12px;
            color: #6b7280;
            margin:0;
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap: wrap;
        }
        .kpi-chip{
            font-size: 11px;
            font-weight: 900;
            padding: 3px 9px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .chip-up{ background: rgba(16,185,129,.12); color: #047857; }
        .chip-down{ background: rgba(239,68,68,.12); color: #b91c1c; }
        .chip-neutral{ background: rgba(59,130,246,.12); color: #1d4ed8; }

        .kpi-icon{
            width: 46px; height: 46px;
            border-radius: 14px;
            display:flex;
            align-items:center;
            justify-content:center;
            flex: 0 0 auto;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,.12);
        }
        .kpi-icon i{ font-size: 18px; }

        .kpi-footer{
            padding: 10px 18px;
            border-top: 1px solid rgba(17,24,39,.06);
            display:flex;
            justify-content: space-between;
            align-items:center;
            font-size: 12px;
            color: #6b7280;
            background: rgba(249,250,251,.6);
        }
        .kpi-link{
            font-weight: 900;
            text-decoration: none;
        }

        /* Color presets */
        .bg-grad-green{ background: linear-gradient(135deg,#10b981,#059669); }
        .bg-grad-blue{ background: linear-gradient(135deg,#3b82f6,#2563eb); }
        .bg-grad-amber{ background: linear-gradient(135deg,#f59e0b,#d97706); }
        .bg-grad-red{ background: linear-gradient(135deg,#ef4444,#dc2626); }

        /* Cards */
        .dash-card{
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background:#fff;
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
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
            color:#111827;
        }
        .dash-card .card-body{
            padding: 14px 16px 16px 16px;
        }

        .chart-holder{
            border-radius: 14px;
            background: #fff;
            border: 1px solid rgba(17,24,39,.08);
            padding: 10px;
        }

        /* Filter UI */
        .filter-row{
            display:flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
        }
        .filter-item{
            min-width: 180px;
        }
        .filter-label{
            font-size: 12px;
            color:#6b7280;
            font-weight: 800;
            margin-bottom: 6px;
            display:block;
        }
        .filter-meta{
            font-size: 12px;
            color:#6b7280;
            display:flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items:center;
        }

        /* Table enhancement */
        #listTable thead th { font-weight: 900; color: #111827; }
        #listTable tbody td { vertical-align: middle; }

        /* Small stat badge area inside headers */
        .header-badges{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
        }
        .mini-stat{
            font-size: 12px;
            color:#6b7280;
            font-weight: 700;
            background: rgba(249,250,251,.9);
            border: 1px solid rgba(17,24,39,.08);
            padding: 6px 10px;
            border-radius: 999px;
            display:inline-flex;
            gap:6px;
            align-items:center;
        }
        .mini-stat b { color:#111827; font-weight: 900; }
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
                        <div class="dash-kpi">
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
                                <div class="kpi-icon bg-grad-green">
                                    <i class="fas fa-truck"></i>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                <span>Updated: {{ now()->format('d M Y, H:i') }}</span>
                                <a href="#" class="kpi-link" style="color:#059669;">More info <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi">
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
                                <div class="kpi-icon bg-grad-blue">
                                    <i class="fas fa-wrench"></i>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                <span>Maintenance capacity</span>
                                <a href="#" class="kpi-link" style="color:#2563eb;">More info <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi">
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
                                <div class="kpi-icon bg-grad-amber">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                <span>System usage</span>
                                <a href="#" class="kpi-link" style="color:#d97706;">More info <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="dash-kpi">
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
                                <div class="kpi-icon bg-grad-red">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                            </div>
                            <div class="kpi-footer">
                                <span>Driver pool</span>
                                <a href="#" class="kpi-link" style="color:#dc2626;">More info <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- MY TASKS CARD --}}
            <div class="row">
                <div class="col-md-12 pl-0">
                    <div class="card dash-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">
                                <h3>My Tasks</h3>
                                <div style="font-size:12px;color:#6b7280;margin-top:4px;">
                                    Quick actions pending your approval
                                </div>
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

                    {{-- FILTER CARD (separate card) --}}
                    <div class="col-12">
                        <div class="card dash-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title">
                                    <h4>Quick Filters</h4>
                                    <div style="font-size:12px;color:#6b7280;margin-top:4px;">
                                        Filter charts client-side (status + search) without affecting backend data
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

                                    <div class="filter-item" style="flex:1; min-width: 240px;">
                                        <label class="filter-label" for="searchFilter">Search</label>
                                        <input id="searchFilter"
                                               type="text"
                                               class="form-control form-control-sm"
                                               placeholder="Search reg number, model, status, driver...">
                                    </div>

                                    <div class="filter-item" style="min-width: 200px;">
                                        <button id="btnApply" class="btn btn-sm btn-primary w-100">
                                            <i class="fas fa-filter mr-1"></i> Apply
                                        </button>
                                    </div>

                                    <div class="filter-item" style="min-width: 200px;">
                                        <button id="btnReset" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="fas fa-undo mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 filter-meta">
                                    <span class="mini-stat">
                                        Top Status: <b id="flt_top_status">--</b>
                                    </span>
                                    <span class="mini-stat">
                                        Top Count: <b id="flt_top_count">--</b>
                                    </span>
                                    <span class="mini-stat">
                                        Query: <b id="flt_query">--</b>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BAR CARD --}}
                    <div class="col-12 col-lg-7">
                        <div class="card dash-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title">
                                    <h4>Vehicles by Status</h4>
                                    <div style="font-size:12px;color:#6b7280;margin-top:4px;">
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

                    {{-- PIE CARD --}}
                    <div class="col-12 col-lg-5">
                        <div class="card dash-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title">
                                    <h4>Status Share</h4>
                                    <div style="font-size:12px;color:#6b7280;margin-top:4px;">
                                        Percentage breakdown for decision overview
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

                    {{-- TREND CARD --}}
                    <div class="col-12">
                        <div class="card dash-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title">
                                    <h4>Trend Overview</h4>
                                    <div style="font-size:12px;color:#6b7280;margin-top:4px;">
                                        Simple trend-like view using status counts (filtered)
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-holder">
                                    <div id="trend" style="height: 300px;"></div>
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
    <script>
        window.vehicleData = {!! json_encode($vehicleData) !!};

        (function () {
            // DataTable
            $('#listTable').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[4,'desc']]
            });

            const allVehicles = Array.isArray(window.vehicleData) ? window.vehicleData : [];
            const baselineTotal = allVehicles.length;

            // If ECharts isn't loaded, stop safely
            if (typeof echarts === 'undefined') {
                console.warn('ECharts not loaded. Please include ECharts library.');
                return;
            }

            // ---------- Helpers ----------
            function norm(v) {
                return (v ?? '').toString().toLowerCase().trim();
            }

            function getStatusName(v) {
                return v.status_name || v.status || 'Unknown';
            }

            function buildStatusAgg(vehicles) {
                const valueObject = {};
                for (const v of vehicles) {
                    const key = getStatusName(v) || 'Unknown';
                    valueObject[key] = (valueObject[key] || 0) + 1;
                }

                const seriesData = Object.keys(valueObject).map(k => ({ name: k, value: valueObject[k] }));
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

            function matchesSearch(v, q) {
                if (!q) return true;

                // Try common fields (won't break if they don't exist)
                const hay = [
                    v.reg_number, v.registration, v.plate, v.number_plate,
                    v.model, v.make, v.type,
                    v.driver_name, v.driver,
                    v.imei,
                    getStatusName(v)
                ].map(norm).join(' | ');

                return hay.includes(q);
            }

            function applyFilter() {
                const status = document.getElementById('statusFilter')?.value || '';
                const q = norm(document.getElementById('searchFilter')?.value || '');

                let filtered = allVehicles.slice();

                if (status) {
                    filtered = filtered.filter(v => (getStatusName(v) || 'Unknown') === status);
                }
                if (q) {
                    filtered = filtered.filter(v => matchesSearch(v, q));
                }

                return { filtered, status, q };
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

                const pct = formatDeltaPct(currentTotal, baselineTotal);
                const pctRounded = Math.round(pct * 10) / 10; // 1 decimal
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

            function updateMetaUI(topStatus, topCount, status, q) {
                const topStatusEl = document.getElementById('flt_top_status');
                const topCountEl = document.getElementById('flt_top_count');
                const queryEl = document.getElementById('flt_query');

                if (topStatusEl) topStatusEl.textContent = topStatus || '--';
                if (topCountEl) topCountEl.textContent = (topCount ?? '--').toString();

                const statusTxt = status ? `status=${status}` : 'status=All';
                const qTxt = q ? `search="${q}"` : 'search=--';
                if (queryEl) queryEl.textContent = `${statusTxt}, ${qTxt}`;
            }

            // ---------- Init Filter Dropdown ----------
            const statusSelect = document.getElementById('statusFilter');
            if (statusSelect) {
                const statuses = uniqueStatuses(allVehicles);
                statuses.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    statusSelect.appendChild(opt);
                });
            }

            // ---------- Chart Instances ----------
            const barDom = document.getElementById('main');
            const pieDom = document.getElementById('pie');
            const trendDom = document.getElementById('trend');

            const barChart = barDom ? echarts.init(barDom) : null;
            const pieChart = pieDom ? echarts.init(pieDom) : null;
            const trendChart = trendDom ? echarts.init(trendDom) : null;

            function setCharts(vehicles) {
                const agg = buildStatusAgg(vehicles);

                // update filter header stats
                updateDeltaUI(agg.total);
                updateMetaUI(agg.top.name, agg.top.value, document.getElementById('statusFilter')?.value || '', norm(document.getElementById('searchFilter')?.value || ''));

                // BAR
                if (barChart) {
                    barChart.setOption({
                        title: {
                            text: 'Vehicles by Status',
                            left: 'left',
                            textStyle: { fontSize: 14, fontWeight: 900 }
                        },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: { type: 'shadow' },
                            formatter: (params) => {
                                const p = params[0];
                                return `${p.name}<br/>Vehicles: <b>${p.value}</b>`;
                            }
                        },
                        grid: { left: 40, right: 20, top: 60, bottom: 70 },
                        xAxis: {
                            type: 'category',
                            data: agg.seriesData.map(x => x.name),
                            axisLabel: { rotate: 30, interval: 0, overflow: 'truncate', width: 110 }
                        },
                        yAxis: { type: 'value', splitLine: { lineStyle: { type: 'dashed' } } },
                        series: [{
                            name: 'Vehicles',
                            type: 'bar',
                            barWidth: 26,
                            data: agg.seriesData.map(x => x.value),
                            itemStyle: {
                                borderRadius: [8,8,0,0],
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                    { offset: 0, color: '#3b82f6' },
                                    { offset: 1, color: '#22c55e' }
                                ])
                            }
                        }]
                    }, true);
                }

                // PIE (donut + total center)
                if (pieChart) {
                    pieChart.setOption({
                        title: {
                            text: 'Status Share',
                            left: 'left',
                            textStyle: { fontSize: 14, fontWeight: 900 }
                        },
                        tooltip: { trigger: 'item', formatter: '{b}<br/>Vehicles: <b>{c}</b> ({d}%)' },
                        legend: { type: 'scroll', orient: 'vertical', right: 10, top: 55, bottom: 10 },
                        series: [{
                            name: 'Status',
                            type: 'pie',
                            radius: ['52%', '72%'],
                            center: ['40%', '55%'],
                            label: { show: false },
                            labelLine: { show: false },
                            data: agg.seriesData,
                            emphasis: {
                                scale: true,
                                scaleSize: 8,
                                itemStyle: { shadowBlur: 12, shadowOffsetX: 0, shadowColor: 'rgba(0,0,0,0.25)' }
                            }
                        }],
                        graphic: [{
                            type: 'text',
                            left: '40%',
                            top: '50%',
                            style: {
                                text: `${agg.total}\nTotal`,
                                textAlign: 'center',
                                fill: '#111827',
                                fontSize: 14,
                                fontWeight: 900
                            }
                        }]
                    }, true);
                }

                // TREND (line based on sorted counts)
                if (trendChart) {
                    const labels = agg.seriesData.map(x => x.name);
                    const values = agg.seriesData.map(x => x.value);

                    trendChart.setOption({
                        title: {
                            text: 'Trend Overview (Top Status Counts)',
                            left: 'left',
                            textStyle: { fontSize: 14, fontWeight: 900 }
                        },
                        tooltip: {
                            trigger: 'axis',
                            formatter: (params) => `${params[0].axisValue}<br/>Vehicles: <b>${params[0].data}</b>`
                        },
                        grid: { left: 40, right: 20, top: 60, bottom: 60 },
                        xAxis: { type: 'category', data: labels, axisLabel: { rotate: 20, interval: 0, overflow: 'truncate', width: 120 } },
                        yAxis: { type: 'value', splitLine: { lineStyle: { type: 'dashed' } } },
                        series: [{
                            type: 'line',
                            smooth: true,
                            symbol: 'circle',
                            symbolSize: 8,
                            data: values,
                            areaStyle: { opacity: 0.15 },
                            lineStyle: { width: 3 }
                        }]
                    }, true);
                }
            }

            // Initial render (all vehicles)
            document.getElementById('flt_total') && (document.getElementById('flt_total').textContent = baselineTotal.toString());
            setCharts(allVehicles);

            // ---------- Events ----------
            const btnApply = document.getElementById('btnApply');
            const btnReset = document.getElementById('btnReset');

            function doApply() {
                const { filtered } = applyFilter();
                setCharts(filtered);
            }

            if (btnApply) btnApply.addEventListener('click', doApply);

            // Live typing (small debounce)
            let t = null;
            const searchInput = document.getElementById('searchFilter');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(t);
                    t = setTimeout(doApply, 250);
                });
            }

            // Change status triggers apply
            if (statusSelect) {
                statusSelect.addEventListener('change', doApply);
            }

            if (btnReset) {
                btnReset.addEventListener('click', () => {
                    if (statusSelect) statusSelect.value = '';
                    if (searchInput) searchInput.value = '';
                    setCharts(allVehicles);
                });
            }

            // Resize
            window.addEventListener('resize', () => {
                if (barChart) barChart.resize();
                if (pieChart) pieChart.resize();
                if (trendChart) trendChart.resize();
            });

        })();
    </script>
@endpush
