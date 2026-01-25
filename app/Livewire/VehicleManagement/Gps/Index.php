<?php

namespace App\Livewire\VehicleManagement\Gps;

use App\Models\Gps\Gps;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 20;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Gps::query()->whereKey($id)->delete();
        session()->flash('message', 'GPS device deleted.');
    }

    public function render()
    {
        $query = Gps::query()
            ->when($this->search !== '', function ($q) {
                $s = trim($this->search);
                $q->where(function ($qq) use ($s) {
                    $qq->where('imei', 'like', "%{$s}%")
                        ->orWhere('serial', 'like', "%{$s}%")
                        ->orWhere('reg_number', 'like', "%{$s}%")
                        ->orWhere('model', 'like', "%{$s}%")
                        ->orWhere('mobile_number', 'like', "%{$s}%");
                });
            })
            ->latest();

        return view('livewire.vehicle-management.gps.index', [
            'devices' => $query->paginate($this->perPage),
            'total' => Gps::count(),
            'connected' => Gps::whereNotNull('connected_at')->count(),
            'not_connected' => Gps::whereNull('connected_at')->count(),
        ]);
    }
}
