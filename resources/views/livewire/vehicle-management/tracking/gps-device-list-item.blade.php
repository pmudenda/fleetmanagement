<li
        class="device-item"
        data-imei="{{ $gps->imei }}"
        data-reg="{{ $gps->reg_number }}"
        wire:click="$dispatch('device-selected',{gps: '{{$gps->imei}}',location: {{json_encode($location,true)}}})"
        title="{{ $signalText }}" wire:ignore.self
>
    <span class="device-label">
        {{ $gps->reg_number }}
    </span>

    <span class="status-dot {{ $severity }}"></span>
</li>
