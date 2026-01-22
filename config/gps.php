<?php
return [
    'port' => env('GPS_PORT'),
    'track' => env('GPS_TRACK'),
    'interval' => env('GPS_INTERVAL'),
    'speed_limit' => [
        'min' => env('GPS_MIN_SPEED_LIMIT', 5),
        'max' => env('GPS_MAX_SPEED_LIMIT', 100),
    ],
    'io' => [

        /* =========================
         * DIGITAL / STATUS
         * ========================= */

        1 => [
            'name' => 'Ignition',
            'unit' => 'bool',
            'type' => 'digital',
            'notes' => 'DIN1 / ignition status',
        ],

        69 => [
            'name' => 'GNSS Status',
            'unit' => 'bool',
            'type' => 'status',
            'notes' => '0 = no fix, 1 = fix',
        ],

        239 => [
            'name' => 'Ignition Source',
            'unit' => 'enum',
            'type' => 'meta',
            'map' => [
                0 => 'Unknown',
                1 => 'DIN1',
                2 => 'CAN',
                3 => 'Voltage',
            ],
        ],

        240 => [
            'name' => 'Movement Source',
            'unit' => 'enum',
            'type' => 'meta',
            'map' => [
                0 => 'Unknown',
                1 => 'GPS',
                2 => 'Accelerometer',
            ],
        ],

        /* =========================
         * GPS / SIGNAL QUALITY
         * ========================= */

        21 => [
            'name' => 'GSM Signal Strength',
            'unit' => 'level',
            'type' => 'signal',
            'range' => '0–5',
        ],

        113 => [
            'name' => 'GNSS PDOP',
            'unit' => 'x0.1',
            'type' => 'accuracy',
            'notes' => 'Lower is better (value / 10)',
        ],

        /* =========================
         * SPEED / MOVEMENT
         * ========================= */

        24 => [
            'name' => 'Speed',
            'unit' => 'km/h',
            'type' => 'movement',
        ],

        /* =========================
         * VOLTAGE / POWER
         * ========================= */

        66 => [
            'name' => 'External Voltage',
            'unit' => 'mV',
            'type' => 'power',
            'scale' => 0.001, // mV → V
            'notes' => 'Vehicle battery',
        ],

        67 => [
            'name' => 'Internal Battery Voltage',
            'unit' => 'mV',
            'type' => 'power',
            'scale' => 0.001,
            'notes' => 'Backup battery',
        ],

        /* =========================
         * ANALOG INPUTS
         * ========================= */

        9 => [
            'name' => 'Analog Input 1',
            'unit' => 'mV',
            'type' => 'analog',
            'notes' => 'User-configured sensor',
        ],

        /* =========================
         * ODOMETER / DISTANCE
         * ========================= */

        16 => [
            'name' => 'Total Odometer',
            'unit' => 'm',
            'type' => 'odometer',
            'notes' => 'Virtual odometer (meters)',
        ],

        199 => [
            'name' => 'Total Odometer (Alias)',
            'unit' => 'm',
            'type' => 'odometer',
            'notes' => 'Same as ID 16 (depends on config)',
        ],

        /* =========================
         * FUEL (ONLY IF CONFIGURED)
         * ========================= */

        96 => [
            'name' => 'Fuel Used (Virtual)',
            'unit' => 'l',
            'type' => 'fuel_virtual',
            'warning' => 'Not real fuel level unless CAN/OBD is configured',
        ],

        270 => [
            'name' => 'Fuel Level (LLS)',
            'unit' => 'l',
            'type' => 'fuel',
            'source' => 'Escort LLS',
        ],

        276 => [
            'name' => 'Fuel Level (LLS 2)',
            'unit' => 'l',
            'type' => 'fuel',
        ],

        279 => [
            'name' => 'Fuel Level (LLS 3)',
            'unit' => 'l',
            'type' => 'fuel',
        ],

        327 => [
            'name' => 'Fuel Level (CAN)',
            'unit' => 'l',
            'type' => 'fuel',
            'source' => 'CAN adapter',
        ],

    ]
];

