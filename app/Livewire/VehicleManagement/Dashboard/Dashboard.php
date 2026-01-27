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

    #[Url] public string $filterConnectivity = 'all'; // all|online|offline|never  (BASED ON connected_at)
    #[Url] public string $filterStatus = 'all';       // all|active|inactive      (active == status 1)
    #[Url] public string $filterType = 'all';

    #[Url] public string $search = '';
    #[Url] public int $perPage = 10;

    public function mount(): void
    {
        if ($this->dateTo === '') $this->dateTo = now()->format('Y-m-d');
        if ($this->dateFrom === '') $this->dateFrom = now()->subDays(14)->format('Y-m-d');
    }

    // ----------------------------
    // ✅ Event listeners (optional)
    // ----------------------------
    #[On('echo:gps,TelemetryReceived')]
    public function onTelemetryReceived(): void
    {
        $this->softRefresh();
    }

    #[On('gps-updated')]
    public function onGpsUpdated(): void
    {
        $this->softRefresh();
    }

    #[On('set-dashboard-filters')]
    public function setDashboardFilters(array $payload = []): void
    {
        if (isset($payload['filterConnectivity'])) $this->filterConnectivity = $payload['filterConnectivity'];
        if (isset($payload['filterStatus'])) $this->filterStatus = $payload['filterStatus'];
        if (isset($payload['filterType'])) $this->filterType = $payload['filterType'];

        $this->resetPage();
        $this->dispatch('charts-refresh');
    }

    private function softRefresh(): void
    {
        Cache::forget('gps:types');
        $this->dispatch('charts-refresh');
    }

    // ----------------------------
    // Reset page on filter changes
    // ----------------------------
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
        $this->softRefresh();
    }

    // ----------------------------
    // Helpers
    // ----------------------------
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

    private function baseQuery()
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $q = Gps::query();

        // Search
        if (trim($this->search) !== '') {
            $s = trim($this->search);
            $q->where(function ($w) use ($s) {
                $w->where('imei', 'like', "%{$s}%")
                        ->orWhere('serial', 'like', "%{$s}%")
                        ->orWhere('reg_number', 'like', "%{$s}%")
                        ->orWhere('model', 'like', "%{$s}%")
                        ->orWhere('mobile_number', 'like', "%{$s}%")
                        ->orWhere('type', 'like', "%{$s}%");
            });
        }

        // Status filter (active == 1)
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'active') {
                $q->where('status', 1);
            } else {
                $q->where(function ($w) {
                    $w->whereNull('status')->orWhere('status', '!=', 1);
                });
            }
        }

        // Type filter
        if ($this->filterType !== 'all') {
            $q->where('type', $this->filterType);
        }

        // Connectivity filter (BASED ON connected_at)
        if ($this->filterConnectivity !== 'all') {
            if ($this->filterConnectivity === 'online') {
                $q->whereNotNull('connected_at')->where('connected_at', '>=', $cutoff);
            } elseif ($this->filterConnectivity === 'offline') {
                $q->whereNotNull('connected_at')->where('connected_at', '<', $cutoff);
            } elseif ($this->filterConnectivity === 'never') {
                $q->whereNull('connected_at');
            }
        }

        // In selected period (connected_at) OR never
        $q->where(function ($qq) use ($from, $to) {
            $qq->whereNull('connected_at')->orWhereBetween('connected_at', [$from, $to]);
        });

        // Order by connected_at (Oracle-safe)
        $q->orderByRaw("NVL(connected_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC");

        return $q;
    }

    // ----------------------------
    // KPIs (BASED ON connected_at)
    // ----------------------------
    public function getKpisProperty(): array
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $total = (int) Gps::query()->count();

        $active = (int) Gps::query()->where('status', 1)->count();
        $inactive = (int) Gps::query()->where(function ($q) {
            $q->whereNull('status')->orWhere('status', '!=', 1);
        })->count();

        // ✅ Online = Active AND connected_at within window
        $online = (int) Gps::query()
                ->where('status', 1)
                ->whereNotNull('connected_at')
                ->where('connected_at', '>=', $cutoff)
                ->count();

        // ✅ Offline = Active AND connected_at older than window
        $offline = (int) Gps::query()
                ->where('status', 1)
                ->whereNotNull('connected_at')
                ->where('connected_at', '<', $cutoff)
                ->count();

        // ✅ Never connected = Active AND connected_at is null
        $neverSeen = (int) Gps::query()
                ->where('status', 1)
                ->whereNull('connected_at')
                ->count();

        $rangeSeen = (int) Gps::query()
                ->where('status', 1)
                ->whereNotNull('connected_at')
                ->whereBetween('connected_at', [$from, $to])
                ->count();

        return compact('total', 'online', 'offline', 'neverSeen', 'active', 'inactive', 'rangeSeen');
    }

    // ----------------------------
    // Trend (connected_at)
    // ----------------------------
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

    public function getDevicesProperty()
    {
        return $this->baseQuery()
                ->select(['imei','model','type','reg_number','mobile_number','status','connected_at','last_seen_at'])
                ->paginate($this->perPage);
    }

    public function getAiInsightProperty(): string
    {
        $k = $this->kpis;
        $total = (int)($k['total'] ?? 0);
        $online = (int)($k['online'] ?? 0);
        $active = (int)($k['active'] ?? 0);
        $never = (int)($k['neverSeen'] ?? 0);

        $onlineRate = $total ? round(($online / $total) * 100) : 0;
        $activeRate = $total ? round(($active / $total) * 100) : 0;

        $msg = [];
        $msg[] = "Connectivity health: {$onlineRate}% online based on connected_at within {$this->onlineWindowMinutes} minutes.";
        $msg[] = "Active coverage: {$activeRate}% devices are marked Active (status=1).";
        if ($never > 0) $msg[] = "{$never} active devices have never connected (check SIM/APN/power/install).";

        return implode(' ', $msg);
    }

    public function getPerformanceProperty(): array
    {
        // Used for the new interactive charts under AI insight
        $k = $this->kpis;

        $total = (int)($k['total'] ?? 0);
        $online = (int)($k['online'] ?? 0);
        $offline = (int)($k['offline'] ?? 0);
        $never = (int)($k['neverSeen'] ?? 0);
        $active = (int)($k['active'] ?? 0);
        $inactive = (int)($k['inactive'] ?? 0);

        // We decide performance based on online vs total (or you can make it online vs active)
        $perf = $total ? (int) round(($online / $total) * 100) : 0;

        return compact('total','online','offline','never','active','inactive','perf');
    }

    public function getChartPayloadProperty(): array
    {
        return [
                'pie' => $this->pie,
                'trend' => [
                        'labels' => array_map(fn($x) => $x['day'], $this->trend),
                        'values' => array_map(fn($x) => $x['count'], $this->trend),
                ],
                'performance' => $this->performance,
        ];
    }

    public function exportCsv()
    {
        $rows = $this->baseQuery()
                ->select(['imei','model','type','reg_number','mobile_number','status','connected_at','last_seen_at','type_id'])
                ->get();

        $filename = "gps_dashboard_export_" . now()->format('Ymd_His') . ".csv";

        $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['IMEI','MODEL','TYPE','REG_NUMBER','MOBILE','STATUS','CONNECTED_AT','LAST_SEEN_AT','TYPE_ID']);

            foreach ($rows as $r) {
                $statusLabel = ((int)($r->status ?? 0) === 1) ? 'Active' : 'Inactive';

                fputcsv($out, [
                        $r->imei,
                        $r->model,
                        $r->type,
                        $r->reg_number,
                        $r->mobile_number,
                        $statusLabel,
                        optional($r->connected_at)->toDateTimeString(),
                        optional($r->last_seen_at)->toDateTimeString(),
                        $r->type_id,
                ]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.vehicle-management.dashboard.dashboard', [
                'kpis' => $this->kpis,
                'types' => $this->types,
                'devices' => $this->devices,
                'cutoff' => $this->cutoff(),
                'chartPayload' => $this->chartPayload,
                'alertCutoff' => $this->alertCutoff(),
        ]);
    }
}
