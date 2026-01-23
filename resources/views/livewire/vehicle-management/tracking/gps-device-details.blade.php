<div class="p-3 position-relative min-vh-100">
    @if($gps)
        <div class="offcanvas-header mb-3">
            <div class="d-flex align-items-start justify-content-between">
                <div class="me-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold fs-5">{{$gps->reg_number}}</span>
                    </div>

                    <div class="text-primary small mt-1">
                        <i class="fas fa-microchip me-1"></i>IMEI: <span class="fw-semibold">{{$gps->imei}}</span>
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock me-1"></i>Last: <span class="fw-semibold">{{$location['tracked_at'] ?? '--'}}</span>
                    </div>
                </div>

            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mt-0 pt-0">


            <!-- Vehicle Info -->
            <div class="mt-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold">
                        <i class="fas fa-car-side me-1"></i>Vehicle
                    </div>

                    <span class="badge {{ $stateMeta['class'] }}">
                        <i class="fas {{ $stateMeta['icon'] }} me-1"></i>
                        {{ $gps->vehicle->state->name }}
                    </span>

                </div>

                <div class="border rounded-3 p-2">
                    <div class="d-flex justify-content-between small py-1">
                        <span class="text-muted"><i class="fas fa-tag me-1"></i>Model</span>
                        <span class="fw-semibold">{{$gps->vehicle->model_name}}</span>
                    </div>

                    <div class="d-flex justify-content-between small py-1">
                        <span class="text-muted"><i class="fas fa-industry me-1"></i>Make</span>
                        <span class="fw-semibold">{{$gps->vehicle->brand_name}}</span>
                    </div>

                    <div class="d-flex justify-content-between small py-1">
                        <span class="text-muted"><i class="fas fa-cogs me-1"></i>Engine</span>
                        <span class="fw-semibold">{{$gps->vehicle->engine->engine_type}}
                            • {{$gps->vehicle->engine->engine_capacity}}cc</span>
                    </div>


                    <div class="d-flex justify-content-between small py-1">
                        <span class="text-muted"><i class="fas fa-sim-card me-1"></i>Device Mobile #</span>
                        <span class="fw-semibold">{{$gps->mobile_number}}</span>
                    </div>
                </div>
            </div>


            <!-- Quick Stats -->
            <div class="fw-semibold mb-2">
                <i class="fas fa-map-marker-alt me-1"></i>Location
            </div>
            @if($location)



                <div class="row g-2">
                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="text-muted small">
                                <i class="fas fa-tachometer-alt me-1"></i>Speed
                            </div>
                            <div class="fw-semibold fs-5"> {{$location['speed']}} <span class="text-muted fs-6">km/h</span></div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="text-muted small">
                                <i class="fas fa-location-arrow me-1"></i>Heading
                            </div>
                            <div class="fw-semibold fs-5"> {{$location['angle']}}<span class="text-muted fs-6">°</span></div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="text-muted small">
                                <i class="fas fa-route me-1"></i>Odometer
                            </div>
                            <div class="fw-semibold fs-6"> {{number_format($location['odometer'],2)}} <span class="text-muted">km</span></div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-2">
                            <div class="text-muted small">
                                <i class="fas fa-gas-pump me-1"></i>Fuel
                            </div>
                            <div class="fw-semibold fs-6"> {{number_format($location['fuel'],2)}} <span class="text-muted">L</span></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">

                    <div class="border rounded-3 p-2">
                        {{--                    <div class="small fw-semibold">--}}
                        {{--                        Great East Rd, near EastPark Mall, Lusaka--}}
                        {{--                    </div>--}}

                        <div class="text-muted small mt-1">
                            <i class="fas fa-globe-africa me-1"></i>
                            {{$location['latitude']}}, {{$location['longitude']}}
                            <span class="mx-2">•</span>
                            <i class="fas fa-mountain me-1"></i>
                            {{$location['altitude']}}m
                        </div>

                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary btn-sm" type="button" wire:click="$dispatch('location-centered',{gps: '{{$gps->imei}}'})">
                                <i class="fas fa-crosshairs me-1"></i>Center
                            </button>
                            <button class="btn btn-outline-primary btn-sm" type="button">
                                <i class="fas fa-history me-1"></i>Playback
                            </button>
                            <button class="btn btn-outline-primary btn-sm" type="button">
                                <i class="fas fa-road me-1"></i>Trips
                            </button>
                            <button class="btn btn-outline-danger btn-sm" type="button">
                                <i class="fas fa-bell me-1"></i>Alert
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="callout callout-danger">
                    <p>No Location Data Available, Please ensure device is connected & transmitting</p>
                </div>
            @endif


            <!-- Compliance / Docs -->
            <div class="mt-3">
                <div class="fw-semibold mb-2">
                    <i class="fas fa-clipboard-check me-1"></i>Road Compliance
                </div>

                @if($gps->vehicle->roadTax)
                    <div class="border rounded-3 p-2">
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <div class="small">
                                <span class="text-muted"><i class="fas fa-file-invoice me-1"></i>RTSA</span>
                            </div>
                            @if($gps->vehicle->roadTax->is_compliant ?? false)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Compliant
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-check me-1"></i>Non-Compliant
                                </span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-1">
                            <div class="small">
                                <span class="text-muted"><i class="fas fa-calendar-alt me-1"></i>Road Tax Expiry</span>
                            </div>
                            <span class="fw-semibold small badge-light-{{$gps->vehicle->roadTax->valid_to->lessThan(now()) ? 'danger' : 'success'}}">{{$gps->vehicle->roadTax->valid_to->toFormattedDateString()}}</span>

                        </div>

                        <div class="d-flex justify-content-between align-items-center py-1">
                            <div class="small">
                                <span class="text-muted"><i class="fas fa-calendar-alt me-1"></i>Fitness Expiry</span>
                            </div>
                            <span class="fw-semibold small badge-light-{{$gps->vehicle->roadTax->fitness_expiry->lessThan(now()) ? 'danger' : 'success'}}">{{$gps->vehicle->roadTax->fitness_expiry->toFormattedDateString()}}</span>
                        </div>

                        <div class="small">
                            <span class="text-muted"><i class="fas fa-calendar-alt me-1"></i>Status</span>
                        </div>
                        <span class="fw-semibold small">{{$gps->vehicle->roadTax->status}}</span>
                    </div>
                @else
                    <div class="callout callout-danger">
                        <p>No RTSA Data Available, Please contact Administrator</p>
                    </div>
                @endif
            </div>

{{--            <!-- Footer Actions -->--}}
{{--            <div class="mt-3 d-grid gap-2">--}}
{{--                <button class="btn btn-primary" type="button">--}}
{{--                    <i class="fas fa-eye me-1"></i>View Full Vehicle Profile--}}
{{--                </button>--}}
{{--                <button class="btn btn-light" type="button">--}}
{{--                    <i class="fas fa-wrench me-1"></i>Manage Device--}}
{{--                </button>--}}
{{--            </div>--}}
        </div>
    @else
        <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 100%;">
            <div class="spinner-border mb-3" role="status"></div>
            <div class="text-muted fw-semibold">
                Loading information… please wait
            </div>
        </div>

    @endif


</div>

