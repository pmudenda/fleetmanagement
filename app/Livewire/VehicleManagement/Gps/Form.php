<?php

namespace App\Livewire\VehicleManagement\Gps;

use App\Models\Gps\Gps;
use App\Models\VehicleManagement\VehicleHeader;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Gps $device = null;

    public array $deviceTypeOptions = [
        'Teltonika',
        'Concox',
        'Queclink',
        'CalAmp',
        'Ruptela',
        'SinoTrack',
        'Meitrack',
        'Trackimo',
        'Other',
    ];

    public bool $regIsValid = false;
    public bool $regNotFound = false;

    public ?string $model = null;
    public ?string $type = null;
    public string $imei = '';
    public ?string $serial = null;

    public ?string $reg_number = null;

    public ?string $mobile_number = null;
    public $connected_at = null;
    public $last_seen_at = null;
    public string $status = '1';

    public int $type_id = 1;


    public ?int $odometer = null;

    public string $regSearch = '';
    public array $regSuggestions = [];
    public bool $showRegDropdown = false;

    public function mount(?string $imei = null): void
    {
        $this->type_id = 1;

        if ($imei) {
            $this->device = Gps::where('imei', $imei)->firstOrFail();

            $this->model         = $this->device->model;
            $this->type          = $this->device->type;
            $this->imei          = $this->device->imei;
            $this->serial        = $this->device->serial;
            $this->reg_number    = $this->device->reg_number;
            $this->mobile_number = $this->device->mobile_number;
            $this->connected_at  = $this->device->connected_at?->format('Y-m-d\TH:i');
            $this->last_seen_at  = $this->device->last_seen_at?->format('Y-m-d\TH:i');
            $this->status        = $this->device->status;


            $this->odometer      = $this->device->odometer !== null ? (int) $this->device->odometer : null;

            $this->regSearch = (string) $this->reg_number;
            $this->regIsValid = !empty($this->reg_number);

            if ($this->type && !in_array($this->type, $this->deviceTypeOptions, true)) {
                $this->deviceTypeOptions[] = $this->type;
                $this->deviceTypeOptions = array_values(array_unique($this->deviceTypeOptions));
            }
        } else {
            $this->connected_at = Carbon::now()->format('Y-m-d\TH:i');
        }
    }

    protected function rules(): array
    {
        $gpsTable = (new Gps())->getTable();
        $ignoreId = $this->device?->id;

        return [
            'model' => ['nullable', 'string', Rule::in(['FMB120', 'FMB920'])],

            'type' => [
                'required',
                'string',
                Rule::in($this->deviceTypeOptions),
            ],

            'imei' => [
                'required',
                'digits:15',
                Rule::unique($gpsTable, 'imei')->ignore($ignoreId),
            ],

            'serial' => [
                'nullable',
                'digits_between:9,10',
                Rule::unique($gpsTable, 'serial')->ignore($ignoreId),
            ],

            'reg_number' => [
                'required',
                'string',
                Rule::exists((new VehicleHeader())->getTable(), 'registration_number')
                    ->where('status', '01'),
                Rule::unique($gpsTable, 'reg_number')->ignore($ignoreId),
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    if ($value === null || $value === '') return;

                    $digits = preg_replace('/\D+/', '', (string) $value);
                    $len = strlen($digits);

                    if ($len < 9 || $len > 13) {
                        $fail('Mobile number must contain 9 to 13 digits.');
                    }
                }
            ],


            'odometer' => ['required', 'integer', 'min:0'],

            'connected_at' => ['nullable', 'date'],
            'last_seen_at' => ['nullable', 'date'],
            // 'status' => ['required', Rule::in(['inactive','active','offline','online'])],
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required' => 'Please select a device type.',
            'type.in' => 'Selected device type is invalid.',

            'imei.digits' => 'IMEI must be exactly 15 digits.',
            'imei.unique' => 'This IMEI already exists.',
            'serial.digits_between' => 'Serial must be 9 or 10 digits.',
            'serial.unique' => 'This serial already exists.',

            'reg_number.required' => 'Please select a registration number from the dropdown.',
            'reg_number.exists' => 'Selected registration number is invalid or not active (status 01).',
            'reg_number.unique' => 'This registration number is already linked to another GPS device.',


            'odometer.required' => 'Odometer is required.',
            'odometer.integer'  => 'Odometer must be a number.',
            'odometer.min'      => 'Odometer cannot be negative.',
        ];
    }

    public function updatedRegSearch($value): void
    {
        $value = trim((string) $value);

        $this->regIsValid = false;
        $this->regNotFound = false;

        $this->reg_number = null;

        if (strlen($value) < 2) {
            $this->regSuggestions = [];
            $this->showRegDropdown = false;
            return;
        }

        $rows = VehicleHeader::query()
            ->where('status', '01')
            ->where('registration_number', 'like', "%{$value}%")
            ->orderBy('registration_number')
            ->limit(20)
            ->pluck('registration_number')
            ->toArray();

        $this->regSuggestions = $rows;
        $this->showRegDropdown = count($rows) > 0;

        if (!$this->showRegDropdown) {
            $this->regNotFound = true;
        }
    }

    public function selectReg(string $reg): void
    {
        $reg = trim($reg);

        $this->reg_number = $reg;
        $this->regSearch = $reg;

        $this->regSuggestions = [];
        $this->showRegDropdown = false;

        $this->regIsValid = true;
        $this->regNotFound = false;
    }

    public function updatedImei($value): void
    {
        if ($this->device) return;
        $this->imei = preg_replace('/\D+/', '', (string) $value);
    }

    public function updatedSerial($value): void
    {
        $this->serial = preg_replace('/\D+/', '', (string) $value);
    }


    public function updatedOdometer($value): void
    {
        $digits = preg_replace('/\D+/', '', (string) $value);
        $this->odometer = ($digits === '') ? null : (int) $digits;
    }

    public function save()
    {
        if (!$this->regIsValid || empty($this->reg_number)) {
            $this->addError('reg_number', 'Please select a registration number from the dropdown.');
            return;
        }

        $regAlreadyAssigned = Gps::query()
            ->where('reg_number', $this->reg_number)
            ->when($this->device, fn ($q) => $q->where('id', '!=', $this->device->id))
            ->exists();

        if ($regAlreadyAssigned) {
            $this->addError('reg_number', 'This registration number is already linked to another GPS device.');
            return;
        }

        $data = $this->validate();

        $payload = [
            'model'         => $data['model'],
            'type'          => $data['type'],
            'imei'          => $data['imei'],
            'serial'        => $data['serial'],
            'reg_number'    => $data['reg_number'],
            'mobile_number' => $data['mobile_number'],


            'odometer'      => (int) $data['odometer'],

            'status'        => 1,
            'type_id'       => 1,

            'connected_at'  => $this->connected_at
                ? Carbon::parse($this->connected_at)->format('Y-m-d H:i:s')
                : null,

            'last_seen_at'  => $data['last_seen_at']
                ? Carbon::parse($data['last_seen_at'])->format('Y-m-d H:i:s')
                : null,
        ];

        if ($this->device) {
            if ($payload['imei'] !== $this->device->imei) {
                $this->addError('imei', 'IMEI cannot be changed.');
                return;
            }

            $this->device->update($payload);
        } else {
            Gps::create($payload);
        }

        session()->flash('message', 'GPS device saved successfully.');
        return redirect()->route('gps.index');
    }

    public function render()
    {
        return view('livewire.vehicle-management.gps.form', [
            'isEdit' => (bool) $this->device,
            'deviceTypeOptions' => $this->deviceTypeOptions,
        ]);
    }
}
