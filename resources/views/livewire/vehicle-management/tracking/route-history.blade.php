<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="m-0">Route History — {{ $gps->imei }} @if($gps->vehicle?->reg_number)
                        ({{ $gps->vehicle->reg_number }})
                    @endif</h1>

                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ url()->previous() }}">Back</a>
                    <button class="btn btn-outline-danger btn-sm" type="button" onclick="RoutePlayback?.clear()">Clear
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-9" wire:ignore>
                            <div id="map" style="height: 70vh; border-radius: 8px; overflow: hidden;"></div>
                        </div>

                        <div class="col-lg-3">

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">From</label>
                                    <input type="datetime-local" class="form-control" wire:model.defer="from">
                                    @error('from') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">To</label>
                                    <input type="datetime-local" class="form-control" wire:model.defer="to">
                                    @error('to') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <x-button class="btn btn-primary w-100"
                                              wire:click="loadRoute"
                                              wire:target="loadRoute">
                                        Load Route
                                    </x-button>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mb-2">
                                <button class="btn btn-success w-100" type="button" wire:click="$js.play">
                                    Play
                                </button>
                                <button class="btn btn-warning w-100" type="button" onclick="RoutePlayback?.pause()">
                                    Pause
                                </button>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Speed</label>
                                <select class="form-select" onchange="RoutePlayback?.setSpeed(parseFloat(this.value))">
                                    <option value="1">1x</option>
                                    <option value="2">2x</option>
                                    <option value="5" selected>5x</option>
                                    <option value="10">10x</option>
                                    <option value="20">20x</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Progress</label>
                                <input id="playbackSlider" type="range" class="form-range" min="0" max="0" value="0"
                                       oninput="RoutePlayback?.seek(parseInt(this.value,10))">
                                <small class="text-muted d-block" id="playbackMeta">—</small>
                            </div>

                            <div class="small text-muted">
                                <div><strong>Points:</strong> <span id="pointsCount">0</span></div>
                                <div><strong>Status:</strong> <span id="playbackStatus">Idle</span></div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0 small">
                                Playback uses <strong>TRACKED_AT</strong> (device timestamp).
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>

