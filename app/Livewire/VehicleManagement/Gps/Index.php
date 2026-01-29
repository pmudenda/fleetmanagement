<?php

namespace App\Livewire\VehicleManagement\Gps;

use App\Models\Gps\Gps;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public int $perPage = 20;

    // Filters
    #[Url] public string $filterStatus = 'all';        // all|active|inactive
    #[Url] public string $filterConnectivity = 'all';  // all|connected|not_connected|never
    #[Url] public string $filterType = 'all';          // all|<type>

    // Sorting
    #[Url] public string $sortField = 'last_seen_at';
    #[Url] public string $sortDirection = 'desc';      // asc|desc

    public function updating($name, $value): void
    {
        // Reset pagination whenever user changes filters/search/perPage
        if (in_array($name, ['search','perPage','filterStatus','filterConnectivity','filterType'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->filterConnectivity = 'all';
        $this->filterType = 'all';
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function sortIcon(string $field): string
    {
        if ($this->sortField !== $field) {
            return '<span class="text-muted ms-1"><i class="fas fa-sort"></i></span>';
        }

        return $this->sortDirection === 'asc'
            ? '<span class="ms-1"><i class="fas fa-sort-up"></i></span>'
            : '<span class="ms-1"><i class="fas fa-sort-down"></i></span>';
    }

    private function baseQuery()
    {
        $q = Gps::query();

        // Search
        if (trim($this->search) !== '') {
            $s = trim($this->search);
            $q->where(function ($w) use ($s) {
                $w->where('imei', 'like', "%{$s}%")
                    ->orWhere('serial', 'like', "%{$s}%")
                    ->orWhere('reg_number', 'like', "%{$s}%")
                    ->orWhere('model', 'like', "%{$s}%")
                    ->orWhere('type', 'like', "%{$s}%")
                    ->orWhere('mobile_number', 'like', "%{$s}%");
            });
        }

        // Status filter (your UI shows active when status === 1)
        if ($this->filterStatus === 'active') {
            $q->where('status', 1);
        } elseif ($this->filterStatus === 'inactive') {
            $q->where('status', '!=', 1);
        }

        // Connectivity filter (based on connected_at / last_seen_at)
        if ($this->filterConnectivity === 'connected') {
            $q->whereNotNull('connected_at');
        } elseif ($this->filterConnectivity === 'not_connected') {
            $q->whereNull('connected_at');
        } elseif ($this->filterConnectivity === 'never') {
            $q->whereNull('last_seen_at');
        }

        // Type filter
        if ($this->filterType !== 'all') {
            $q->where('type', $this->filterType);
        }

        // Safe sort allowlist
        $allowedSorts = [
            'model','type','imei','serial','reg_number','mobile_number',
            'connected_at','last_seen_at','odometer','status'
        ];
        if (!in_array($this->sortField, $allowedSorts, true)) {
            $this->sortField = 'last_seen_at';
        }

        $q->orderBy($this->sortField, $this->sortDirection);

        return $q;
    }

    // IMPORTANT: your GPS model primary key is IMEI (string)
    public function delete(string $imei): void
    {
        Gps::query()->where('imei', $imei)->delete();
        session()->flash('message', 'GPS device deleted.');
        $this->resetPage();
    }

    public function exportCsv()
    {
        $filename = 'gps_devices_' . now()->format('Ymd_His') . '.csv';
        $query = $this->baseQuery()->clone();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return Response::stream(function () use ($query) {
            $out = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($out, [
                'Model','Type','IMEI','Serial','REG Number','Mobile',
                'Connected At','Last Seen','Odometer','Status'
            ]);

            $query->chunk(1000, function ($rows) use ($out) {
                foreach ($rows as $d) {
                    fputcsv($out, [
                        $d->model,
                        $d->type,
                        $d->imei,
                        $d->serial,
                        $d->reg_number,
                        $d->mobile_number,
                        optional($d->connected_at)?->toDateTimeString(),
                        optional($d->last_seen_at)?->toDateTimeString(),
                        $d->odometer,
                        ((int)$d->status === 1) ? 'Active' : 'Inactive',
                    ]);
                }
            });

            fclose($out);
        }, 200, $headers);
    }

    public function copyVisible(): void
    {
        $rows = $this->baseQuery()
            ->forPage($this->getPage(), $this->perPage)
            ->limit($this->perPage)
            ->get();

        $lines = [];
        $lines[] = implode("\t", [
            'Model','Type','IMEI','Serial','REG Number','Mobile',
            'Connected At','Last Seen','Odometer','Status'
        ]);

        foreach ($rows as $d) {
            $lines[] = implode("\t", [
                $d->model ?? '--',
                $d->type ?? '--',
                $d->imei,
                $d->serial ?? '--',
                $d->reg_number ?? '--',
                $d->mobile_number ?? '--',
                $d->connected_at?->toDateTimeString() ?? '--',
                $d->last_seen_at?->toDateTimeString() ?? '--',
                $d->odometer !== null ? (string)((int)$d->odometer) : '--',
                ((int)$d->status === 1) ? 'Active' : 'Inactive',
            ]);
        }

        $this->dispatch('gps-copy', text: implode("\n", $lines));
    }

    public function render()
    {
        // Header totals (overall)
        $total = Gps::count();
        $connected = Gps::whereNotNull('connected_at')->count();
        $not_connected = Gps::whereNull('connected_at')->count();

        // Filtered total
        $filteredTotal = (clone $this->baseQuery())->count();

        // Type dropdown options (distinct types)
        $typeOptions = Gps::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->toArray();

        $devices = $this->baseQuery()->paginate($this->perPage);

        return view('livewire.vehicle-management.gps.index', [
            'devices' => $devices,

            'total' => $total,
            'connected' => $connected,
            'not_connected' => $not_connected,

            'filteredTotal' => $filteredTotal,
            'typeOptions' => $typeOptions,

            'sortIcon' => fn ($f) => $this->sortIcon($f),
        ]);
    }
}
