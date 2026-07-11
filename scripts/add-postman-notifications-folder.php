<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $owner, string $id): string
{
    return "{$owner}-{$id}";
}

function req(string $name, string $id, string $method, string $urlPath, string $tokenVar, string $now): array
{
    $pathOnly = strtok($urlPath, '?');
    $parts = explode('/', trim($pathOnly, '/'));

    return [
        'name'      => $name,
        'id'        => $id,
        'uid'       => uid('54926212', $id),
        'createdAt' => $now,
        'updatedAt' => $now,
        'request'   => [
            'method' => $method,
            'header' => [
                ['key' => 'Accept', 'value' => 'application/json'],
                ['key' => 'Authorization', 'value' => "Bearer {{{$tokenVar}}}"],
                ['key' => 'lang', 'value' => 'ar'],
            ],
            'url' => [
                'raw'  => "{{base_url}}/{$urlPath}",
                'host' => ['{{base_url}}'],
                'path' => $parts,
            ],
        ],
        'response' => [],
    ];
}

$clientNotifications = [
    'name'        => 'Notifications',
    'id'          => 'n1c2d3e4-f5a6-4789-c111-111111111111',
    'uid'         => uid($owner, 'n1c2d3e4-f5a6-4789-c111-111111111111'),
    'description' => 'الإشعارات — عرض، عدّ، حذف، تفعيل',
    'createdAt'   => $now,
    'updatedAt'   => $now,
    'item'        => [
        req('Switch Notify', 'n2c3d4e5-f6a7-4890-c222-222222222222', 'PATCH', 'api/client/switch-notify', 'client_token', $now),
        req('List Notifications', 'n3c4d5e6-f7a8-4901-c333-333333333333', 'GET', 'api/client/notifications', 'client_token', $now),
        req('Count Unread', 'n4c5d6e7-f8a9-4012-c444-444444444444', 'GET', 'api/client/count-notifications', 'client_token', $now),
        req('Delete Notification', 'n5c6d7e8-f9a0-4123-c555-555555555555', 'DELETE', 'api/client/delete-notification/1', 'client_token', $now),
        req('Delete All Notifications', 'n6c7d8e9-f0a1-4234-c666-666666666666', 'DELETE', 'api/client/delete-notifications', 'client_token', $now),
    ],
];

$providerNotifications = [
    'name'        => 'Notifications',
    'id'          => 'n7c8d9e0-f1a2-4345-b777-777777777777',
    'uid'         => uid($owner, 'n7c8d9e0-f1a2-4345-b777-777777777777'),
    'description' => 'الإشعارات — عرض، عدّ، حذف، تفعيل',
    'createdAt'   => $now,
    'updatedAt'   => $now,
    'item'        => [
        req('Switch Notify', 'n8c9d0e1-f2a3-4456-b888-888888888888', 'PATCH', 'api/provider/switch-notify', 'provider_token', $now),
        req('List Notifications', 'n9c0d1e2-f3a4-4567-b999-999999999999', 'GET', 'api/provider/notifications', 'provider_token', $now),
        req('Count Unread', 'n0d1e2f3-a4b5-4678-baaa-aaaaaaaaaaaa', 'GET', 'api/provider/count-notifications', 'provider_token', $now),
        req('Delete Notification', 'n1d2e3f4-b5c6-4789-bbbb-bbbbbbbbbbbb', 'DELETE', 'api/provider/delete-notification/1', 'provider_token', $now),
        req('Delete All Notifications', 'n2d3e4f5-c6d7-4890-bccc-cccccccccccc', 'DELETE', 'api/provider/delete-notifications', 'provider_token', $now),
    ],
];

function findRootIndex(array $items, string $name): ?int
{
    foreach ($items as $i => $item) {
        if (($item['name'] ?? '') === $name) {
            return $i;
        }
    }

    return null;
}

function removeNotificationsFolder(array &$items): void
{
    $items = array_values(array_filter($items, fn ($item) => ($item['name'] ?? '') !== 'Notifications'));
}

$clientIdx = findRootIndex($collection['item'], 'Client');
$providerIdx = findRootIndex($collection['item'], 'Provider');

if ($clientIdx === null || $providerIdx === null) {
    fwrite(STDERR, "Client or Provider folder not found\n");
    exit(1);
}

removeNotificationsFolder($collection['item'][$clientIdx]['item']);
removeNotificationsFolder($collection['item'][$providerIdx]['item']);

array_unshift($collection['item'][$clientIdx]['item'], $clientNotifications);
array_unshift($collection['item'][$providerIdx]['item'], $providerNotifications);

file_put_contents($path, json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Added Notifications folders to Client and Provider\n";
