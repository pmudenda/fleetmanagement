<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Tracking'"
                      :activeCrumb="'Tracking'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3">
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-sm" placeholder="REG No / Imei"
                           wire:model.live="search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card">
{{--                    <div class="card-header">--}}

{{--                    </div>--}}

                    <div class="card-body p-0">
                        <table class="table table-condensed table-borderless table-sm" style="font-size: 12px">
                            @foreach($vehicles as $gps)
                                <tr class="m-0 p-0 @if($gps['id'] == ($selectedGps->id ?? 0)) table-active @endif">
                                    <td class="m-0 p-2">
                                        <a href="javascript:void(0)"
                                           wire:click="select({{$gps['id']}})">{{$gps['reg_number']}}</a>
                                    </td>
                                    <td class="text-right">
                                        @if(isset($gps['speed']))
                                            @if($gps['speed']  > 40)
                                                <span class="badge badge-light-danger badge-sm">{{$gps['speed']}}
                                                    Km/h</span>
                                            @endif
                                            @if($gps['speed'] <= 0)
                                                <span class="badge badge-light-warning badge-sm">idle</span>
                                            @endif
                                        @endif
                                        <span class="badge badge-light-{{isset($gps['connected_at'])  ? 'success' : 'danger'}} badge-sm">@if($gps['connected_at']) {{$gps['connected_at']->diffForHumans(null,true,true)}} @else offline @endif</span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                    </div>

                    <div class="card-footer">
                        {{$vehicles->links()}}
                    </div>

                    <div wire:target="search" wire:loading.class="overlay">
                        <i class="fas fa-2x fa-sync-alt fa-spin d-none" wire:target="search"
                           wire:loading.class.remove="d-none"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4">

                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fa fa-car fa-2x"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Online Vehicles</span>
                                <span class="info-box-number">{{number_format($connected,0)}} </span>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fa fa-car fa-2x"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Offline Vehicles</span>
                                <span class="info-box-number">{{number_format($not_connected)}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fa fa-car fa-2x"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Vehicles With Devices</span>
                                <span class="info-box-number">{{number_format($total)}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>
                <div class="embed-responsive embed-responsive-16by9" wire:ignore>
                    <div id="map" class="embed-responsive-item"></div>
                </div>
            </div>
        </div>
    </div>


</section>

@script
<script type="text/javascript">

    let map;
    let markers = {};
    let details = [];


    window.Echo.channel('gps.location')
        .listen('CurrentLocationEvent', (e) => {
            var gps = e.location;

            if (details[gps.imei] == null) {
               $wire.getLocation(gps.imei).then(function (location) {
                   details[gps.imei] = location;
                });
            }
            details[gps.imei] = {...details[gps.imei],...gps};

            console.log(details[gps.imei]);

            addOrUpdateMarker(details[gps.imei]);
            if(details[gps.imei].reg != null){
                if(gps.speed > 40){
                    toastr.error(`${details[gps.imei].reg} - ${gps.speed}km/h`,'Speed Alert',{
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": true,
                        preventOpenDuplicates: true,
                        "onclick": null,
                        "showDuration": "300000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    })
                }
            }
        });

    // $wire.on('location-update', (event) => {
    //     console.log(event.location);
    //     addOrUpdateMarker(event.location);
    // });

    async function initMap() {
        // The location of Uluru
        const position = {lat: -15.408955, lng: 28.2847}
        // Request needed libraries.
        //@ts-ignore
        const {Map} = await google.maps.importLibrary("maps");
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        // The map, centered at Uluru
        map = new Map(document.getElementById("map"), {
            zoom: 12,
            center: position,
            mapId: "ALL_DEVICE_MAP",
            mapTypeId: 'hybrid'

        });

        // for (const location of $wire.gpses) {
        //     await addOrUpdateMarker(location)
        // }
    }

    initMap();

    // document.addEventListener('DOMContentLoaded', initMap);

    function toggleHighlight(markerView, location) {
        // Get current speed class
        const speedClass = getSpeedClass(location.speed || 0);

        if (markerView.content.classList.contains("highlight")) {
            markerView.content.classList.remove("highlight");

            // Re-add speed class when unhighlighting
            markerView.content.classList.add(speedClass);

            markerView.zIndex = null;
        } else {
            // When highlighting, remove speed color (it will use the highlight styling)
            markerView.content.classList.remove('speed-idle', 'speed-moving', 'speed-fast');
            markerView.content.classList.add("highlight");
            markerView.zIndex = 1;
        }
    }

    function getSpeedClass(speed) {
        if (speed > 40) {
            return 'speed-fast'; // Red for high speed
        } else if (speed > 0) {
            return 'speed-moving'; // Green for moving
        } else {
            return 'speed-idle'; // Yellow for idle
        }
    }

    function buildContent(location) {
        const content = document.createElement("div");

        // Get speed class
        let speedClass = getSpeedClass(location.speed || 0);
        speedClass = location.is_compliant ? 'speed-fast' : speedClass;

        content.classList.add("property");
        content.classList.add(speedClass); // Add speed-based class

        content.innerHTML = `
        <span class="fa-house">${location.reg ?? '<div class="d-flex align-items-center"><strong>Please wait...</strong><div class="spinner-border spinner-border-sm ms-auto" role="status" aria-hidden="true"></div></div>'}</span>        <div class="details">
            <div class="address"><strong>IMEI: </strong>${location.imei}</div>
            <div class="address"><strong>Type: </strong>${location.brand}</div>
            <div class="address"><strong>Fuel: </strong>${location.fuel_type}</div>
            <div class="address"><strong>Unit: </strong>${location.business_unit}</div>
            <div class="address"><strong>Status: </strong>${location.status}</div>
            <div class="address"><strong>Road Tax: </strong>${location.road_tax}</div>
            <div class="address"><strong>Fitness: </strong>${location.fitness}</div>
            <div class="address"><strong>Compliant: </strong>${location.rtsa_status}</div>
            <hr/>
            <div class="features">
                <div>
                    <i aria-hidden="true" class="fa fa-tachometer-alt fa-lg" title="Speed"></i>
                    <span>${location.speed || 0} Km/h</span>
                </div>
                <div>
                    <i aria-hidden="true" class="fa fa-clock fa-lg" title="Last Update"></i>
                    <span>${location.connected_at}</span>
                </div>
                <div>
                    <i aria-hidden="true" class="fa fa-map-marked fa-lg" title="Location"></i>
                    <span>${location.lat},${location.lng}</span>
                </div>
            </div>
        </div>
    `;
        return content;
    }

    async function addOrUpdateMarker(location) {
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        if (markers[location.imei]) {
            var isHighlighted = markers[location.imei].content.classList.contains("highlight");

            // Get current speed class
            const speedClass = getSpeedClass(location.speed || 0);

            // Remove existing speed classes
            markers[location.imei].content.classList.remove('speed-idle', 'speed-moving', 'speed-fast');

            // Add new speed class
            markers[location.imei].content.classList.add(speedClass);

            // Update marker content with new speed info
            markers[location.imei].content = buildContent(location);

            // If it was highlighted, re-apply highlight
            if (isHighlighted) {
                markers[location.imei].content.classList.add("highlight");
            }

            // Animate movement
            animatedMove(markers[location.imei], .5, markers[location.imei].position, {
                lat: location.lat,
                lng: location.lng
            });

        } else {
            // Create a new marker
            const marker = new AdvancedMarkerElement({
                position: {lat: location.lat, lng: location.lng},
                map: map,
                content: buildContent(location),
                title: location.reg,
            });

            marker.addListener("click", () => {
                toggleHighlight(marker, location);
            });

            markers[location.imei] = marker;
        }
    }

    function animatedMove(marker, t, current, moveto) {
        var lat = current.lat;
        var lng = current.lng;

        var deltalat = (moveto.lat - current.lat) / 100;
        var deltalng = (moveto.lng - current.lng) / 100;

        var delay = 10 * t;
        for (var i = 0; i < 100; i++) {
            (function (ind) {
                // console.log(ind);
                setTimeout(
                    function () {
                        var lat = marker.position.lat;
                        var lng = marker.position.lng;
                        lat += deltalat;
                        lng += deltalng;
                        latlng = new google.maps.LatLng(lat, lng);
                        marker.position = {lat: lat, lng: lng};
                    }, delay * ind
                );
            })(i)
        }
    }

    function updateMarkers() {
        $wire.call('refresh').then(async function () {
            for (const location of $wire.gpses) {
                await addOrUpdateMarker(location)
            }
        });
    }

    // Update markers every 10 seconds
    // setInterval(updateMarkers, 10000);

    $wire.on('gps-selected', (event) => {

        var gps = event.gps;
        const paths = event.paths;
        const marker = markers[gps.imei];

        map.setZoom(17);
        map.setCenter(marker.position);
        map.panTo(marker.position);

        console.log(paths);
        const carPath = new google.maps.Polyline({
            path: paths,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2,
        });

        carPath.setMap(map);


        // map.addListener("center_changed", () => {
        //     // 3 seconds after the center of the map has changed, pan back to the
        //     // marker.
        //     window.setTimeout(() => {
        //         map.panTo(marker.position);
        //     }, 3000);
        // });

    });
</script>
@endscript
