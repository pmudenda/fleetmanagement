<li
        class="device-item"
        data-imei="{{ $gps->imei }}"
        data-reg="{{ $gps->reg_number }}"
        wire:click="$dispatch('device-selected',{gps: '{{$gps->imei}}',location: {{json_encode($location,true)}}})"
        title="{{ $location['signals']['primary'] ?? '' }}" wire:ignore.self
>
    <span class="device-label">
        {{ $gps->reg_number }}
    </span>

    <div>
        @foreach($icons as $icon)
            <i class="mr-1 {{ $icon['icon'] }} {{ $icon['class'] ?? 'text-muted' }}"
               title="{{ $icon['title'] }}"></i>

        @endforeach
    </div>

{{--    <span class="status-dot {{ $severity }}"></span>--}}
</li>
