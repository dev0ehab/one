<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $owner, string $id): string
{
    return "{$owner}-{$id}";
}

$req = [
    'name'      => 'Home Dashboard',
    'id'        => 'p1h2o3m4-e5f6-4789-a111-111111111111',
    'uid'       => uid($owner, 'p1h2o3m4-e5f6-4789-a111-111111111111'),
    'description' => 'Figma provider/home.png — عدادات الحجوزات والاستفسارات',
    'createdAt' => $now,
    'updatedAt' => $now,
    'request'   => [
        'method' => 'GET',
        'header' => [
            ['key' => 'Accept', 'value' => 'application/json'],
            ['key' => 'Authorization', 'value' => 'Bearer {{provider_token}}'],
            ['key' => 'lang', 'value' => 'ar'],
        ],
        'url' => [
            'raw'  => '{{base_url}}/api/provider/home',
            'host' => ['{{base_url}}'],
            'path' => ['api', 'provider', 'home'],
        ],
    ],
    'response' => [],
];

$homeFolder = [
    'name' => 'Home',
    'id'   => 'p0h1o2m3-e4f5-4678-a000-000000000000',
    'uid'  => uid($owner, 'p0h1o2m3-e4f5-4678-a000-000000000000'),
    'description' => 'Figma design/provider/home.png',
    'createdAt' => $now,
    'updatedAt' => $now,
    'item' => [$req],
];

foreach ($collection['item'] as $i => $item) {
    if (($item['name'] ?? '') !== 'Provider') {
        continue;
    }
    $collection['item'][$i]['item'] = array_values(array_filter(
        $collection['item'][$i]['item'] ?? [],
        fn ($sub) => ($sub['name'] ?? '') !== 'Home'
    ));
    array_unshift($collection['item'][$i]['item'], $homeFolder);
    break;
}

file_put_contents($path, json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Added Provider Home request\n";
