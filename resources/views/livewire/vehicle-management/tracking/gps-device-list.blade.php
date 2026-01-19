<div class="row">
    <div class="col-lg-12">
        @foreach($gpses as $gps)
            <livewire:vehicle-management.tracking.gps-device-list-item :$gps/>
        @endforeach
    </div>
</div>
