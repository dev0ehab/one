<?php

/**
 * Adds or refreshes Provider → Booking Service + Work Days folders in Postman collection.
 */

$collectionPath = __DIR__ . '/../postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($collectionPath), true);

$baseUrl = '{{base_url}}';

$testScript = [
    'listen' => 'test',
    'script' => [
        'type' => 'text/javascript',
        'exec' => [
            "pm.test('Status code is 200', function () {",
            "    pm.response.to.have.status(200);",
            "});",
            "pm.test('Response is JSON API envelope', function () {",
            "    const json = pm.response.json();",
            "    pm.expect(json).to.have.property('key');",
            "    pm.expect(json).to.have.property('msg');",
            "});",
        ],
    ],
];

function makeItem(
    string $name,
    string $method,
    string $path,
    ?array $body = null,
    string $description = ''
): array {
    global $testScript, $baseUrl;

    $pathSegments = explode('/', ltrim($path, '/'));
    $url = "$baseUrl/$path";

    $item = [
        'name'        => $name,
        'description' => $description,
        'request'     => [
            'method' => strtoupper($method),
            'header' => [
                ['key' => 'Accept', 'value' => 'application/json'],
                ['key' => 'Lang', 'value' => 'ar'],
            ],
            'url'    => [
                'raw'  => $url,
                'host' => ['{{base_url}}'],
                'path' => $pathSegments,
            ],
            'auth' => [
                'type'   => 'bearer',
                'bearer' => [['key' => 'token', 'value' => '{{provider_token}}', 'type' => 'string']],
            ],
        ],
        'event' => [$testScript],
    ];

    if ($body !== null) {
        $item['request']['body'] = [
            'mode'    => 'raw',
            'raw'     => json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'options' => ['raw' => ['language' => 'json']],
        ];
        $item['request']['header'][] = ['key' => 'Content-Type', 'value' => 'application/json'];
    }

    return $item;
}

$weekdays = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
$weeklySchedule = [];
foreach ($weekdays as $day) {
    $weeklySchedule[] = [
        'day'     => $day,
        'closed'  => $day === 'friday',
        'periods' => $day === 'friday' ? [] : [['start' => '09:00', 'end' => '17:00']],
    ];
}

$bookingServiceFolder = [
    'name'        => 'Booking Service',
    'description' => 'Provider: subscribe to booking service (monthly fee from site settings) and configure duration, notes, reminders.',
    'item'        => [
        makeItem(
            'Get Booking Service Settings',
            'GET',
            'api/provider/booking-service',
            null,
            "## Get Booking Service Settings\n\n**Endpoint:** `GET /api/provider/booking-service`\n\nReturns `has_booking_service`, expiry, `service_duration_minutes`, `booking_notes`, `booking_reminder_minutes`, `monthly_fee`, `duration_days`, and `work_days`.\n\n**Auth:** Bearer `{{provider_token}}`"
        ),
        makeItem(
            'Subscribe to Booking Service',
            'POST',
            'api/provider/booking-service/subscribe',
            ['payment_method' => 'wallet'],
            "## Subscribe to Booking Service\n\n**Endpoint:** `POST /api/provider/booking-service/subscribe`\n\n**Body:** `payment_method` — `wallet` | `online`\n\nDeducts `booking_service_monthly_fee` from wallet (when fee > 0). Sets `has_booking_service` and `booking_service_expires_at`.\n\n**Auth:** Bearer `{{provider_token}}`"
        ),
        makeItem(
            'Update Booking Service Settings',
            'PATCH',
            'api/provider/booking-service',
            [
                'service_duration_minutes' => 30,
                'booking_notes'            => 'يرجى الحضور قبل الموعد بـ 10 دقائق',
                'booking_reminder_minutes' => 60,
                'weekly_schedule'          => $weeklySchedule,
            ],
            "## Update Booking Service Settings\n\n**Endpoint:** `PATCH /api/provider/booking-service`\n\n**Body (send at least one field):**\n- `service_duration_minutes` — 5–480\n- `booking_notes` — shown to client on booking screen\n- `booking_reminder_minutes` — alert before appointment\n- `weekly_schedule` — all 7 weekdays with `closed` and `periods` (`start`/`end` as `H:i`)\n\nRequires active booking service subscription.\n\n**Auth:** Bearer `{{provider_token}}`"
        ),
    ],
];

$workDaysFolder = [
    'name'        => 'Work Days',
    'description' => 'Provider: weekly schedule for booking availability (used with booking service).',
    'item'        => [
        makeItem(
            'Get Work Days',
            'GET',
            'api/provider/work-days',
            null,
            "## Get Work Days\n\n**Endpoint:** `GET /api/provider/work-days`\n\nLists work day periods for the authenticated provider.\n\n**Auth:** Bearer `{{provider_token}}`"
        ),
        makeItem(
            'Update Work Days',
            'PUT',
            'api/provider/work-days/update-work-days',
            ['weekly_schedule' => $weeklySchedule],
            "## Update Work Days\n\n**Endpoint:** `PUT /api/provider/work-days/update-work-days`\n\n**Body:** `weekly_schedule` — all 7 weekdays; open days need at least one period (`start`/`end` as `H:i`).\n\n**Auth:** Bearer `{{provider_token}}`"
        ),
    ],
];

$providerFolderIndex = null;
foreach ($collection['item'] as $i => $folder) {
    if (($folder['name'] ?? '') === 'Provider') {
        $providerFolderIndex = $i;
        break;
    }
}

if ($providerFolderIndex === null) {
    echo "Provider folder not found.\n";
    exit(1);
}

$items = $collection['item'][$providerFolderIndex]['item'] ?? [];
$filtered = array_values(array_filter(
    $items,
    fn ($f) => !in_array($f['name'] ?? '', ['Booking Service', 'Work Days'], true)
));

$packagesIndex = null;
foreach ($filtered as $i => $f) {
    if (($f['name'] ?? '') === 'Packages') {
        $packagesIndex = $i;
        break;
    }
}

$newFolders = [$bookingServiceFolder, $workDaysFolder];
if ($packagesIndex !== null) {
    array_splice($filtered, $packagesIndex, 0, $newFolders);
} else {
    $filtered = array_merge($filtered, $newFolders);
}

$collection['item'][$providerFolderIndex]['item'] = $filtered;

file_put_contents(
    $collectionPath,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

echo "Provider folder updated: Booking Service (3 requests) + Work Days (2 requests).\n";