@script
<script>
    let points;
    let marker;
    let overviewLine;
    let playedLine;
    initMap();

    async function initMap() {
        // Request needed libraries.
        //@ts-ignore
        await google.maps.importLibrary("maps");
        await google.maps.importLibrary("marker");

        // Zambia bounding box (optional; left commented out)
        const ZAMBIA_BOUNDS = {
            north: -8.2720,
            south: -18.0792,
            east: 33.7124,
            west: 21.9800,
        };

        // A good visual center (roughly central Zambia)
        const ZAMBIA_CENTER = {lat: -13.175, lng: 27.846};

        MapSingleton.init("map", {
            zoom: 8,
            center: ZAMBIA_CENTER,
            mapId: "ALL_DEVICE_MAP",
            mapTypeId: 'hybrid',
            // restriction: {
            //     latLngBounds: ZAMBIA_BOUNDS,
            //     strictBounds: false,
            // },

            streetViewControl: false,
            fullscreenControl: true,
        });
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        const map = await MapSingleton.getMap();
        let polyline;

        $wire.on('route-loaded', function (e) {
            if (polyline) {
                polyline.setMap(null);
            }
            removeMarker(marker);

            points = e.points;
            console.log("Number of points: " + points.length);
            const path = points.map(p => ({lat: p.lat, lng: p.lng}));

            overviewLine = new google.maps.Polyline({
                path,
                strokeColor: '#1e88e5',
                strokeOpacity: 0.45,
                strokeWeight: 4,
                geodesic: true,
                map,
            });
            playedLine = new google.maps.Polyline({
                path: [path[0]], // grows as playback progresses
                strokeColor: '#1e88e5',
                strokeOpacity: 0.9,
                strokeWeight: 6,
                geodesic: true,
                map,
            });


            overviewLine.setMap(map);
            playedLine.setMap(map);

            let start = points[0];
            marker = new AdvancedMarkerElement({
                position: start,
                map
            });
        })

        let playbackInterval = null;
        let index = 0;

        $js('play', () => {
            // prevent multiple intervals
            if (playbackInterval) return;

            playbackInterval = setInterval(() => {
                if (index >= points.length) {
                    clearInterval(playbackInterval);
                    playbackInterval = null;
                    return;
                }

                const p = points[index];

                animatedMove(
                    marker,
                    0.5,
                    marker.position,
                    {lat: p.lat, lng: p.lng}
                );
                updatePlayedLine(index);
                index++;
            }, 500);
        });

        $js('pause', () => {
            if (playbackInterval) {
                clearInterval(playbackInterval);
                playbackInterval = null;
            }
        });

        $js('reset', () => {
            if (playbackInterval) {
                clearInterval(playbackInterval);
                playbackInterval = null;
            }

            index = 0;
        });

    }

    function updatePlayedLine(idx) {
        const playedPath = overviewLine.getPath().getArray().slice(0, idx + 1);
        playedLine.setPath(playedPath);
    }

    function removeMarker(marker) {
        if (!marker) return;
        marker.map = null;
        marker = null;
    }

    window.MapSingleton = (() => {
        let map = null;
        let resolveMap;
        let mapReady = new Promise((resolve) => {
            resolveMap = resolve;
        });

        return {
            init(mapElementId, options) {
                if (map) return mapReady;

                map = new google.maps.Map(
                    document.getElementById(mapElementId),
                    options
                );

                resolveMap(map);
                return mapReady;
            },

            async getMap() {
                return mapReady;
            }
        };
    })();

    function animatedMove(marker, t, current, moveto) {
        var deltalat = (moveto.lat - current.lat) / 100;
        var deltalng = (moveto.lng - current.lng) / 100;

        var delay = 10 * t;
        for (var i = 0; i < 100; i++) {
            (function (ind) {
                setTimeout(function () {
                    var lat = marker.position.lat;
                    var lng = marker.position.lng;
                    lat += deltalat;
                    lng += deltalng;
                    marker.position = {lat: lat, lng: lng};
                }, delay * ind);
            })(i);
        }
    }


    async function addOrUpdateMarker(location) {
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        const nextPos = {lat: location.latitude, lng: location.longitude};
        const heading = Number(location.angle ?? 0);
        const severity = location.signals?.severity ?? "gray";
        const fill = severityToHex(severity);
        const abnormal = isAbnormalSeverity(severity);
        const isSelected = (location.imei === selectedImei);

        if (markers[location.imei]) {
            const marker = markers[location.imei];

            // Keep latest location cached for tier re-rendering
            marker.__lastLocation = location;

            // Move smoothly
            animatedMove(marker, 0.5, marker.position, nextPos);

            // Ensure correct mode + update visuals
            await ensureMode(marker, location, heading, fill, abnormal, isSelected);
            return;
        }

        const map = await MapSingleton.getMap();

        // Create marker with initial content based on current zoom
        const marker = new AdvancedMarkerElement({
            position: nextPos,
            map,
            content: document.createElement("div"),
            title: location.reg_number ?? "",
        });

        marker.__lastLocation = location;
        marker.__mode = null;
        marker.__rootEl = null;
        marker.__plateEl = null;
        marker.__svgEl = null;
        marker.__carPathEl = null;
        marker.__isAbnormal = abnormal;

        // Click → select vehicle
        marker.addListener("click", () => {
            $wire.dispatch("device-selected", {
                gps: location.imei,
                location: location,
            });
        });

        markers[location.imei] = marker;

        // Build initial template (based on current zoom)
        await ensureMode(marker, location, heading, fill, abnormal, isSelected);
    }


</script>
@endscript
