<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $owner, string $id): string
{
    return "{$owner}-{$id}";
}

function req(string $name, string $id, string $method, string $urlPath, string $tokenVar, string $now, ?array $body = null, string $bodyMode = 'json'): array
{
    $pathOnly = strtok($urlPath, '?');
    $parts = explode('/', trim($pathOnly, '/'));
    $item = [
        'name'    => $name,
        'id'      => $id,
        'uid'     => uid('54926212', $id),
        'createdAt' => $now,
        'updatedAt' => $now,
        'request' => [
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

    if ($body !== null) {
        if ($bodyMode === 'formdata') {
            $item['request']['header'][] = ['key' => 'Content-Type', 'value' => 'multipart/form-data'];
            $item['request']['body'] = ['mode' => 'formdata', 'formdata' => $body];
        } else {
            $item['request']['header'][] = ['key' => 'Content-Type', 'value' => 'application/json'];
            $item['request']['body'] = [
                'mode'    => 'raw',
                'raw'     => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'options' => ['raw' => ['language' => 'json']],
            ];
        }
    }

    return $item;
}

$clientWallet = [
    'name' => 'Wallet',
    'id'   => 'a1b2c3d4-e5f6-4789-a111-111111111111',
    'uid'  => uid($owner, 'a1b2c3d4-e5f6-4789-a111-111111111111'),
    'description' => 'المحفظة — رصيد، معاملات، شحن',
    'createdAt' => $now,
    'updatedAt' => $now,
    'item' => [
        req('Get Wallet', 'b2c3d4e5-f6a7-4890-a222-222222222222', 'GET', 'api/client/wallet', 'client_token', $now),
        req('List Transactions', 'c3d4e5f6-a7b8-4901-a333-333333333333', 'GET', 'api/client/wallet/transactions', 'client_token', $now),
        req('List Charge Requests', 'd4e5f6a7-b8c9-4012-a444-444444444444', 'GET', 'api/client/wallet/charge-requests', 'client_token', $now),
        req('Charge Wallet (online)', 'e5f6a7b8-c9d0-4123-a555-555555555555', 'POST', 'api/client/wallet/charge', 'client_token', $now, ['amount' => 100, 'type' => 'online']),
        req('Charge Wallet (bank transfer)', 'f6a7b8c9-d0e1-4234-a666-666666666666', 'POST', 'api/client/wallet/charge/bank-transfer', 'client_token', $now, [
            ['key' => 'amount', 'value' => '250', 'type' => 'text'],
            ['key' => 'image', 'value' => '', 'type' => 'file'],
        ], 'formdata'),
    ],
];

$providerWallet = [
    'name' => 'Wallet',
    'id'   => 'a7b8c9d0-e1f2-4345-b777-777777777777',
    'uid'  => uid($owner, 'a7b8c9d0-e1f2-4345-b777-777777777777'),
    'description' => 'المحفظة — رصيد، معاملات، شحن',
    'createdAt' => $now,
    'updatedAt' => $now,
    'item' => [
        req('Get Wallet', 'b8c9d0e1-f2a3-4456-b888-888888888888', 'GET', 'api/provider/wallet', 'provider_token', $now),
        req('List Transactions', 'c9d0e1f2-a3b4-4567-b999-999999999999', 'GET', 'api/provider/wallet/transactions', 'provider_token', $now),
        req('List Charge Requests', 'd0e1f2a3-b4c5-4678-baaa-aaaaaaaaaaaa', 'GET', 'api/provider/wallet/charge-requests', 'provider_token', $now),
        req('Charge Wallet (online)', 'e1f2a3b4-c5d6-4789-bbbb-bbbbbbbbbbbb', 'POST', 'api/provider/wallet/charge', 'provider_token', $now, ['amount' => 100, 'type' => 'online']),
        req('Charge Wallet (bank transfer)', 'f2a3b4c5-d6e7-4890-bccc-cccccccccccc', 'POST', 'api/provider/wallet/charge/bank-transfer', 'provider_token', $now, [
            ['key' => 'amount', 'value' => '250', 'type' => 'text'],
            ['key' => 'image', 'value' => '', 'type' => 'file'],
        ], 'formdata'),
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

function removeWalletFolder(array &$items): void
{
    $items = array_values(array_filter($items, fn ($item) => ($item['name'] ?? '') !== 'Wallet'));
}

$clientIdx = findRootIndex($collection['item'], 'Client');
$providerIdx = findRootIndex($collection['item'], 'Provider');

if ($clientIdx === null || $providerIdx === null) {
    fwrite(STDERR, "Client or Provider folder not found\n");
    exit(1);
}

removeWalletFolder($collection['item'][$clientIdx]['item']);
removeWalletFolder($collection['item'][$providerIdx]['item']);

$collection['item'][$clientIdx]['item'][] = $clientWallet;
$collection['item'][$providerIdx]['item'][] = $providerWallet;
$collection['info']['updatedAt'] = $now;

file_put_contents(
    $path,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Added Wallet folders to Client and Provider in Postman collection\n";
