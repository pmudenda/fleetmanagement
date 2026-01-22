<div class="gps-device-list">
    <input
            id="deviceFilterInput"
            type="text"
            class="form-control mb-2"
            placeholder="Search REG..."
            autocomplete="off"
    />

    <div class="device-list-scroll">
        <ul id="deviceList" class="device-list list-unstyled mb-0">
            @foreach($devices as $device)
                <livewire:vehicle-management.tracking.gps-device-list-item
                        :gps="$device"
                        :key="$device->id ?? $device->imei"
                />
            @endforeach
        </ul>
    </div>
</div>

@assets
<style>
    .gps-device-list .device-list-scroll {
        max-height: 70vh;
        overflow-y: auto;
    }

    .gps-device-list .device-item {
        cursor: pointer;
        border-radius: 8px;
        padding: .45rem .75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background .12s ease, opacity .12s ease;
        border: 1px solid rgba(0, 0, 0, .06);
        margin-bottom: .35rem;
        background: #fff;
    }

    .gps-device-list .device-item:hover {
        background: rgba(0, 0, 0, .035);
    }

    .gps-device-list .device-item:not(.active) {
        opacity: .72;
    }

    .gps-device-list .device-item.active {
        opacity: 1;
        background: rgba(13, 110, 253, .08);
        border-color: rgba(13, 110, 253, .25);
        box-shadow: 0 0 0 1px rgba(13, 110, 253, .12);
    }

    .gps-device-list .device-label {
        font-weight: 600;
        font-size: 13px;
        line-height: 1;
        color: rgba(0, 0, 0, .82);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
    }

    .gps-device-list .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        flex: 0 0 auto;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, .03);
    }

    .gps-device-list .status-dot.red {
        background: #dc3545;
    }

    .gps-device-list .status-dot.amber {
        background: #ffc107;
    }

    .gps-device-list .status-dot.green {
        background: #28a745;
    }

    .gps-device-list .status-dot.gray {
        background: #adb5bd;
    }
</style>
@endassets


@script
<script>
    // selection highlight driven by your existing 'device-selected' event
    $wire.on('device-selected', function (e) {
        console.log(e);

        const imei = e.gps;
        if (!imei) return;
        document.querySelectorAll('#deviceList .device-item').forEach(el => {
            el.classList.toggle('active', el.dataset.imei === imei);
        });

        document.querySelector('#deviceList .device-item.active')
            ?.scrollIntoView({block: 'nearest'});
    });

    // minimal filter by reg number
    const input = document.getElementById('deviceFilterInput');
    if (input) {
        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('#deviceList .device-item').forEach(el => {
                const reg = (el.dataset.reg || '').toLowerCase();
                el.style.display = reg.includes(q) ? '' : 'none';
            });
        });
    }
</script>
@endscript
