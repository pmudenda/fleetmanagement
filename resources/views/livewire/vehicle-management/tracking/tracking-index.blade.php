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

    <div id="gps-details-canvas" class="offcanvas offcanvas-end" data-bs-backdrop="false" data-bs-scroll="true" tabindex="-1"
         aria-labelledby="gps-details-canvas" wire:ignore>
        <livewire:vehicle-management.tracking.gps-device-details/>
    </div>
</div>

@script
<script type="text/javascript">
    let markers = {};
    let selectedImei = null;

    // Tier thresholds
    const ZOOM_DOT_MAX = 7;     // zoom <= 7 => DOT (i.e. < 8)
    const ZOOM_SIMPLE_MAX = 12; // zoom 8..12 => SIMPLE, >12 => FULL

    initMap();
    const canvas = new bootstrap.Offcanvas('#gps-details-canvas');

    Echo.channel('gps')
        .listen('.CurrentLocation', async e => {
            await addOrUpdateMarker(e.location);

            $wire.dispatch('location-changed', {
                gps: e.location.imei,
                location: e.location
            });
        });

    $wire.on('device-selected', async (event) => {
        const gps = event.gps;
        selectedImei = gps;

        canvas.show();

        const marker = markers[gps];
        if (marker) {
            const map = await MapSingleton.getMap();
            map.panTo(marker.position);
        }

        // Update selection styling on all markers
        await refreshAllMarkerModes(true);
    });

    $wire.on('location-centered', async (e) => {
        const marker = markers[e.gps];
        const map = await MapSingleton.getMap();
        if (marker) smoothPanTo(map, marker.position, 1000);
    });

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
        const ZAMBIA_CENTER = { lat: -13.175, lng: 27.846 };

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

        const map = await MapSingleton.getMap();

        // Apply tier logic on zoom changes
        map.addListener("zoom_changed", async () => {
            await refreshAllMarkerModes(false);
        });

        const devices = @json($lastloactions);
        for (const l of devices) {
            await addOrUpdateMarker(l);
        }

        // Ensure markers match current zoom after initial render
        await refreshAllMarkerModes(false);
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

    function severityToHex(severity) {
        switch (severity) {
            case "red": return "#dc3545";
            case "amber": return "#ffc107";
            case "green": return "#28a745";
            default: return "#6c757d"; // gray
        }
    }

    function isAbnormalSeverity(sev) {
        // MVP abnormal: amber/red only (green/gray are normal)
        return sev === "red" || sev === "amber";
    }

    function getTierMode(zoom) {
        if (zoom <= ZOOM_DOT_MAX) return "dot";      // < 8
        if (zoom <= ZOOM_SIMPLE_MAX) return "dot"; // 8..12
        return "full";                                // > 12
    }

    // Your overhead car SVG path
    const OVERHEAD_CAR_PATH = `
M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759
c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z
M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z
M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z
M15.741,21.713v4.492l-2.73-0.349V14.502L15.741,21.713z
M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z
M14.568,40.882l2.218-3.336h13.771l2.219,3.336H14.568z
M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805
`.trim();

    function fadeSwapContent(marker, newContentEl) {
        // Soft transition to avoid “harsh snap”
        newContentEl.style.opacity = "0";
        newContentEl.style.transition = "opacity 160ms ease";

        marker.content = newContentEl;

        requestAnimationFrame(() => {
            newContentEl.style.opacity = "1";
        });
    }

    function applySelectionStyle(rootEl, isSelected) {
        if (!rootEl) return;
        if (isSelected) {
            rootEl.style.filter = "drop-shadow(0 3px 6px rgba(0,0,0,0.35))";
        } else {
            rootEl.style.filter = "none";
        }
    }

    function buildDotContent({ fill, isAbnormal, isSelected,plateText }) {
        const root = document.createElement("div");
        root.style.position = "relative";
        root.style.transform = "translate(-50%, -50%)";

        const dot = document.createElement("div");
        dot.style.width = "20px";
        dot.style.height = "20px";
        dot.style.borderRadius = "50%";
        dot.style.background = fill;
        dot.style.border = "2px solid rgba(0,0,0,0.5)";
        dot.style.boxSizing = "border-box";
        dot.style.margin = "0 auto 4px auto";


        // Plate label
        const label = document.createElement("div");
        label.textContent = plateText;
        label.style.padding = "1px 6px";
        label.style.borderRadius = "10px";
        label.style.background = "rgba(255,255,255,0.95)";
        label.style.border = "1px solid rgba(0,0,0,0.25)";
        label.style.boxShadow = "0 1px 2px rgba(0,0,0,0.18)";
        label.style.fontSize = "10px";
        label.style.fontWeight = "700";
        label.style.whiteSpace = "nowrap";
        label.style.color = fill;

        root.appendChild(dot);
        root.appendChild(label);

        if (isAbnormal) {
            // subtle ring/pulse
            const ring = document.createElement("div");
            ring.style.position = "absolute";
            ring.style.left = "50%";
            ring.style.top = "50%";
            ring.style.width = "22px";
            ring.style.height = "22px";
            ring.style.transform = "translate(-50%, -50%)";
            ring.style.borderRadius = "50%";
            ring.style.border = `2px solid ${fill}`;
            ring.style.opacity = "0.65";
            ring.style.boxSizing = "border-box";

            // simple pulse using CSS animation (inline)
            ring.style.animation = "pulseRing 1.4s ease-out infinite";

            root.appendChild(ring);
        }

        applySelectionStyle(root, isSelected);

        return { root, plateEl: null, svgEl: null, carPathEl: null };
    }

    function buildSimpleContent({ plateText, heading, fill, isAbnormal, isSelected }) {
        const root = document.createElement("div");
        root.style.position = "relative";
        root.style.transform = "translate(-50%, -85%)";
        root.style.display = "flex";
        root.style.flexDirection = "column";
        root.style.alignItems = "center";
        root.style.gap = "4px";
        root.style.userSelect = "none";

        // Simplified vehicle glyph (smaller)
        const svgNS = "http://www.w3.org/2000/svg";
        const svg = document.createElementNS(svgNS, "svg");
        svg.setAttribute("width", "26");
        svg.setAttribute("height", "26");
        svg.setAttribute("viewBox", "0 0 24 24");
        svg.style.transformOrigin = "50% 50%";
        svg.style.transform = `rotate(${heading}deg)`;
        svg.style.margin = "0 auto";


        const path = document.createElementNS(svgNS, "path");
        path.setAttribute(
            "d",
            "M7 4h10l2 5v8l-2 1h-1l-1-2H9l-1 2H7l-2-1V9l2-5zm2 2-1 3h8l-1-3H9zm-1 5v5h2v-2h4v2h2v-5H8z"
        );
        path.setAttribute("fill", fill);
        path.setAttribute("stroke", "rgba(0,0,0,0.65)");
        path.setAttribute("stroke-width", "0.6");
        svg.appendChild(path);

        // Plate label
        const label = document.createElement("div");
        label.textContent = plateText;
        label.style.padding = "1px 6px";
        label.style.borderRadius = "10px";
        label.style.background = "rgba(255,255,255,0.95)";
        label.style.border = "1px solid rgba(0,0,0,0.25)";
        label.style.boxShadow = "0 1px 2px rgba(0,0,0,0.18)";
        label.style.fontSize = "12px";
        label.style.fontWeight = "700";
        label.style.whiteSpace = "nowrap";
        label.style.color = fill;

        // Prevent hover flicker
        label.style.pointerEvents = "none";

        // Option A: abnormal always visible; normal hidden until hover
        label.style.display = isAbnormal ? "inline-block" : "none";

        root.appendChild(svg);
        root.appendChild(label);

        // Hover: show label only for normal vehicles
        root.addEventListener("mouseenter", () => {
            if (!root.__isAbnormal) label.style.display = "inline-block";
        });
        root.addEventListener("mouseleave", () => {
            if (!root.__isAbnormal) label.style.display = "none";
        });

        // Track abnormal state on root so hover stays correct even after updates
        root.__isAbnormal = isAbnormal;

        applySelectionStyle(root, isSelected);

        return { root, plateEl: label, svgEl: svg, carPathEl: path };
    }

    function buildFullContent({ plateText, heading, fill, isSelected }) {
        const root = document.createElement("div");
        root.style.position = "relative";
        root.style.textAlign = "center";
        root.style.transform = "translate(-50%, -85%)";
        root.style.userSelect = "none";

        const svgWrap = document.createElement("div");
        svgWrap.style.width  = "38px";
        svgWrap.style.height = "64px";

        svgWrap.style.margin = "0 auto";

        svgWrap.innerHTML = `
            <svg width="38" height="64" viewBox="0 0 48 48"
                 style="display:block; transform: rotate(${heading}deg); transform-origin: 50% 50%;">
                <path d="${OVERHEAD_CAR_PATH}" fill="${fill}" stroke="rgba(0,0,0,0.75)" stroke-width="1"></path>
            </svg>
        `;

        const svg = svgWrap.querySelector("svg");
        const carPath = svgWrap.querySelector("path");

        const plate = document.createElement("div");
        plate.textContent = plateText;
        plate.style.marginTop = "4px";
        plate.style.padding = "2px 8px";
        plate.style.borderRadius = "12px";
        plate.style.background = "rgba(255,255,255,0.95)";
        plate.style.border = "1px solid rgba(0,0,0,0.25)";
        plate.style.boxShadow = "0 1px 2px rgba(0,0,0,0.20)";
        plate.style.fontSize = "12px";
        plate.style.fontWeight = "800";
        plate.style.whiteSpace = "nowrap";
        plate.style.color = fill;
        plate.style.pointerEvents = "none";
        plate.style.display = "inline-block"; // FULL: always visible

        root.appendChild(svgWrap);
        root.appendChild(plate);

        applySelectionStyle(root, isSelected);

        return { root, plateEl: plate, svgEl: svg, carPathEl: carPath };
    }

    async function ensureMode(marker, location, heading, fill, isAbnormal, isSelected) {
        const map = await MapSingleton.getMap();
        const zoom = map.getZoom() ?? 8;
        const wantedMode = getTierMode(zoom);

        const plateText = location.reg_number ?? "BAD 8909";

        // Update cached state (used by hover logic etc.)
        marker.__isAbnormal = isAbnormal;
        marker.__severity = location.signals?.severity ?? "gray";

        if (marker.__mode === wantedMode && marker.__rootEl) {
            // Update visuals without swapping templates
            if (marker.__svgEl) {
                marker.__svgEl.style.transform = `rotate(${heading}deg)`;
                marker.__svgEl.style.transformOrigin = "50% 50%";
            }
            if (marker.__carPathEl) {
                // For dot mode, this is harmless; for SVG it updates fill
                try { marker.__carPathEl.setAttribute("fill", fill); } catch (e) {}
            }
            if (marker.__plateEl) {
                marker.__plateEl.textContent = plateText;
                marker.__plateEl.style.color = fill;

                if (wantedMode === "simple") {
                    // abnormal visible, normal hidden (hover reveals)
                    marker.__plateEl.style.display = marker.__isAbnormal ? "inline-block" : "none";
                    marker.__rootEl.__isAbnormal = marker.__isAbnormal; // keep hover state correct
                }
                if (wantedMode === "full") {
                    marker.__plateEl.style.display = "inline-block";
                }
            }

            applySelectionStyle(marker.__rootEl, isSelected);
            return;
        }

        // Swap template (snapped mode change, softened with fade)
        let built;
        if (wantedMode === "dot") {
            built = buildDotContent({ fill, isAbnormal, isSelected,plateText });
        } else if (wantedMode === "simple") {
            built = buildSimpleContent({ plateText, heading, fill, isAbnormal, isSelected });
        } else {
            built = buildFullContent({ plateText, heading, fill, isSelected });
        }

        marker.__mode = wantedMode;
        marker.__rootEl = built.root;
        marker.__plateEl = built.plateEl;
        marker.__svgEl = built.svgEl;
        marker.__carPathEl = built.carPathEl;

        fadeSwapContent(marker, built.root);
    }

    async function refreshAllMarkerModes(force = false) {
        const map = await MapSingleton.getMap();
        const zoom = map.getZoom() ?? 8;
        const wantedMode = getTierMode(zoom);

        const keys = Object.keys(markers);
        for (const imei of keys) {
            const marker = markers[imei];
            if (!marker || !marker.__lastLocation) continue;

            if (!force && marker.__mode === wantedMode) {
                // Still apply selection style, because selection can change without zoom change
                applySelectionStyle(marker.__rootEl, imei === selectedImei);
                continue;
            }

            const loc = marker.__lastLocation;
            const heading = Number(loc.angle ?? 0);
            const severity = loc.signals?.severity ?? "gray";
            const fill = severityToHex(severity);
            const abnormal = isAbnormalSeverity(severity);
            const isSelected = (imei === selectedImei);

            await ensureMode(marker, loc, heading, fill, abnormal, isSelected);
        }
    }

    async function addOrUpdateMarker(location) {
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        const nextPos = { lat: location.latitude, lng: location.longitude };
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

    // ---- Animations / utilities ----

    // Pulse ring for DOT mode (inline keyframes)
    (function injectPulseCss() {
        const css = `
            @keyframes pulseRing {
                0%   { transform: translate(-50%, -50%) scale(0.75); opacity: 0.65; }
                70%  { transform: translate(-50%, -50%) scale(1.20); opacity: 0.10; }
                100% { transform: translate(-50%, -50%) scale(1.25); opacity: 0; }
            }
        `;
        const style = document.createElement("style");
        style.type = "text/css";
        style.appendChild(document.createTextNode(css));
        document.head.appendChild(style);
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
                    marker.position = { lat: lat, lng: lng };
                }, delay * ind);
            })(i);
        }
    }

    function easeInOut(t) {
        return t < 0.5
            ? 2 * t * t
            : 1 - Math.pow(-2 * t + 2, 2) / 2;
    }

    function smoothPanTo(map, target, duration = 1000) {
        const start = map.getCenter();
        const startTime = performance.now();

        function animate(time) {
            const t = Math.min((time - startTime) / duration, 1);
            const p = easeInOut(t);

            const lat = start.lat() + (target.lat - start.lat()) * p;
            const lng = start.lng() + (target.lng - start.lng()) * p;

            map.setCenter({ lat, lng });

            if (t < 1) requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);
    }
</script>
@endscript
