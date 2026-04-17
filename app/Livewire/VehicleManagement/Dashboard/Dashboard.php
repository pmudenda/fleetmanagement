<?php

namespace App\Livewire\VehicleManagement\Dashboard;

use App\Models\Gps\Gps;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    #[Url] public int $onlineWindowMinutes = 15;
    #[Url] public int $alertOfflineMinutes = 30;

    #[Url] public string $dateFrom = '';
    #[Url] public string $dateTo = '';

    #[Url] public string $filterConnectivity = 'all'; // all|online|offline|never
    #[Url] public string $filterStatus = 'all';       // all|active|inactive
    #[Url] public string $filterType = 'all';

    #[Url] public string $search = '';
    #[Url] public int $perPage = 10;

    // Fuel Management Filters
    #[Url] public string $fuelSearch = '';
    #[Url] public string $fuelRegNo = '';
    #[Url] public string $fuelType = '';
    #[Url] public string $fuelStatus = '';
    #[Url] public string $fuelDateFrom = '';
    #[Url] public string $fuelDateTo = '';
    #[Url] public int $fuelPerPage = 10;

    // Maintenance Filters
    #[Url] public string $maintenanceSearch = '';
    #[Url] public string $maintenanceRegNo = '';
    #[Url] public string $maintenanceType = '';
    #[Url] public string $maintenanceStatus = '';
    #[Url] public string $maintenanceDateFrom = '';
    #[Url] public string $maintenanceDateTo = '';
    #[Url] public int $maintenancePerPage = 10;

    // Modal
    public bool $showSummaryModal = false;

    public function mount(): void
    {
        if ($this->dateTo === '') {
            $this->dateTo = now()->format('Y-m-d');
        }
        if ($this->dateFrom === '') {
            $this->dateFrom = now()->subDays(90)->format('Y-m-d'); // Extended to 90 days for better fuel data visibility
        }
        
        // Initialize fuel date ranges with broader defaults
        if ($this->fuelDateFrom === '') {
            $this->fuelDateFrom = now()->subYear()->format('Y-m-d'); // Show last year of fuel data
        }
        if ($this->fuelDateTo === '') {
            $this->fuelDateTo = now()->format('Y-m-d');
        }
    }

    // -----------------------------
    // UI lifecycle
    // -----------------------------
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterConnectivity(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }

    // Fuel filter updates
    public function updatingFuelSearch(): void { $this->resetPage(); }
    public function updatingFuelRegNo(): void { $this->resetPage(); }
    public function updatingFuelType(): void { $this->resetPage(); }
    public function updatingFuelStatus(): void { $this->resetPage(); }
    public function updatingFuelDateFrom(): void { $this->resetPage(); }
    public function updatingFuelDateTo(): void { $this->resetPage(); }

    // Maintenance filter updates
    public function updatingMaintenanceSearch(): void { $this->resetPage(); }
    public function updatingMaintenanceRegNo(): void { $this->resetPage(); }
    public function updatingMaintenanceType(): void { $this->resetPage(); }
    public function updatingMaintenanceStatus(): void { $this->resetPage(); }
    public function updatingMaintenanceDateFrom(): void { $this->resetPage(); }
    public function updatingMaintenanceDateTo(): void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }
    public function updatingAlertOfflineMinutes(): void { $this->resetPage(); }
    public function updatingOnlineWindowMinutes(): void { $this->resetPage(); }

    public function refreshNow(): void
    {
        $this->dispatch('charts-refresh');
    }

    public function resetFuelFilters(): void
    {
        $this->fuelSearch = '';
        $this->fuelRegNo = '';
        $this->fuelType = '';
        $this->fuelStatus = '';
        $this->fuelDateFrom = '';
        $this->fuelDateTo = '';
        $this->resetPage();
    }

    public function resetMaintenanceFilters(): void
    {
        $this->maintenanceSearch = '';
        $this->maintenanceRegNo = '';
        $this->maintenanceType = '';
        $this->maintenanceStatus = '';
        $this->maintenanceDateFrom = '';
        $this->maintenanceDateTo = '';
        $this->resetPage();
    }

    public function openSummaryModal(): void
    {
        $this->showSummaryModal = true;
        // helpful if you render charts inside the modal
        $this->dispatch('summary-opened');
    }

    public function closeSummaryModal(): void
    {
        $this->showSummaryModal = false;
    }

    /**
     * Drill-down from charts (Livewire JS dispatch)
     * Livewire v3 passes payload as first argument, not DI array.
     */
    #[On('set-dashboard-filters')]
    public function applyFilters($payload = null): void
    {
        if ($payload === null) return;

        if (!is_array($payload)) {
            $payload = (array) $payload;
        }

        // strict allow-list
        foreach (['filterConnectivity','filterStatus','filterType','search','perPage'] as $key) {
            if (!array_key_exists($key, $payload)) continue;

            $val = $payload[$key];

            // ignore empty values
            if ($val === null || $val === '') continue;

            // keep typing sane
            if ($key === 'perPage') {
                $this->perPage = (int) $val;
                continue;
            }

            $this->$key = (string) $val;
        }

        $this->resetPage();
    }

    // -----------------------------
    // Time helpers
    // -----------------------------
    private function cutoff(): Carbon
    {
        return now()->subMinutes($this->onlineWindowMinutes);
    }

    private function alertCutoff(): Carbon
    {
        return now()->subMinutes($this->alertOfflineMinutes);
    }

    private function rangeFrom(): Carbon
    {
        return Carbon::parse($this->dateFrom)->startOfDay();
    }

    private function rangeTo(): Carbon
    {
        return Carbon::parse($this->dateTo)->endOfDay();
    }

    // -----------------------------
    // KPIs (Oracle safe) - uses connected_at
    // status: 1 = Active, else = Inactive
    // -----------------------------
    public function getKpisProperty(): array
    {
        $cutoff = $this->cutoff();

        $total = (int) Gps::query()->count();

        $active = (int) Gps::query()->where('status', 1)->count();
        $inactive = (int) Gps::query()
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 1);
            })
            ->count();

        // Online/offline/never based on connected_at AND active
        $online = (int) Gps::query()
            ->where('status', 1)
            ->whereNotNull('connected_at')
            ->where('connected_at', '>=', $cutoff)
            ->count();

        $offline = (int) Gps::query()
            ->where('status', 1)
            ->whereNotNull('connected_at')
            ->where('connected_at', '<', $cutoff)
            ->count();

        $neverSeen = (int) Gps::query()
            ->where('status', 1)
            ->whereNull('connected_at')
            ->count();

        $rangeSeen = (int) Gps::query()
            ->where('status', 1)
            ->whereNotNull('connected_at')
            ->whereBetween('connected_at', [$this->rangeFrom(), $this->rangeTo()])
            ->count();

        return compact('total','online','offline','neverSeen','active','inactive','rangeSeen');
    }

    // -----------------------------
    // Trend (Oracle safe) - per day using TRUNC
    // -----------------------------
    public function getTrendProperty(): array
    {
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $rows = Gps::query()
            ->selectRaw("TRUNC(connected_at) as day, COUNT(*) as cnt")
            ->whereNotNull('connected_at')
            ->whereBetween('connected_at', [$from, $to])
            ->groupByRaw("TRUNC(connected_at)")
            ->orderByRaw("TRUNC(connected_at)")
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = Carbon::parse($r->day)->format('Y-m-d');
            $map[$key] = (int) $r->cnt;
        }

        $days = $from->diffInDays($to);
        $series = [];
        for ($i = 0; $i <= $days; $i++) {
            $d = $from->copy()->addDays($i)->format('Y-m-d');
            $series[] = ['day' => $d, 'connections' => $map[$d] ?? 0];
        }

        // Add fuel and maintenance trends
        $fuelTrend = $this->getFuelTrendData();
        $maintenanceTrend = $this->getMaintenanceTrendData();

        foreach ($series as &$day) {
            $day['fuel_cost'] = $fuelTrend[$day['day']] ?? 0;
            $day['maintenance_cost'] = $maintenanceTrend[$day['day']] ?? 0;
        }

        return $series;
    }

    // -----------------------------
    // Fuel Trend Data (per day)
    // -----------------------------
    private function getFuelTrendData(): array
    {
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $fuelQuery = "
            SELECT 
                TRUNC(voucher_date) as day,
                SUM(amount) as daily_cost
            FROM fleetmaster.fuel_management
            WHERE voucher_date BETWEEN ? AND ?
            GROUP BY TRUNC(voucher_date)
            ORDER BY TRUNC(voucher_date)
        ";

        $results = \DB::select($fuelQuery, [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        
        $trend = [];
        foreach ($results as $r) {
            $key = Carbon::parse($r->day)->format('Y-m-d');
            $trend[$key] = (float) $r->daily_cost;
        }

        return $trend;
    }

    // -----------------------------
    // Maintenance Trend Data (per day)
    // -----------------------------
    private function getMaintenanceTrendData(): array
    {
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $maintenanceQuery = "
            SELECT 
                TRUNC(h.DATE_CREATED) as day,
                SUM(d.QUANTITY * d.PRICE) as daily_cost
            FROM fleetmaster.gen_material_details d
                INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
            WHERE h.status IN ('26', '32', '42', '46')
                AND h.IS_FUEL = 'N'
                AND h.DATE_CREATED BETWEEN ? AND ?
            GROUP BY TRUNC(h.DATE_CREATED)
            ORDER BY TRUNC(h.DATE_CREATED)
        ";

        $results = \DB::select($maintenanceQuery, [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        
        $trend = [];
        foreach ($results as $r) {
            $key = Carbon::parse($r->day)->format('Y-m-d');
            $trend[$key] = (float) $r->daily_cost;
        }

        return $trend;
    }

    public function getPieProperty(): array
    {
        $k = $this->kpis;

        return [
            'labels' => ['Online', 'Offline', 'Never Connected'],
            'values' => [(int)$k['online'], (int)$k['offline'], (int)$k['neverSeen']],
        ];
    }

    public function getTypesProperty(): array
    {
        return Cache::remember('gps:types', 300, function () {
            return Gps::query()
                ->selectRaw("NVL(type, 'UNKNOWN') as type")
                ->distinct()
                ->orderByRaw("NVL(type, 'UNKNOWN')")
                ->pluck('type')
                ->toArray();
        });
    }

    // -----------------------------
    // Devices table (Oracle safe)
    // -----------------------------
    public function getDevicesProperty()
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $q = Gps::query()
            ->when($this->search !== '', function ($qq) {
                $s = trim($this->search);
                $qq->where(function ($w) use ($s) {
                    $w->where('imei', 'like', "%{$s}%")
                        ->orWhere('serial', 'like', "%{$s}%")
                        ->orWhere('reg_number', 'like', "%{$s}%")
                        ->orWhere('model', 'like', "%{$s}%")
                        ->orWhere('mobile_number', 'like', "%{$s}%")
                        ->orWhere('type', 'like', "%{$s}%");
                });
            })
            ->when($this->filterStatus !== 'all', function ($qq) {
                if ($this->filterStatus === 'active') {
                    $qq->where('status', 1);
                } else {
                    $qq->where(function ($w) {
                        $w->whereNull('status')->orWhere('status', '!=', 1);
                    });
                }
            })
            ->when($this->filterType !== 'all', fn($qq) => $qq->where('type', $this->filterType))
            ->when($this->filterConnectivity !== 'all', function ($qq) use ($cutoff) {
                if ($this->filterConnectivity === 'online') {
                    $qq->whereNotNull('connected_at')->where('connected_at', '>=', $cutoff);
                } elseif ($this->filterConnectivity === 'offline') {
                    $qq->whereNotNull('connected_at')->where('connected_at', '<', $cutoff);
                } elseif ($this->filterConnectivity === 'never') {
                    $qq->whereNull('connected_at');
                }
            })
            ->where(function ($qq) use ($from, $to) {
                $qq->whereNull('connected_at')
                    ->orWhereBetween('connected_at', [$from, $to]);
            })
            ->orderByRaw("NVL(connected_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC");

        return $q->paginate($this->perPage);
    }

    // -----------------------------
    // Decision summary (dynamic)
    // -----------------------------
    public function getDecisionSummaryProperty(): array
    {
        $k = $this->kpis;

        $total = max(1, (int)($k['total'] ?? 1));
        $online = (int)($k['online'] ?? 0);
        $offline = (int)($k['offline'] ?? 0);
        $never = (int)($k['neverSeen'] ?? 0);
        $active = (int)($k['active'] ?? 0);

        $onlineRate = (int) round(($online / $total) * 100);
        $offlineRate = (int) round(($offline / $total) * 100);
        $neverRate = (int) round(($never / $total) * 100);
        $activeRate = (int) round(($active / $total) * 100);

        $health = 'success';
        if ($onlineRate < 80) $health = 'warning';
        if ($onlineRate < 50) $health = 'danger';

        $bullets = [];

        $bullets[] = [
            'title' => "Fleet health: {$onlineRate}% online",
            'text'  => 'Primary service KPI. Aim for ≥ 80% online.',
            'tag'   => strtoupper($health),
            'tagBg' => $health,
            'actionFilter' => ['filterConnectivity' => 'online'],
        ];

        $bullets[] = [
            'title' => "Operational risk: {$offlineRate}% offline",
            'text'  => $offlineRate >= 15
                ? 'If many units drop together: investigate GSM coverage or tracking server downtime. Otherwise: check vehicle power/antenna.'
                : 'Offline percentage is within a normal band. Focus on repeated offenders.',
            'tag'   => $offlineRate >= 15 ? 'INVESTIGATE' : 'NORMAL',
            'tagBg' => $offlineRate >= 15 ? 'warning' : 'success',
            'actionFilter' => ['filterConnectivity' => 'offline'],
        ];

        $bullets[] = [
            'title' => "Onboarding: {$neverRate}% never connected",
            'text'  => $neverRate >= 10
                ? 'Check SIM/APN, install power, device config (IP/Port), and correct IMEI provisioning.'
                : 'Onboarding looks stable. Keep monitoring new installs.',
            'tag'   => $neverRate >= 10 ? 'PRIORITY' : 'GOOD',
            'tagBg' => $neverRate >= 10 ? 'danger' : 'success',
            'actionFilter' => ['filterConnectivity' => 'never'],
        ];

        $bullets[] = [
            'title' => "Coverage: {$activeRate}% devices are Active",
            'text'  => 'Mark decommissioned devices Inactive to reduce alert noise and KPI distortion.',
            'tag'   => 'DATA HYGIENE',
            'tagBg' => 'dark',
            'actionFilter' => ['filterStatus' => 'active'],
        ];

        // Add fuel and maintenance insights
        $fk = $this->fuelKpis;
        $mk = $this->maintenanceKpis;
        $problemVehicles = $this->topProblemVehicles;

        // Fuel management insights
        if (($fk['overIssuedRate'] ?? 0) > 10) {
            $bullets[] = [
                'title' => "Fuel over-issuing: {$fk['overIssuedRate']}%",
                'text'  => 'High over-issuing indicates potential fuel theft or tank capacity issues. Review authorization processes.',
                'tag'   => 'ALERT',
                'tagBg' => 'warning',
                'actionFilter' => [],
            ];
        }

        // Maintenance insights
        if (($mk['maintenanceFrequency'] ?? 0) > 2) {
            $bullets[] = [
                'title' => "High maintenance frequency: {$mk['maintenanceFrequency']} jobs/vehicle",
                'text'  => 'Vehicles requiring frequent maintenance may need replacement or preventive maintenance schedules.',
                'tag'   => 'REVIEW',
                'tagBg' => 'warning',
                'actionFilter' => [],
            ];
        }

        // Most expensive vehicle insight
        if (!empty($problemVehicles['most_expensive'])) {
            $mostExpensive = $problemVehicles['most_expensive'][0] ?? null;
            if ($mostExpensive) {
                $totalCost = ($mostExpensive['fuel_cost'] ?? 0) + ($mostExpensive['maintenance_cost'] ?? 0);
                $bullets[] = [
                    'title' => "Most expensive: {$mostExpensive['reg_no']} (" . number_format($totalCost, 2) . ")",
                    'text'  => 'This vehicle has the highest combined fuel and maintenance costs. Consider cost-benefit analysis.',
                    'tag'   => 'COST',
                    'tagBg' => 'danger',
                    'actionFilter' => [],
                ];
            }
        }

        return compact('onlineRate','offlineRate','neverRate','activeRate','health','bullets');
    }

    // -----------------------------
    // Summary report for modal (Oracle safe) + uses status_label in UI
    // -----------------------------
    public function getSummaryReportProperty(): array
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $base = Gps::query()
            ->where(function ($q) use ($from, $to) {
                $q->whereNull('connected_at')
                    ->orWhereBetween('connected_at', [$from, $to]);
            });

        $activeCount = (int) (clone $base)->where('status', 1)->count();
        $inactiveCount = (int) (clone $base)->where(function ($q) {
            $q->whereNull('status')->orWhere('status', '!=', 1);
        })->count();

        $onlineCount = (int) (clone $base)
            ->whereNotNull('connected_at')
            ->where('connected_at', '>=', $cutoff)
            ->count();

        $offlineCount = (int) (clone $base)
            ->whereNotNull('connected_at')
            ->where('connected_at', '<', $cutoff)
            ->count();

        $neverCount = (int) (clone $base)->whereNull('connected_at')->count();

        $topTypes = (clone $base)
            ->selectRaw("NVL(type,'UNKNOWN') as label, COUNT(*) as cnt")
            ->groupByRaw("NVL(type,'UNKNOWN')")
            ->orderByRaw("COUNT(*) DESC")
            ->take(10)
            ->get()
            ->map(fn($r) => ['label' => $r->label, 'count' => (int)$r->cnt])
            ->toArray();

        $topModels = (clone $base)
            ->selectRaw("NVL(model,'UNKNOWN') as label, COUNT(*) as cnt")
            ->groupByRaw("NVL(model,'UNKNOWN')")
            ->orderByRaw("COUNT(*) DESC")
            ->take(10)
            ->get()
            ->map(fn($r) => ['label' => $r->label, 'count' => (int)$r->cnt])
            ->toArray();

        $longOfflineActive = (clone $base)
            ->where('status', 1)
            ->whereNotNull('connected_at')
            ->where('connected_at', '<', $cutoff)
            ->orderBy('connected_at', 'asc')
            ->take(10)
            ->get(['imei','reg_number','type','model','mobile_number','connected_at','status']);

        $neverConnected = (clone $base)
            ->whereNull('connected_at')
            ->orderByRaw("NVL(created_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC")
            ->take(10)
            ->get(['imei','reg_number','type','model','mobile_number','created_at','status']);

        $recentlyConnected = (clone $base)
            ->whereNotNull('connected_at')
            ->orderBy('connected_at', 'desc')
            ->take(10)
            ->get(['imei','reg_number','type','model','mobile_number','connected_at','status']);

        $missingReg = (int) (clone $base)->where(function ($q) {
            $q->whereNull('reg_number')->orWhere('reg_number', '=', '');
        })->count();

        $missingMobile = (int) (clone $base)->where(function ($q) {
            $q->whereNull('mobile_number')->orWhere('mobile_number', '=', '');
        })->count();

        $missingSerial = (int) (clone $base)->where(function ($q) {
            $q->whereNull('serial')->orWhere('serial', '=', '');
        })->count();

        $bullets = [];

        if ($neverCount > 0) {
            $bullets[] = [
                'title' => 'Onboarding gap (Never connected)',
                'tag' => $neverCount,
                'tagBg' => 'secondary',
                'text' => "There are {$neverCount} devices with no connected_at. Prioritise install checks: power, SIM/APN, server/port, IMEI configuration.",
                'actionFilter' => ['filterConnectivity' => 'never'],
            ];
        }

        if ($offlineCount > 0) {
            $bullets[] = [
                'title' => 'Operational risk (Offline)',
                'tag' => $offlineCount,
                'tagBg' => 'warning',
                'text' => "{$offlineCount} devices have connected_at older than the online window. Investigate network/server downtime or vehicle/device power.",
                'actionFilter' => ['filterConnectivity' => 'offline'],
            ];
        }

        $fleetPerf = ($activeCount + $inactiveCount) > 0
            ? (int) round(($onlineCount / max(1, ($activeCount + $inactiveCount))) * 100)
            : 0;

        $bullets[] = [
            'title' => 'Performance signal',
            'tag' => "{$fleetPerf}%",
            'tagBg' => $fleetPerf >= 80 ? 'success' : ($fleetPerf >= 50 ? 'warning' : 'danger'),
            'text' => "Online now: {$onlineCount}. Active: {$activeCount}. Use the filters and charts to drill down by type/model.",
            'actionFilter' => ['filterConnectivity' => 'online'],
        ];

        if (($missingReg + $missingMobile + $missingSerial) > 0) {
            $bullets[] = [
                'title' => 'Data quality',
                'tag' => ($missingReg + $missingMobile + $missingSerial),
                'tagBg' => 'dark',
                'text' => "Missing fields — Reg: {$missingReg}, Mobile: {$missingMobile}, Serial: {$missingSerial}. Clean data improves alerts and reporting.",
                'actionFilter' => [],
            ];
        }

        return [
            'counts' => [
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'online' => $onlineCount,
                'offline' => $offlineCount,
                'never' => $neverCount,
                'fleetPerf' => $fleetPerf,
            ],
            'topTypes' => $topTypes,
            'topModels' => $topModels,
            'risk' => [
                'longOfflineActive' => $longOfflineActive,
                'neverConnected' => $neverConnected,
                'recentlyConnected' => $recentlyConnected,
            ],
            'quality' => [
                'missingReg' => $missingReg,
                'missingMobile' => $missingMobile,
                'missingSerial' => $missingSerial,
            ],
            'bullets' => $bullets,
            // you already render modal charts separately; trend can reuse main $this->trend
            'trend' => $this->trend,
        ];
    }

    // -----------------------------
    // Charts payload (for Chart.js)
    // -----------------------------
    public function getChartPayloadProperty(): array
    {
        $k = $this->kpis;
        $total = max(1, (int)($k['total'] ?? 1));
        $perf = (int) round(((int)$k['online'] / $total) * 100);

        $trendData = $this->trend;

        return [
            'pie' => $this->pie,
            'trend' => [
                'labels' => array_map(fn($x) => $x['day'], $trendData),
                'connections' => array_map(fn($x) => $x['connections'], $trendData),
                'fuel_costs' => array_map(fn($x) => $x['fuel_cost'], $trendData),
                'maintenance_costs' => array_map(fn($x) => $x['maintenance_cost'], $trendData),
            ],
            'performance' => [
                'online' => (int)$k['online'],
                'offline' => (int)$k['offline'],
                'never' => (int)$k['neverSeen'],
                'active' => (int)$k['active'],
                'inactive' => (int)$k['inactive'],
                'perf' => $perf,
            ],
        ];
    }

    // -----------------------------
    // Fuel Management Data (Oracle safe)
    // -----------------------------
    public function getFuelDataProperty(): array
    {
        // Use custom date range if set, otherwise use main dashboard range
        $fuelDateFrom = $this->fuelDateFrom ?: $this->rangeFrom()->format('Y-m-d');
        $fuelDateTo = $this->fuelDateTo ?: $this->rangeTo()->format('Y-m-d');

        // Use the improved LEFT JOIN approach starting from GPS table
        // This query returns ALL vehicles with their LATEST fuel transaction
        $fuelQuery = "
            SELECT
                gps.reg_number AS reg_no,
                TRIM(NVL(vd.engine_brand, '') || ' ' || NVL(vh.model_name, '')) AS Type_Brand,
                NVL(vd.engine_capacity, 0) AS ENGINE_CAPACITY,
                NVL(vd.fuel_consumption, 0) AS KILOMETER_CONSUMPTION_PER_LITER,
                NVL(vd.tank_capacity, 0) AS main_tank,
                NVL(vd.sub_tank_capacity, 0) AS sub_tank,
                (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) AS total_tank_capacity,
                NVL(a.description, 'Unknown') AS FUEL_TYPE,
                f.document_no AS issue_document,
                f.voucher_date AS issue_date,
                NVL(f.qty, 0) AS qty_issued,
                NVL(f.price, 0) AS price_per_litre,
                NVL(f.ttl, 0) AS qty_issued_value,
                CASE
                    WHEN f.fueling_type = 10 THEN 'Normal'
                    WHEN f.fueling_type = 20 THEN 'Out-of-Town'
                    WHEN f.fueling_type = 30 THEN 'Override'
                    WHEN f.fueling_type IS NULL THEN '--'
                    ELSE 'Unknown'
                END AS FUELING_TYPE_NAME,
                CASE
                    WHEN NVL(f.qty, 0) > (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0))
                        THEN 'Y'
                    ELSE 'N'
                END AS Over_Issued,
                NVL(ou.description, '--') AS fuel_req_unit,
                gps.status AS vehicle_status,
                gps.mobile_number
            FROM fleetmaster.gps gps
            LEFT JOIN fleetmaster.vm_vehicle_header vh
                ON vh.registration_number = gps.reg_number
            LEFT JOIN fleetmaster.vm_engine_details vd
                ON vd.reg_no = gps.reg_number
            LEFT JOIN ZFM_ARTICLES_VIEW a
                ON vd.fuel_types = a.code_article
            LEFT JOIN (
                SELECT 
                    fm.reg_no,
                    fm.document_no,
                    fm.voucher_date,
                    fm.quantity AS qty,
                    fm.price,
                    fm.amount AS ttl,
                    fm.fueling_type,
                    fm.user_unit,
                    ROW_NUMBER() OVER (PARTITION BY fm.reg_no ORDER BY fm.voucher_date DESC) as rn
                FROM fleetmaster.fuel_management fm
                WHERE fm.voucher_date BETWEEN TO_DATE(?, 'YYYY-MM-DD') AND TO_DATE(?, 'YYYY-MM-DD')
            ) f
                ON f.reg_no = gps.reg_number AND f.rn = 1
            LEFT JOIN zfm_organizational_units_view ou
                ON f.user_unit = ou.code_unit
            WHERE gps.reg_number IS NOT NULL
        ";

        // Add dynamic filters with proper date filtering
        $bindings = [$fuelDateFrom, $fuelDateTo];
        
        if (!empty($this->fuelRegNo)) {
            $fuelQuery .= " AND UPPER(gps.reg_number) LIKE UPPER(?)";
            $bindings[] = '%' . $this->fuelRegNo . '%';
        }
        
        if (!empty($this->fuelType)) {
            $fuelQuery .= " AND UPPER(a.description) LIKE UPPER(?)";
            $bindings[] = '%' . $this->fuelType . '%';
        }
        
        if (!empty($this->fuelStatus)) {
            if ($this->fuelStatus === 'over_issued') {
                $fuelQuery .= " AND NVL(f.qty, 0) > (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0))";
            } elseif ($this->fuelStatus === 'normal') {
                $fuelQuery .= " AND NVL(f.qty, 0) <= (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0))";
            }
        }
        
        if (!empty($this->fuelSearch)) {
            $fuelQuery .= " AND (UPPER(gps.reg_number) LIKE UPPER(?) OR UPPER(vd.engine_brand) LIKE UPPER(?) OR UPPER(vh.model_name) LIKE UPPER(?) OR UPPER(f.document_no) LIKE UPPER(?))";
            $bindings[] = '%' . $this->fuelSearch . '%';
            $bindings[] = '%' . $this->fuelSearch . '%';
            $bindings[] = '%' . $this->fuelSearch . '%';
            $bindings[] = '%' . $this->fuelSearch . '%';
        }

        $fuelQuery .= " ORDER BY gps.reg_number";

        $fuelData = \DB::select($fuelQuery, $bindings);

        // Add row numbers for display
        $result = [];
        $rowNumber = 1;
        foreach ($fuelData as $row) {
            $row->rn = $rowNumber++;
            $row->month = $row->issue_date ? date('Ym', strtotime($row->issue_date)) : date('Ym');
            $result[] = $row;
        }

        return $result;
    }

    // -----------------------------
    // Comprehensive Fuel Usage Analysis (Oracle safe)
    // -----------------------------
    public function getFuelUsageAnalysisProperty(): array
    {
        // Use fuel date range for consistency with fuel data table
        $from = $this->fuelDateFrom ? Carbon::parse($this->fuelDateFrom) : $this->rangeFrom();
        $to = $this->fuelDateTo ? Carbon::parse($this->fuelDateTo) : $this->rangeTo();


        $fuelUsageQuery = "
            SELECT /*+ FIRST_ROWS(200) */
                f.reg_no,
                vd.engine_brand || ' ' || g.model_name AS type_brand,
                vd.engine_capacity AS engine_capacity,
                vd.fuel_consumption AS fuel_consumption,
                vd.tank_capacity AS tank_capacity,
                vd.sub_tank_capacity AS sub_tank_capacity,
                (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) AS total_tank_capacity,
                a.description AS fuel_type,
                COUNT(*) AS fueling_events,
                SUM(f.quantity) AS total_fuel_consumed,
                SUM(f.amount) AS total_fuel_cost,
                AVG(f.price) AS avg_price_per_liter,
                MIN(f.voucher_date) AS first_fueling_date,
                MAX(f.voucher_date) AS last_fueling_date,
                -- Consumption analysis
                CASE 
                    WHEN COUNT(*) > 1 THEN 
                        ROUND(SUM(f.quantity) / COUNT(*), 2)
                    ELSE 0 
                END AS avg_fuel_per_transaction,
                -- Cost efficiency
                ROUND(SUM(f.amount) / NULLIF(SUM(f.quantity), 0), 2) AS effective_cost_per_liter,
                -- Frequency analysis
                ROUND((MAX(f.voucher_date) - MIN(f.voucher_date)) / NULLIF(COUNT(*) - 1, 0), 0) AS days_between_fueling,
                -- Over-issuing analysis
                SUM(CASE 
                    WHEN f.quantity > (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) 
                    THEN 1 
                    ELSE 0 
                END) AS over_issued_count,
                -- Fueling type distribution
                SUM(CASE WHEN f.fueling_type = 10 THEN 1 ELSE 0 END) AS normal_fueling_count,
                SUM(CASE WHEN f.fueling_type = 20 THEN 1 ELSE 0 END) AS out_of_town_count,
                SUM(CASE WHEN f.fueling_type = 30 THEN 1 ELSE 0 END) AS override_count,
                -- Unit assignment
                ou.description AS assigned_unit,
                -- Performance indicators
                ROUND(
                    CASE 
                        WHEN vd.fuel_consumption > 0 AND SUM(f.quantity) > 0 THEN
                            (SUM(f.quantity) * vd.fuel_consumption)
                        ELSE 0 
                    END, 2
                ) AS estimated_distance_traveled,
                -- Efficiency rating
                CASE 
                    WHEN vd.fuel_consumption > 0 THEN
                        CASE 
                            WHEN (SUM(f.quantity) / NULLIF(COUNT(*), 0)) <= (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) * 0.8 THEN 'Excellent'
                            WHEN (SUM(f.quantity) / NULLIF(COUNT(*), 0)) <= (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) * 0.9 THEN 'Good'
                            WHEN (SUM(f.quantity) / NULLIF(COUNT(*), 0)) <= (NVL(vd.tank_capacity, 0) + NVL(vd.sub_tank_capacity, 0)) THEN 'Fair'
                            ELSE 'Poor'
                        END
                    ELSE 'Unknown'
                END AS efficiency_rating
            FROM fleetmaster.fuel_management f
                INNER JOIN FLEETMASTER.vm_vehicle_header g ON f.reg_no = g.REGISTRATION_NUMBER
                INNER JOIN FLEETMASTER.vm_engine_details vd ON g.REGISTRATION_NUMBER = vd.reg_no
                INNER JOIN ZFM_ARTICLES_VIEW a ON vd.fuel_types = a.code_article
                INNER JOIN zfm_organizational_units_view ou ON f.user_unit = ou.code_unit
                INNER JOIN fleetmaster.gps gps ON g.REGISTRATION_NUMBER = gps.REG_NUMBER
            WHERE f.voucher_date BETWEEN ? AND ?
            GROUP BY 
                f.reg_no,
                vd.engine_brand,
                g.model_name,
                vd.engine_capacity,
                vd.fuel_consumption,
                vd.tank_capacity,
                vd.sub_tank_capacity,
                a.description,
                ou.description
            ORDER BY total_fuel_cost DESC
        ";

        $fuelUsageData = \DB::select($fuelUsageQuery, [$from->format('Y-m-d'), $to->format('Y-m-d')]);

        return collect($fuelUsageData)->toArray();
    }

    // -----------------------------
    // Maintenance Data (Improved LEFT JOIN)
    // -----------------------------
    public function getMaintenanceDataProperty(): array
    {
        // Use broader date range for maintenance data
        $maintenanceDateFrom = $this->maintenanceDateFrom ?: now()->subYear()->format('Y-m-d');
        $maintenanceDateTo = $this->maintenanceDateTo ?: now()->format('Y-m-d');

        $maintenanceQuery = "
            SELECT 
                ROW_NUMBER() OVER (
                    ORDER BY gps.reg_number, m.document_date DESC, m.issue_no
                ) AS rn,
                gps.reg_number AS reg_no,
                TRIM(NVL(ed.engine_brand, '') || ' ' || NVL(vh.model_name, '')) AS type_brand,
                NVL(m.job_card_no, '--') AS job_card_no,
                NVL(m.issue_no, '--') AS issue_no,
                NVL(m.requi_number, '--') AS requi_number,
                m.document_date,
                NVL(m.article_description, 'No maintenance records found') AS article_description,
                NVL(m.vehicle_assignment, '--') AS vehicle_assignment,
                NVL(m.organizationalunit, '--') AS organizationalunit,
                NVL(m.value_amount, 0) AS value_amount,
                CASE
                    WHEN m.reg_no IS NULL THEN 'No Records'
                    ELSE m.status
                END AS maintenance_status,
                gps.status AS vehicle_status,
                gps.mobile_number
            FROM fleetmaster.gps gps
            LEFT JOIN fleetmaster.vm_vehicle_header vh
                ON vh.registration_number = gps.reg_number
            LEFT JOIN fleetmaster.vm_engine_details ed
                ON ed.reg_no = vh.registration_number
            LEFT JOIN (
                SELECT
                    d.reg_no,
                    h.st_pur AS requi_number,
                    mh.document_no AS issue_no,
                    h.document_no AS job_card_no,
                    h.date_created AS document_date,
                    LISTAGG(a.description, ', ') WITHIN GROUP (ORDER BY a.description) AS article_description,
                    TRIM(NVL(MAX(va.business_unit_name), '') || ' ' || NVL(MAX(va.cost_center_name), '')) AS vehicle_assignment,
                    MAX(td.organizationalunit) AS organizationalunit,
                    SUM(d.quantity * d.price) AS value_amount,
                    CASE 
                        WHEN h.status = '26' THEN 'Pending'
                        WHEN h.status = '32' THEN 'Approved'
                        WHEN h.status = '42' THEN 'In Progress'
                        WHEN h.status = '46' THEN 'Completed'
                        ELSE 'Unknown'
                    END AS status
                FROM fleetmaster.gen_material_details d
                INNER JOIN fleetmaster.gen_material_headers h
                    ON h.req_no = d.req_no
                INNER JOIN fleetmaster.vm_vehicle_header g
                    ON d.reg_no = g.registration_number
                INNER JOIN ZFM_ARTICLES_VIEW a
                    ON d.material_code = a.code_article
                LEFT JOIN fleetmaster.vm_assignments va
                    ON g.registration_number = va.reg_no
                LEFT JOIN fleetmaster.tms_data_clean_up td
                    ON g.registration_number = td.registrationnumber
                LEFT JOIN store_movements_header mh
                    ON h.st_pur = mh.stores_requisition_no
                WHERE h.status IN ('26', '32', '42', '46')
                  AND h.is_fuel = 'N'
                  AND h.date_created BETWEEN TO_DATE(?, 'YYYY-MM-DD') AND TO_DATE(?, 'YYYY-MM-DD')
                GROUP BY
                    d.reg_no,
                    h.st_pur,
                    mh.document_no,
                    h.document_no,
                    h.date_created,
                    h.status
            ) m
                ON m.reg_no = gps.reg_number
        ";

        // Add dynamic filters
        $bindings = [$maintenanceDateFrom, $maintenanceDateTo];
        
        if (!empty($this->maintenanceRegNo)) {
            $maintenanceQuery .= " WHERE UPPER(gps.reg_number) LIKE UPPER(?)";
            $bindings[] = '%' . $this->maintenanceRegNo . '%';
        }
        
        if (!empty($this->maintenanceStatus)) {
            $statusMap = [
                'pending' => '26',
                'approved' => '32',
                'in_progress' => '42',
                'completed' => '46'
            ];
            if (isset($statusMap[$this->maintenanceStatus])) {
                $maintenanceQuery .= (strpos($maintenanceQuery, 'WHERE') !== false ? ' AND' : ' WHERE') . " m.status = ?";
                $bindings[] = $statusMap[$this->maintenanceStatus];
            }
        }
        
        if (!empty($this->maintenanceSearch)) {
            $maintenanceQuery .= (strpos($maintenanceQuery, 'WHERE') !== false ? ' AND' : ' WHERE') . " (UPPER(gps.reg_number) LIKE UPPER(?) OR UPPER(ed.engine_brand) LIKE UPPER(?) OR UPPER(vh.model_name) LIKE UPPER(?) OR UPPER(m.article_description) LIKE UPPER(?) OR UPPER(m.job_card_no) LIKE UPPER(?))";
            $bindings[] = '%' . $this->maintenanceSearch . '%';
            $bindings[] = '%' . $this->maintenanceSearch . '%';
            $bindings[] = '%' . $this->maintenanceSearch . '%';
            $bindings[] = '%' . $this->maintenanceSearch . '%';
            $bindings[] = '%' . $this->maintenanceSearch . '%';
        }

        $maintenanceQuery .= " ORDER BY gps.reg_number, m.document_date DESC";

        $maintenanceData = \DB::select($maintenanceQuery, $bindings);

        return collect($maintenanceData)->toArray();
    }

    // ... (rest of the code remains the same)
    public function getFuelKpisProperty(): array
    {
        $fuelData = $this->fuelData;
        
        $totalFuelIssued = collect($fuelData)->sum('qty_issued');
        $totalFuelValue = collect($fuelData)->sum('qty_issued_value');
        $overIssuedCount = collect($fuelData)->where('over_issued', 'Y')->count();
        $uniqueVehicles = collect($fuelData)->unique('reg_no')->count();
        $avgPricePerLitre = $totalFuelIssued > 0 ? $totalFuelValue / $totalFuelIssued : 0;

        // Calculate fuel efficiency score
        $overIssuedRate = $uniqueVehicles > 0 ? ($overIssuedCount / $uniqueVehicles) * 100 : 0;
        $fuelScore = 100 - $overIssuedRate; // Higher score is better
        $fuelScore = max(0, min(100, $fuelScore)); // Clamp between 0-100

        // Determine badge color
        $fuelBadge = 'success';
        if ($fuelScore < 80) $fuelBadge = 'warning';
        if ($fuelScore < 60) $fuelBadge = 'danger';

        return [
            'totalIssued' => $totalFuelIssued,
            'totalValue' => $totalFuelValue,
            'overIssuedCount' => $overIssuedCount,
            'uniqueVehicles' => $uniqueVehicles,
            'avgPricePerLitre' => round($avgPricePerLitre, 2),
            'overIssuedRate' => round($overIssuedRate, 1),
            'fuelScore' => round($fuelScore, 0),
            'fuelBadge' => $fuelBadge
        ];
    }

    // -----------------------------
    // Fuel Usage KPIs and Analysis
    // -----------------------------
    public function getFuelUsageKpisProperty(): array
    {
        $fuelUsageData = $this->fuelUsageAnalysis;
        
        $totalVehicles = count($fuelUsageData);
        $totalFuelConsumed = collect($fuelUsageData)->sum('total_fuel_consumed');
        $totalFuelCost = collect($fuelUsageData)->sum('total_fuel_cost');
        $totalFuelingEvents = collect($fuelUsageData)->sum('fueling_events');
        $totalOverIssued = collect($fuelUsageData)->sum('over_issued_count');
        
        // Efficiency metrics
        $avgFuelPerVehicle = $totalVehicles > 0 ? $totalFuelConsumed / $totalVehicles : 0;
        $avgCostPerVehicle = $totalVehicles > 0 ? $totalFuelCost / $totalVehicles : 0;
        $avgEventsPerVehicle = $totalVehicles > 0 ? $totalFuelingEvents / $totalVehicles : 0;
        
        // Performance distribution
        $excellentVehicles = collect($fuelUsageData)->where('efficiency_rating', 'Excellent')->count();
        $goodVehicles = collect($fuelUsageData)->where('efficiency_rating', 'Good')->count();
        $fairVehicles = collect($fuelUsageData)->where('efficiency_rating', 'Fair')->count();
        $poorVehicles = collect($fuelUsageData)->where('efficiency_rating', 'Poor')->count();
        
        // Fueling type analysis
        $normalFueling = collect($fuelUsageData)->sum('normal_fueling_count');
        $outOfTownFueling = collect($fuelUsageData)->sum('out_of_town_count');
        $overrideFueling = collect($fuelUsageData)->sum('override_count');
        
        // Calculate overall efficiency score
        $efficiencyScore = 0;
        if ($totalVehicles > 0) {
            $efficiencyScore = (($excellentVehicles * 100) + ($goodVehicles * 75) + ($fairVehicles * 50) + ($poorVehicles * 25)) / $totalVehicles;
        }
        
        // Determine badge color for fuel usage
        $fuelUsageBadge = 'success';
        if ($efficiencyScore < 75) $fuelUsageBadge = 'warning';
        if ($efficiencyScore < 50) $fuelUsageBadge = 'danger';
        
        return [
            'totalVehicles' => $totalVehicles,
            'totalFuelConsumed' => round($totalFuelConsumed, 2),
            'totalFuelCost' => round($totalFuelCost, 2),
            'totalFuelingEvents' => $totalFuelingEvents,
            'totalOverIssued' => $totalOverIssued,
            'avgFuelPerVehicle' => round($avgFuelPerVehicle, 2),
            'avgCostPerVehicle' => round($avgCostPerVehicle, 2),
            'avgEventsPerVehicle' => round($avgEventsPerVehicle, 1),
            'excellentVehicles' => $excellentVehicles,
            'goodVehicles' => $goodVehicles,
            'fairVehicles' => $fairVehicles,
            'poorVehicles' => $poorVehicles,
            'normalFueling' => $normalFueling,
            'outOfTownFueling' => $outOfTownFueling,
            'overrideFueling' => $overrideFueling,
            'efficiencyScore' => round($efficiencyScore, 0),
            'fuelUsageBadge' => $fuelUsageBadge
        ];
    }

    // -----------------------------
    // Maintenance KPIs and Scoring
    // -----------------------------
    public function getMaintenanceKpisProperty(): array
    {
        $maintenanceData = $this->maintenanceData;
        
        $totalMaintenanceCost = collect($maintenanceData)->sum('value_amount');
        $maintenanceCount = count($maintenanceData);
        $uniqueVehicles = collect($maintenanceData)->unique('reg_no')->count();
        $avgCostPerJob = $maintenanceCount > 0 ? $totalMaintenanceCost / $maintenanceCount : 0;
        
        // Calculate maintenance frequency (jobs per vehicle)
        $maintenanceFrequency = $uniqueVehicles > 0 ? $maintenanceCount / $uniqueVehicles : 0;
        
        // Maintenance score based on frequency and cost
        $frequencyScore = min(100, max(0, 100 - ($maintenanceFrequency - 1) * 20)); // Ideal is 1 job per vehicle
        $costScore = $avgCostPerJob > 0 ? min(100, max(0, 100 - ($avgCostPerJob / 100) * 10)) : 100; // Lower cost is better
        $maintenanceScore = ($frequencyScore + $costScore) / 2;

        // Determine badge color
        $maintenanceBadge = 'success';
        if ($maintenanceScore < 80) $maintenanceBadge = 'warning';
        if ($maintenanceScore < 60) $maintenanceBadge = 'danger';

        return [
            'totalCost' => $totalMaintenanceCost,
            'jobCount' => $maintenanceCount,
            'uniqueVehicles' => $uniqueVehicles,
            'avgCostPerJob' => round($avgCostPerJob, 2),
            'maintenanceFrequency' => round($maintenanceFrequency, 1),
            'maintenanceScore' => round($maintenanceScore, 0),
            'maintenanceBadge' => $maintenanceBadge
        ];
    }

    // -----------------------------
    // Top Problem Vehicles
    // -----------------------------
    public function getTopProblemVehiclesProperty(): array
    {
        $fuelData = $this->fuelData;
        $maintenanceData = $this->maintenanceData;
        
        // Most expensive vehicles (fuel + maintenance)
        $fuelByVehicle = collect($fuelData)->groupBy('reg_no')->map(function($group) {
            return [
                'reg_no' => $group->first()->reg_no,
                'type_brand' => $group->first()->type_brand,
                'fuel_cost' => $group->sum('qty_issued_value'),
                'over_issued_count' => $group->where('over_issued', 'Y')->count()
            ];
        });

        $maintenanceByVehicle = collect($maintenanceData)->groupBy('reg_no')->map(function($group) {
            return [
                'reg_no' => $group->first()->reg_no,
                'type_brand' => $group->first()->type_brand,
                'maintenance_cost' => $group->sum('value_amount'),
                'job_count' => $group->count()
            ];
        });

        // Combine fuel and maintenance data
        $combined = $fuelByVehicle->map(function($fuel) use ($maintenanceByVehicle) {
            $maintenance = $maintenanceByVehicle->get($fuel['reg_no'], [
                'maintenance_cost' => 0,
                'job_count' => 0
            ]);
            
            return array_merge($fuel, $maintenance);
        });

        // Sort by total cost (fuel + maintenance)
        $mostExpensive = $combined->sortByDesc(function($item) {
            return $item['fuel_cost'] + $item['maintenance_cost'];
        })->take(5)->values();

        // Vehicles with highest maintenance frequency
        $highMaintenanceFreq = $maintenanceByVehicle->sortByDesc('job_count')->take(5)->values();

        // Vehicles with repeated over-issuing
        $repeatedOverIssued = $fuelByVehicle->sortByDesc('over_issued_count')->take(5)->values();

        return [
            'most_expensive' => $mostExpensive->toArray(),
            'high_maintenance_freq' => $highMaintenanceFreq->toArray(),
            'repeated_over_issued' => $repeatedOverIssued->toArray()
        ];
    }

    // -----------------------------
    // AI insight (computed) - FIXES "Property [$aiInsight] not found"
    // Use in blade: {{ $aiInsight }}
    // -----------------------------
    public function getAiInsightProperty(): string
    {
        $key = "gps:ai:{$this->onlineWindowMinutes}:{$this->dateFrom}:{$this->dateTo}:{$this->alertOfflineMinutes}";

        return Cache::remember($key, 60, function () {
            $k = $this->kpis;
            $trend = $this->trend;

            $total = (int)($k['total'] ?? 0);
            $online = (int)($k['online'] ?? 0);
            $offline = (int)($k['offline'] ?? 0);
            $never = (int)($k['neverSeen'] ?? 0);

            $onlineRate = $total > 0 ? (int) round(($online / $total) * 100) : 0;

            $avg = count($trend) ? (int) round(collect($trend)->avg('count')) : 0;
            $latest = count($trend) ? (int) ($trend[count($trend) - 1]['count'] ?? 0) : 0;
            $delta = $latest - $avg;

            $msg = [];
            $msg[] = "Fleet health: {$onlineRate}% online ({$online}/{$total}).";

            if ($never > 0) {
                $msg[] = "{$never} devices have never connected (SIM/APN/power/server/port).";
            }

            if ($offline > 0) {
                $msg[] = "{$offline} active devices are offline beyond {$this->onlineWindowMinutes} minutes.";
            }

            if ($avg > 0) {
                if ($delta >= 10) $msg[] = "Connections are above normal today (+{$delta} vs avg).";
                if ($delta <= -10) $msg[] = "Connections are below normal today ({$delta} vs avg). Investigate network/server.";
            }

            return implode(' ', $msg);
        });
    }

    // -----------------------------
    // Export
    // -----------------------------
    public function exportCsv()
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $q = Gps::query()
            ->when($this->search !== '', function ($qq) {
                $s = trim($this->search);
                $qq->where(function ($w) use ($s) {
                    $w->where('imei', 'like', "%{$s}%")
                        ->orWhere('serial', 'like', "%{$s}%")
                        ->orWhere('reg_number', 'like', "%{$s}%")
                        ->orWhere('model', 'like', "%{$s}%")
                        ->orWhere('mobile_number', 'like', "%{$s}%")
                        ->orWhere('type', 'like', "%{$s}%");
                });
            })
            ->when($this->filterStatus !== 'all', function ($qq) {
                if ($this->filterStatus === 'active') {
                    $qq->where('status', 1);
                } else {
                    $qq->where(function ($w) {
                        $w->whereNull('status')->orWhere('status', '!=', 1);
                    });
                }
            })
            ->when($this->filterType !== 'all', fn($qq) => $qq->where('type', $this->filterType))
            ->when($this->filterConnectivity !== 'all', function ($qq) use ($cutoff) {
                if ($this->filterConnectivity === 'online') {
                    $qq->whereNotNull('connected_at')->where('connected_at', '>=', $cutoff);
                } elseif ($this->filterConnectivity === 'offline') {
                    $qq->whereNotNull('connected_at')->where('connected_at', '<', $cutoff);
                } elseif ($this->filterConnectivity === 'never') {
                    $qq->whereNull('connected_at');
                }
            })
            ->where(function ($qq) use ($from, $to) {
                $qq->whereNull('connected_at')->orWhereBetween('connected_at', [$from, $to]);
            })
            ->orderByRaw("NVL(connected_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC");

        $rows = $q->get([
            'imei','model','type','reg_number','mobile_number','status','connected_at','last_seen_at','type_id'
        ]);

        $filename = "gps_dashboard_export_" . now()->format('Ymd_His') . ".csv";

        return Response::stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['IMEI','MODEL','TYPE','REG_NUMBER','MOBILE','STATUS','CONNECTED_AT','LAST_SEEN_AT','TYPE_ID']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->imei,
                    $r->model,
                    $r->type,
                    $r->reg_number,
                    $r->mobile_number,
                    ((int)$r->status === 1) ? 'Active' : 'Inactive',
                    optional($r->connected_at)->toDateTimeString(),
                    optional($r->last_seen_at)->toDateTimeString(),
                    $r->type_id,
                ]);
            }
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function render()
    {
        return view('livewire.vehicle-management.dashboard.dashboard', [
            'kpis' => $this->kpis,
            'types' => $this->types,
            'devices' => $this->devices,
            'cutoff' => $this->cutoff(),
            'chartPayload' => $this->chartPayload,

            // computed helpers
            'aiInsight' => $this->aiInsight,
            'decisionSummary' => $this->decisionSummary,
            'summaryReport' => $this->summaryReport,
            'showSummaryModal' => $this->showSummaryModal,

            // fuel and maintenance data
            'fuelData' => $this->fuelData,
            'maintenanceData' => $this->maintenanceData,
            'fuelKpis' => $this->fuelKpis,
            'maintenanceKpis' => $this->maintenanceKpis,
            'topProblemVehicles' => $this->topProblemVehicles,
            
            // fuel usage analysis
            'fuelUsageAnalysis' => $this->fuelUsageAnalysis,
            'fuelUsageKpis' => $this->fuelUsageKpis,
        ]);
    }
}
