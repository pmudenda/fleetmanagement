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

    // Modal
    public bool $showSummaryModal = false;

    public function mount(): void
    {
        if ($this->dateTo === '') {
            $this->dateTo = now()->format('Y-m-d');
        }
        if ($this->dateFrom === '') {
            $this->dateFrom = now()->subDays(14)->format('Y-m-d');
        }
    }

    // -----------------------------
    // UI lifecycle
    // -----------------------------
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterConnectivity(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }
    public function updatingAlertOfflineMinutes(): void { $this->resetPage(); }
    public function updatingOnlineWindowMinutes(): void { $this->resetPage(); }

    public function refreshNow(): void
    {
        $this->dispatch('charts-refresh');
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
            $series[] = ['day' => $d, 'count' => $map[$d] ?? 0];
        }

        return $series;
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

        return [
            'pie' => $this->pie,
            'trend' => [
                'labels' => array_map(fn($x) => $x['day'], $this->trend),
                'values' => array_map(fn($x) => $x['count'], $this->trend),
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
        ]);
    }
}
