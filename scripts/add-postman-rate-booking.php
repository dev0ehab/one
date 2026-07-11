<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$now = gmdate('Y-m-d\TH:i:s.000\Z');
$owner = '54926212';

function findBookingsIndex(array $items, string $rootName): ?array
{
    foreach ($items as $i => $root) {
        if (($root['name'] ?? '') !== $rootName) {
            continue;
        }
        foreach ($root['item'] ?? [] as $j => $folder) {
            if (($folder['name'] ?? '') === 'Bookings') {
                return [$i, $j];
            }
        }
    }

    return null;
}

$idx = findBookingsIndex($collection['item'], 'Client');
if ($idx === null) {
    fwrite(STDERR, "Client/Bookings not found\n");
    exit(1);
}

[$clientIdx, $bookingsIdx] = $idx;
$items = &$collection['item'][$clientIdx]['item'][$bookingsIdx]['item'];

foreach ($items as $item) {
    if (($item['name'] ?? '') === 'Rate Booking') {
        echo "Rate Booking already exists\n";
        exit(0);
    }
}

$id = 'a9b8c7d6-e5f4-4321-a987-6543210fedcb';
$items[] = [
    'name' => 'Rate Booking',
    'id'   => $id,
    'uid'  => "{$owner}-{$id}",
    'createdAt' => $now,
    'updatedAt' => $now,
    'request' => [
        'method' => 'POST',
        'header' => [
            ['key' => 'Accept', 'value' => 'application/json'],
            ['key' => 'Content-Type', 'value' => 'application/json'],
            ['key' => 'Authorization', 'value' => 'Bearer {{client_token}}'],
            ['key' => 'lang', 'value' => 'ar'],
        ],
        'body' => [
            'mode' => 'raw',
            'raw'  => json_encode(['rate' => 5, 'comment' => 'خدمة ممتازة'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'options' => ['raw' => ['language' => 'json']],
        ],
        'url' => [
            'raw'  => '{{base_url}}/api/client/bookings/1/rate',
            'host' => ['{{base_url}}'],
            'path' => ['api', 'client', 'bookings', '1', 'rate'],
        ],
    ],
    'response' => [],
];

file_put_contents(
    $path,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Added Rate Booking to Client/Bookings\n";
