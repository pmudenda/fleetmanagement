<?php

namespace App\Livewire\VehicleManagement\Dashboard;

use App\Models\Gps\Gps;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Support\Telemetry;
use App\Models\Analytics\DashboardInsight;
use App\Models\Analytics\GpsUptimeDaily;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    #[Url]
    public int $onlineWindowMinutes = 15;

    // Alerts threshold (minutes offline to flag)
    #[Url]
    public int $alertOfflineMinutes = 30;

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    #[Url]
    public string $filterConnectivity = 'all'; // all|online|offline|never

    #[Url]
    public string $filterStatus = 'all';       // all|active|inactive

    #[Url]
    public string $filterType = 'all';         // TELTONIKA etc.

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 10;

    public function mount(): void
    {
        if ($this->dateTo === '') {
            $this->dateTo = now()->format('Y-m-d');
        }
        if ($this->dateFrom === '') {
            $this->dateFrom = now()->subDays(14)->format('Y-m-d');
        }
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterConnectivity(): void {
        $this->resetPage();


    }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }
    public function updatingAlertOfflineMinutes(): void { $this->resetPage(); }

    public function refreshNow(): void
    {
        // triggers frontend redraw and also gives user feedback
        $this->dispatch('charts-refresh');
    }

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

    public function getKpisProperty(): array
    {
        $cutoff = $this->cutoff();

        $total = Gps::query()->count();

        $online = Gps::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $cutoff)
            ->count();

        $offline = Gps::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '<', $cutoff)
            ->count();

        $neverSeen = Gps::query()->whereNull('last_seen_at')->count();

        $active = Gps::query()->where('status', 'active')->count();
        $inactive = Gps::query()
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'active');
            })
            ->count();

        $rangeSeen = Gps::query()
            ->whereNotNull('last_seen_at')
            ->whereBetween('last_seen_at', [$this->rangeFrom(), $this->rangeTo()])
            ->count();

        return compact('total', 'online', 'offline', 'neverSeen', 'active', 'inactive', 'rangeSeen');
    }

    public function getTrendProperty(): array
    {
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        $rows = Gps::query()
            ->selectRaw("TRUNC(last_seen_at) as day, COUNT(*) as cnt")
            ->whereNotNull('last_seen_at')
            ->whereBetween('last_seen_at', [$from, $to])
            ->groupByRaw("TRUNC(last_seen_at)")
            ->orderByRaw("TRUNC(last_seen_at)")
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = Carbon::parse($r->day)->format('Y-m-d');
            $map[$key] = (int)$r->cnt;
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
            'labels' => ['Online', 'Offline', 'Never Seen'],
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
                    $qq->where('status', 'active');
                } else {
                    $qq->where(function ($w) {
                        $w->whereNull('status')->orWhere('status', '!=', 'active');
                    });
                }
            })
            ->when($this->filterType !== 'all', fn($qq) => $qq->where('type', $this->filterType))
            ->when($this->filterConnectivity !== 'all', function ($qq) use ($cutoff) {
                if ($this->filterConnectivity === 'online') {
                    $qq->whereNotNull('last_seen_at')->where('last_seen_at', '>=', $cutoff);
                } elseif ($this->filterConnectivity === 'offline') {
                    $qq->whereNotNull('last_seen_at')->where('last_seen_at', '<', $cutoff);
                } elseif ($this->filterConnectivity === 'never') {
                    $qq->whereNull('last_seen_at');
                }
            })
            // activity in selected period OR never
            ->where(function ($qq) use ($from, $to) {
                $qq->whereNull('last_seen_at')
                    ->orWhereBetween('last_seen_at', [$from, $to]);
            })
            ->orderByRaw("NVL(last_seen_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC");

        return $q->paginate($this->perPage);
    }

    /**
     *  Alerts list: devices that are offline beyond alertOfflineMinutes but still marked active,
     * plus never-seen devices.
     */
    public function getAlertsProperty(): array
    {
        $alertCutoff = $this->alertCutoff();
        $cutoff = $this->cutoff();

        $offlineLong = Gps::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '<', $alertCutoff)
            ->where('status', 'active')
            ->orderBy('last_seen_at', 'asc')
            ->take(10)
            ->get(['imei', 'model', 'type', 'reg_number', 'mobile_number', 'last_seen_at', 'status']);

        $never = Gps::query()
            ->whereNull('last_seen_at')
            ->orderByRaw("NVL(created_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC")
            ->take(10)
            ->get(['imei', 'model', 'type', 'reg_number', 'mobile_number', 'created_at', 'status']);

        return [
            'cutoffOnline' => $cutoff,
            'cutoffAlert' => $alertCutoff,
            'offlineLong' => $offlineLong,
            'neverSeen' => $never,
        ];
    }

    /**
     *  Uptime approximation:
     * - We don’t have full ping history in this table.
     * - So we approximate uptime score using "was seen during selected period" and "currently online".
     * This is still very useful for ranking trouble devices.
     */
    public function getUptimeProperty(): array
    {
        $from = $this->rangeFrom();
        $to = $this->rangeTo();
        $cutoff = $this->cutoff();

        // Score:
        // 100 = seen in period AND online now
        // 70  = seen in period but offline now
        // 30  = never seen in period (or never)

        $rows = Gps::query()
            ->select(['imei', 'model', 'type', 'reg_number', 'mobile_number', 'last_seen_at', 'status'])
            ->where(function ($q) use ($from, $to) {
                $q->whereNull('last_seen_at')->orWhereBetween('last_seen_at', [$from, $to]);
            })
            ->orderByRaw("NVL(last_seen_at, TO_DATE('1900-01-01','YYYY-MM-DD')) ASC")
            ->take(10)
            ->get();

        $out = [];
        foreach ($rows as $d) {
            $seenInRange = $d->last_seen_at && $d->last_seen_at->between($from, $to);
            $isOnline = $d->last_seen_at && $d->last_seen_at >= $cutoff;

            $score = 30;
            if ($seenInRange && $isOnline) $score = 100;
            elseif ($seenInRange) $score = 70;

            $out[] = [
                'imei' => $d->imei,
                'model' => $d->model,
                'reg' => $d->reg_number,
                'type' => $d->type,
                'mobile' => $d->mobile_number,
                'status' => $d->status,
                'last_seen_at' => $d->last_seen_at,
                'score' => $score,
            ];
        }

        return $out;
    }

    /**
     *  Root-cause grouping (simple, fast, actionable)
     */
    public function getRootCausesProperty(): array
    {
        $cutoff = $this->cutoff();
        $alertCutoff = $this->alertCutoff();

        $never = (int)Gps::query()->whereNull('last_seen_at')->count();

        $activeButOffline = (int)Gps::query()
            ->where('status', 'active')
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '<', $cutoff)
            ->count();

        $longOffline = (int)Gps::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '<', $alertCutoff)
            ->count();

        $recentOutageCluster = (int)Gps::query()
            ->whereNotNull('last_seen_at')
            ->whereBetween('last_seen_at', [now()->subMinutes(90), now()->subMinutes(30)])
            ->count();

        return [
            [
                'title' => 'Never reported',
                'count' => $never,
                'hint' => 'Likely onboarding issue: SIM/APN, power wiring, device not installed, wrong server/port.',
            ],
            [
                'title' => 'Active but offline',
                'count' => $activeButOffline,
                'hint' => 'Operational risk: device should be online but is offline. Check vehicle power + SIM network.',
            ],
            [
                'title' => "Offline > {$this->alertOfflineMinutes} mins",
                'count' => $longOffline,
                'hint' => 'Potential prolonged outage: network issue, server unreachable, device power loss.',
            ],
            [
                'title' => 'Possible outage window (last 90→30 mins)',
                'count' => $recentOutageCluster,
                'hint' => 'If many devices dropped within same window, check APN/network or tracking server downtime.',
            ],
        ];
    }

    public function getAiInsightProperty(): string
    {
        $key = "gps:ai:{$this->onlineWindowMinutes}:{$this->dateFrom}:{$this->dateTo}:{$this->alertOfflineMinutes}";

        return Cache::remember($key, 60, function () {
            $k = $this->kpis;
            $trend = $this->trend;

            $avg = count($trend) ? (int)round(collect($trend)->avg('count')) : 0;
            $latest = count($trend) ? $trend[count($trend) - 1]['count'] : 0;

            $onlineRate = $k['total'] ? round(($k['online'] / $k['total']) * 100) : 0;

            $msg = [];
            $msg[] = "Fleet health: {$onlineRate}% online ({$k['online']} / {$k['total']}).";
            $msg[] = "Seen in selected period: {$k['rangeSeen']}.";

            if ($avg > 0) {
                $delta = $latest - $avg;
                if ($delta >= 10) $msg[] = "Reporting is above normal today (+{$delta} vs avg).";
                if ($delta <= -10) $msg[] = "Reporting is below normal today ({$delta} vs avg). Investigate network/server.";
            }

            if ($k['neverSeen'] > 0) {
                $msg[] = "{$k['neverSeen']} devices have never reported (onboarding/SIM/APN/power).";
            }

            if ($k['offline'] > $k['online']) {
                $msg[] = "Offline exceeds online—prioritize troubleshooting offline units.";
            }

            return implode(' ', $msg);
        });
    }

    public function getChartPayloadProperty(): array
    {
        return [
            'pie' => $this->pie,
            'trend' => [
                'labels' => array_map(fn($x) => $x['day'], $this->trend),
                'values' => array_map(fn($x) => $x['count'], $this->trend),
            ],
        ];
    }



    /**
     *  Export CSV (works everywhere, no extra packages)
     * Exports current filtered table view.
     */
    public function exportCsv()
    {
        $cutoff = $this->cutoff();
        $from = $this->rangeFrom();
        $to = $this->rangeTo();

        // Use the same filtering logic as devices, but no pagination
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
                    $qq->where('status', 'active');
                } else {
                    $qq->where(function ($w) {
                        $w->whereNull('status')->orWhere('status', '!=', 'active');
                    });
                }
            })
            ->when($this->filterType !== 'all', fn($qq) => $qq->where('type', $this->filterType))
            ->when($this->filterConnectivity !== 'all', function ($qq) use ($cutoff) {
                if ($this->filterConnectivity === 'online') {
                    $qq->whereNotNull('last_seen_at')->where('last_seen_at', '>=', $cutoff);
                } elseif ($this->filterConnectivity === 'offline') {
                    $qq->whereNotNull('last_seen_at')->where('last_seen_at', '<', $cutoff);
                } elseif ($this->filterConnectivity === 'never') {
                    $qq->whereNull('last_seen_at');
                }
            })
            ->where(function ($qq) use ($from, $to) {
                $qq->whereNull('last_seen_at')->orWhereBetween('last_seen_at', [$from, $to]);
            })
            ->orderByRaw("NVL(last_seen_at, TO_DATE('1900-01-01','YYYY-MM-DD')) DESC");

        $rows = $q->get([
            'imei','model','type','reg_number','mobile_number','status','connected_at','last_seen_at','type_id'
        ]);

        $filename = "gps_dashboard_export_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['IMEI','MODEL','TYPE','REG_NUMBER','MOBILE','STATUS','CONNECTED_AT','LAST_SEEN_AT','TYPE_ID']);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->imei,
                    $r->model,
                    $r->type,
                    $r->reg_number,
                    $r->mobile_number,
                    $r->status,
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

            // new
            'alerts' => $this->alerts,
            'uptime' => $this->uptime,
            'rootCauses' => $this->rootCauses,
            'alertCutoff' => $this->alertCutoff(),
        ]);
    }
}
