<div class="position-relative" style="width: 100vw; height: 100vh; overflow: hidden;" wire:ignore>

    <!-- MAP (100% of wrapper) -->
    <div id="map" style="position:absolute; inset:0;"></div>

    <!-- DEVICES TABLE OVERLAY (inside map) -->
    <div
            class="position-absolute top-0 start-0 ml-2 mt-20 bg-white bg-opacity-90 rounded-3 shadow-sm p-2"
            style="z-index:1050; max-width:320px;"
    >
        <livewire:vehicle-management.tracking.gps-device-list/>
    </div>

    <div id="gps-details-canvas" class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
         aria-labelledby="gps-details-canvas" wire:ignore>
        <livewire:vehicle-management.tracking.gps-device-details/>
    </div>
</div>
@script
<script type="text/javascript">
    let markers = {};
    let details = [];

    initMap();
    const canvas = new bootstrap.Offcanvas('#gps-details-canvas')

    Echo.channel('gps')
        .listen('.CurrentLocation', async e => {
            await addOrUpdateMarker(e.location)
            console.log('dispatching location changed','location-changed.' + e.location.imei)
            $wire.dispatch('location-changed', {
                gps: e.location.imei,
                location: e.location
            });

        })

    $wire.on('device-selected', async (event) => {
        console.log('device-selected', event);
        const gps = event.gps;
        const location = event.location;
        canvas.show();
        marker = markers[gps];
        if(marker){
            const map = await MapSingleton.getMap();
            map.panTo(marker.position);
        }

    });

    // $wire.on('location-changed', async (e) => {
    //     await addOrUpdateMarker(e.location)
    // });

    $wire.on('location-centered', async (e) => {
        console.log('location-centered', e.gps);
        marker = markers[e.gps];
        const map = await MapSingleton.getMap();
        map.panTo(marker.position);
    })

    async function initMap() {
        // The location of Uluru
        const position = {lat: -15.408955, lng: 28.2847}
        // Request needed libraries.
        //@ts-ignore
        const {Map} = await google.maps.importLibrary("maps");
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        // Zambia bounding box
        const ZAMBIA_BOUNDS = {
            north: -8.2720,
            south: -18.0792,
            east: 33.7124,
            west: 21.9800,
        };

// A good visual center (roughly central Zambia)
        const ZAMBIA_CENTER = {
            lat: -13.175,
            lng: 27.846,
        };

        // The map, centered at Uluru
        MapSingleton.init("map", {
            zoom: 8,
            center: ZAMBIA_CENTER,
            mapId: "ALL_DEVICE_MAP",
            mapTypeId: 'hybrid',
            // restriction: {
            //     latLngBounds: ZAMBIA_BOUNDS,
            //     strictBounds: false,
            // },

            // Optional UX controls
            streetViewControl: false,
            fullscreenControl: true,

        });
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

    function toggleHighlight(markerView, location) {
        // Get current speed class
        // let speedClass = getSpeedClass(location.speed || 0);
        let speedClass = getSpeedClass(location.speed || 0);
        speedClass = location.is_compliant ? speedClass : 'speed-fast';
        // console.log(location.is_compliant);

        if (markerView.content.classList.contains("highlight")) {
            markerView.content.classList.remove("highlight");

            // Re-add speed class when unhighlighting
            markerView.content.classList.add(speedClass);

            markerView.zIndex = null;
        } else {
            // When highlighting, remove speed color (it will use the highlight styling)
            markerView.content.classList.remove('speed-idle', 'speed-moving', 'speed-fast');
            // markerView.content.classList.add(speedClass);

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
        speedClass = location.is_compliant ? speedClass : 'speed-fast';

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
                    <span>${location.latitude},${location.longitude}</span>
                </div>
            </div>
        </div>
    `;
        return content;
    }

    async function addOrUpdateMarker(location) {
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");

        const nextPos = {lat: location.latitude, lng: location.longitude};
        const heading = Number(location.angle ?? 0); // degrees

        if (markers[location.imei]) {
            const marker = markers[location.imei];

            // Move smoothly
            animatedMove(marker, 0.5, marker.position, nextPos);

            // Update heading (rotate the car DOM node)
            if (marker.__carEl) {
                marker.__carEl.style.transform = `rotate(${heading}deg)`;
                marker.__carEl.style.transformOrigin = "50% 50%";
            }

            // Update plate if needed
            if (marker.__plateEl) {
                marker.__plateEl.textContent = location.reg_number ?? "BAD 8909";
            }

            return;
        }

        // Create marker
        const map = await MapSingleton.getMap();

        const el = document.createElement("div");
        el.style.transform = "translate(-50%, -100%)";
        el.style.textAlign = "center";

        const car = document.createElement("div");
        car.textContent = "🚗";
        car.style.fontSize = "50px";
        car.style.transform = `rotate(${heading}deg)`;
        car.style.transformOrigin = "50% 50%";

        const plate = document.createElement("div");
        plate.textContent = location.reg_number ?? "BAD 8909";
        plate.style.fontSize = "14px";
        plate.style.fontWeight = "600";
        plate.style.background = "#fff";
        plate.style.padding = "2px 6px";
        plate.style.borderRadius = "6px";

        el.appendChild(car);
        el.appendChild(plate);

        const marker = new AdvancedMarkerElement({
            position: nextPos,
            map,
            content: el,
            title: location.reg_number ?? "",
        });

        // Save DOM references for later updates
        marker.__carEl = car;
        marker.__plateEl = plate;

        marker.addListener("click", () => {
            $wire.dispatch('device-selected', {
                gps: location.imei,
                location: location,
            })
        });

        markers[location.imei] = marker;
    }


    function createVehicleMarkerContent({plate = "", heading = 0} = {}) {
        const root = document.createElement("div");
        root.style.transform = "translate(-50%, -100%)";
        root.style.display = "flex";
        root.style.flexDirection = "column";
        root.style.alignItems = "center";
        root.style.gap = "4px";

        // Vehicle icon (simple "car" shape using SVG)
        const svgNS = "http://www.w3.org/2000/svg";
        const svg = document.createElementNS(svgNS, "svg");
        svg.setAttribute("width", "28");
        svg.setAttribute("height", "28");
        svg.setAttribute("viewBox", "0 0 24 24");
        svg.style.transformOrigin = "50% 50%";
        svg.style.transform = `rotate(${heading}deg)`;

        // Simple top-down car-ish shape
        const path = document.createElementNS(svgNS, "path");
        path.setAttribute(
            "d",
            "M7 4h10l2 5v8l-2 1h-1l-1-2H9l-1 2H7l-2-1V9l2-5zm2 2-1 3h8l-1-3H9zm-1 5v5h2v-2h4v2h2v-5H8z"
        );
        path.setAttribute("fill", "#111");
        path.setAttribute("stroke", "#fff");
        path.setAttribute("stroke-width", "0.6");
        svg.appendChild(path);

        // Number plate label
        const label = document.createElement("div");
        label.textContent = plate;
        label.style.padding = "2px 6px";
        label.style.borderRadius = "10px";
        label.style.background = "#fff";
        label.style.border = "1px solid rgba(0,0,0,.25)";
        label.style.boxShadow = "0 1px 3px rgba(0,0,0,.25)";
        label.style.fontSize = "12px";
        label.style.fontWeight = "700";
        label.style.whiteSpace = "nowrap";

        root.appendChild(svg);
        root.appendChild(label);

        return {root, svg, label};
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
</script>
@endscript
