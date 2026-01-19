<div class="card collapsed-card mb-2">
    <div class="card-header d-flex align-items-center">
        <div class="card-title flex-grow-1 mb-0">
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold">{{ $gps->reg_number }}</span>
                <span class="fs-6 text-muted">{{ $gps->imei }}</span>
            </div>
        </div>

        <div class="card-tools d-flex align-items-center">
            @if($gps->last_seen_at)
                <span class="badge badge-success mr-2">{{$gps->last_seen_at->diffForHumas()}}</span>
                @else
                <span class="badge badge-danger mr-2">offline</span>
            @endif
            <span class="badge badge-primary mr-2">Label</span>

            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body" style="display: none;">
        The body of the card
    </div>
    <!-- /.card-body -->
</div>
