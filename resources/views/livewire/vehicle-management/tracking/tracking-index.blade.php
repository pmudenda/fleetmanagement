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
                    <div class="card-header">

                    </div>

                    <div class="card-body p-0">
                        <table class="table table-condensed table-bordered table-sm" style="font-size: 12px">
                            @foreach($gpses as $gps)
                                <tr class="m-0 p-0 @if($gps['id'] == ($selectedGps->id ?? 0)) table-active @endif">
                                    <td class="m-0 p-2" >
                                        <a href="javascript:void(0)"  wire:click="select({{$gps['id']}})">{{$gps['reg']}}</a>
                                    </td>
                                    <td class="text-right">
                                        @if($gps['speed'] > 40)
                                            <span class="badge badge-light-danger badge-sm">{{$gps['speed']}}Km/h</span>
                                        @endif
                                            @if($gps['speed'] <= 0)
                                                <span class="badge badge-light-warning badge-sm">stationary</span>
                                            @endif
                                        <span class="badge badge-light-{{$gps['is_connected'] ? 'success' : 'danger'}} badge-sm">{{$gps['connected_at'] ?? 'offline'}}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

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

        for (const location of $wire.gpses) {
            // const priceTag = document.createElement("div");
            //
            // priceTag.className = "vehicle-marker";
            // priceTag.textContent = location.reg;
            //
            // const marker = new AdvancedMarkerElement({
            //     position: {lat: location.lat, lng: location.lng},
            //     map: map,
            //     content: buildContent(location),
            //     title: location.reg,
            //
            // });

            await addOrUpdateMarker(location)

            // marker.addListener("click", () => {
            //     toggleHighlight(marker, location);
            // });
        }
    }

    initMap();

    // document.addEventListener('DOMContentLoaded', initMap);

    function toggleHighlight(markerView, location) {
        if (markerView.content.classList.contains("highlight")) {
            markerView.content.classList.remove("highlight");
            markerView.zIndex = null;
        } else {
            markerView.content.classList.add("highlight");
            markerView.zIndex = 1;
        }
    }

    function buildContent(location) {
        const content = document.createElement("div");

        content.classList.add("property");
        content.innerHTML = `
<!--<div class="icon">-->
        <span class=" fa-house">${location.reg}</span>
<!--    </div>-->

    <div class="details">
        <div class="address"><strong>IMEI: </strong>${location.imei}</div>
        <div class="address"><strong>Type: </strong>${location.brand}</div>
        <div class="address"><strong>Fuel: </strong>${location.fuel_type}</div>
        <div class="address"><strong>Unit: </strong>${location.business_unit}</div>
        <div class="address"><strong>Status: </strong>${location.status}</div>
        <hr/>
        <div class="features">
        <div>
            <i aria-hidden="true" class="fa fa-tachometer-alt fa-lg bed" title="bedroom"></i>
            <span>${location.speed} Km/h</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-clock fa-lg bed" title="bathroom"></i>
            <span>${location.connected_at}</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-map-marked fa-lg bed" title="size"></i>
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
            var isHighlighted = markers[location.imei].content.classList.contains("highlight")
            // Update existing marker position
            markers[location.imei].position = {lat: location.lat, lng: location.lng};
            markers[location.imei].content = buildContent(location);
            if (isHighlighted) {
                toggleHighlight(markers[location.imei], location);
            }
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

    function updateMarkers() {
        $wire.call('refresh').then(async function () {
            for (const location of $wire.gpses) {
                await addOrUpdateMarker(location)
            }
        });
    }

    // Update markers every 10 seconds
    setInterval(updateMarkers, 10000);

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
