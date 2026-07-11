<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);

if (!$collection) {
    fwrite(STDERR, "Invalid collection JSON\n");
    exit(1);
}

$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $owner, string $id): string
{
    return "{$owner}-{$id}";
}

function makeFolder(string $name, string $id, array $items, string $owner, string $now, ?string $description = null): array
{
    $folder = [
        'name'      => $name,
        'item'      => $items,
        'id'        => $id,
        'createdAt' => $now,
        'updatedAt' => $now,
        'uid'       => uid($owner, $id),
    ];
    if ($description) {
        $folder['description'] = $description;
    }

    return $folder;
}

function normalizePayRequest(array $item, string $id, string $owner, string $now): array
{
    $item['id'] = $id;
    $item['uid'] = uid($owner, $id);
    $item['createdAt'] = $now;
    $item['updatedAt'] = $now;
    $item['request']['url'] = [
        'raw'  => '{{base_url}}/api/client/bookings/1/additional-invoice/pay',
        'host' => ['{{base_url}}'],
        'path' => ['api', 'client', 'bookings', '1', 'additional-invoice', 'pay'],
    ];

    return $item;
}

function findFolderIndex(array $items, string $name): ?int
{
    foreach ($items as $index => $item) {
        if (($item['name'] ?? '') === $name && isset($item['item'])) {
            return $index;
        }
    }

    return null;
}

$clientNames = [
    'Accept Additional Invoice',
    'Reject Additional Invoice',
    'Pay Additional Invoice (wallet)',
    'Pay Additional Invoice (online)',
];
$providerNames = ['Issue Additional Invoice'];

$clientFolderId = 'c8e4f1a2-3b5d-4e6f-9a0b-1c2d3e4f5a6b';
$providerFolderId = 'd9f5a2b3-4c6e-5f7a-0b1c-2d3e4f5a6b7c';
$payWalletId = 'e1a6b3c4-5d7f-6a8b-9c0d-3e4f5a6b7c8d';
$payOnlineId = 'f2b7c4d5-6e8a-7b9c-0d1e-4f5a6b7c8d9e';

foreach (['Client', 'Provider'] as $rootName) {
    $rootIdx = findFolderIndex($collection['item'], $rootName);
    if ($rootIdx === null) {
        fwrite(STDERR, "Missing root folder: {$rootName}\n");
        exit(1);
    }

    $bookingsIdx = findFolderIndex($collection['item'][$rootIdx]['item'], 'Bookings');
    if ($bookingsIdx === null) {
        fwrite(STDERR, "Missing Bookings under {$rootName}\n");
        exit(1);
    }

    $names = $rootName === 'Client' ? $clientNames : $providerNames;
    $extracted = [];
    $remaining = [];

    foreach ($collection['item'][$rootIdx]['item'][$bookingsIdx]['item'] as $req) {
        if (in_array($req['name'] ?? '', $names, true)) {
            if ($req['name'] === 'Pay Additional Invoice (wallet)') {
                $req = normalizePayRequest($req, $payWalletId, $owner, $now);
            } elseif ($req['name'] === 'Pay Additional Invoice (online)') {
                $req = normalizePayRequest($req, $payOnlineId, $owner, $now);
            }
            $extracted[] = $req;
        } elseif (($req['name'] ?? '') !== 'Additional Invoice') {
            $remaining[] = $req;
        }
    }

    if ($extracted === []) {
        fwrite(STDERR, "No additional-invoice requests found under {$rootName}/Bookings\n");
        continue;
    }

    $subFolderId = $rootName === 'Client' ? $clientFolderId : $providerFolderId;
    $subFolder = makeFolder(
        'Additional Invoice',
        $subFolderId,
        $extracted,
        $owner,
        $now,
        'فاتورة إضافية — accept / reject / pay (client) · issue (provider)'
    );

    $collection['item'][$rootIdx]['item'][$bookingsIdx]['item'] = array_merge($remaining, [$subFolder]);
    $collection['item'][$rootIdx]['item'][$bookingsIdx]['updatedAt'] = $now;
}

$collection['info']['updatedAt'] = $now;

file_put_contents(
    $path,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Reorganized: Client/Bookings/Additional Invoice + Provider/Bookings/Additional Invoice\n";
