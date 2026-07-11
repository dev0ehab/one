<?php

/**
 * Updates Postman collection for ad-based client booking (booking-slots + create booking).
 */

$collectionPath = __DIR__ . '/../postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($collectionPath), true);

if (!$collection) {
    fwrite(STDERR, "Failed to parse collection JSON.\n");
    exit(1);
}

$createBookingBody = [
    'ad_id' => 1,
    'date'  => '2026-06-01',
    'time'  => '14:00',
    'notes' => 'ملاحظات إضافية من العميل',
];

$createBookingRaw = json_encode($createBookingBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$createBookingRaw = str_replace('    ', '    ', $createBookingRaw); // 4-space indent

$createBookingDescription = <<<'MD'
## Create Booking

**Endpoint:** `POST /api/client/bookings`

**Auth:** Bearer `{{client_token}}`

### Flow
1. `GET /api/client/providers/{id}/booking-slots?days=14` — load valid dates & times
2. Pick `date` (`Y-m-d`) and `time` (`H:i`) from the response
3. Submit this request with an approved active `ad_id`

### Body (JSON)
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `ad_id` | integer | yes | Provider ad ID (approved & active) |
| `date` | string | yes | `Y-m-d` from booking-slots |
| `time` | string | yes | `H:i` from selected slot |
| `notes` | string | no | Client notes (max 2000) |

### Figma
**Screen:** booking flow — [Figma 1421:10319](https://www.figma.com/design/ifLgxTuiF3pXJowbS9aNk7/One1?node-id=1421-10319)
MD;

$bookingSlotsDescription = <<<'MD'
## Provider Booking Slots

**Endpoint:** `GET /api/client/providers/{id}/booking-slots`

Returns available booking dates and time slots from the provider work schedule, slot duration, and existing bookings.

### Query
| Param | Description |
|-------|-------------|
| `days` | Optional. Days ahead (1–60, default 14) |

### Response
- `service_duration_minutes` — slot length
- `booking_notes` — shown on booking screen
- `dates[]` — `{ date, day, day_name, slots: [{ time, label }] }`

**Auth:** Optional (public)
MD;

function jsonHeaders(array $existing): array
{
    $headers = [];
    foreach ($existing as $header) {
        if (strcasecmp($header['key'] ?? '', 'Content-Type') === 0) {
            continue;
        }
        $headers[] = $header;
    }
    $headers[] = ['key' => 'Content-Type', 'value' => 'application/json'];

    return $headers;
}

function applyCreateBookingRequest(array &$request, string $raw, string $description): void
{
    $request['body'] = [
        'mode'    => 'raw',
        'raw'     => $raw,
        'options' => ['raw' => ['language' => 'json']],
    ];
    $request['header'] = jsonHeaders($request['header'] ?? []);
    $request['description'] = $description;
}

function applyCreateBookingOriginalRequest(array &$originalRequest, string $raw): void
{
    $originalRequest['body'] = [
        'mode'    => 'raw',
        'raw'     => $raw,
        'options' => ['raw' => ['language' => 'json']],
    ];
    $originalRequest['header'] = jsonHeaders($originalRequest['header'] ?? []);
}

function isClientCreateBookingUrl(array $url): bool
{
    $path = $url['path'] ?? [];
    $last = end($path);

    return ($url['raw'] ?? '') !== ''
        && str_contains($url['raw'] ?? '', '/api/client/bookings')
        && !str_contains($url['raw'] ?? '', '/pay')
        && !str_contains($url['raw'] ?? '', '/cancel')
        && !str_contains($url['raw'] ?? '', '/rate')
        && !str_contains($url['raw'] ?? '', '/additional-invoice')
        && !str_contains($url['raw'] ?? '', 'dismiss-empty-banner')
        && ($last === 'bookings' || preg_match('#/bookings$#', $url['raw'] ?? ''));
}

function walkItems(array &$items, callable $callback): void
{
    foreach ($items as &$item) {
        if (isset($item['item'])) {
            walkItems($item['item'], $callback);
        }
        $callback($item);
    }
}

$bookingSlotsItem = [
    'name'        => 'Provider Booking Slots',
    'description' => $bookingSlotsDescription,
    'request'     => [
        'method' => 'GET',
        'header' => [
            ['key' => 'Accept', 'value' => 'application/json'],
            ['key' => 'lang', 'value' => 'ar'],
        ],
        'url'    => [
            'raw'   => '{{base_url}}/api/client/providers/1/booking-slots?days=14',
            'host'  => ['{{base_url}}'],
            'path'  => ['api', 'client', 'providers', '1', 'booking-slots'],
            'query' => [
                [
                    'key'         => 'days',
                    'value'       => '14',
                    'description' => 'Days ahead (1–60, default 14)',
                ],
            ],
        ],
    ],
    'event' => [
        [
            'listen' => 'test',
            'script' => [
                'type' => 'text/javascript',
                'exec' => [
                    "pm.test('Status code is 200', function () {",
                    "    pm.response.to.have.status(200);",
                    "});",
                    "pm.test('Has dates array', function () {",
                    "    const json = pm.response.json();",
                    "    if (json.key === 'success') {",
                    "        pm.expect(json.data).to.have.property('dates');",
                    "    }",
                    "});",
                ],
            ],
        ],
    ],
];

$updatedCreate = 0;
$updatedExamples = 0;

walkItems($collection['item'], function (array &$item) use (
    $createBookingRaw,
    $createBookingDescription,
    &$updatedCreate,
    &$updatedExamples
) {
    if (($item['name'] ?? '') === 'Create Booking' && isset($item['request'])) {
        applyCreateBookingRequest($item['request'], $createBookingRaw, $createBookingDescription);
        $item['description'] = $createBookingDescription;
        $updatedCreate++;
    }

    foreach ($item['response'] ?? [] as &$response) {
        if (!isset($response['originalRequest'])) {
            continue;
        }
        $orig = &$response['originalRequest'];
        if (strtoupper($orig['method'] ?? '') !== 'POST') {
            continue;
        }
        if (!isClientCreateBookingUrl($orig['url'] ?? [])) {
            continue;
        }
        $body = $orig['body'] ?? [];
        if (($body['mode'] ?? '') === 'formdata'
            || str_contains($body['raw'] ?? '', 'provider_id')
            || str_contains($body['raw'] ?? '', 'scheduled_at')) {
            applyCreateBookingOriginalRequest($orig, $createBookingRaw);
            $updatedExamples++;
        }
    }
});

// Ensure booking-slots in Providers and Bookings folders
foreach ($collection['item'] as &$folder) {
    if (($folder['name'] ?? '') !== 'Client') {
        continue;
    }
    foreach ($folder['item'] as &$sub) {
        if (!in_array($sub['name'] ?? '', ['Providers', 'Bookings'], true)) {
            continue;
        }
        $names = array_column($sub['item'] ?? [], 'name');
        if (!in_array('Provider Booking Slots', $names, true)) {
            if (($sub['name'] ?? '') === 'Bookings') {
                array_unshift($sub['item'], $bookingSlotsItem);
            } else {
                $sub['item'][] = $bookingSlotsItem;
            }
        } else {
            // Refresh existing entry
            foreach ($sub['item'] as &$req) {
                if (($req['name'] ?? '') === 'Provider Booking Slots') {
                    $req['description'] = $bookingSlotsDescription;
                    $req['request'] = $bookingSlotsItem['request'];
                }
            }
        }
    }
}

file_put_contents(
    $collectionPath,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Updated One.postman_collection.json\n";
echo "  Create Booking requests: {$updatedCreate}\n";
echo "  Saved example bodies: {$updatedExamples}\n";
