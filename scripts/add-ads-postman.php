<?php

/**
 * Adds Provider Packages, Subscription, Ads and Client Ads folders
 * to the existing Postman collection, then pushes to cloud.
 */

$collectionPath = __DIR__ . '/../postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($collectionPath), true);

$baseUrl = '{{base_url}}';

// ─────────────────────────────────────────────────────────────────────────────
// Helper: build a request item
// ─────────────────────────────────────────────────────────────────────────────
function makeItem(string $name, string $method, string $url, ?array $body = null, string $auth = 'bearer', string $description = ''): array
{
    $item = [
        'name'        => $name,
        'description' => $description,
        'request'     => [
            'method' => strtoupper($method),
            'header' => [
                ['key' => 'Accept',       'value' => 'application/json'],
                ['key' => 'Lang',         'value' => 'ar'],
            ],
            'url'    => [
                'raw'  => $url,
                'host' => ['{{base_url}}'],
                'path' => explode('/', ltrim(parse_url($url, PHP_URL_PATH), '/')),
            ],
        ],
    ];

    if ($auth === 'bearer') {
        $item['request']['auth'] = [
            'type'   => 'bearer',
            'bearer' => [['key' => 'token', 'value' => '{{provider_token}}', 'type' => 'string']],
        ];
    } elseif ($auth === 'client') {
        $item['request']['auth'] = [
            'type'   => 'bearer',
            'bearer' => [['key' => 'token', 'value' => '{{client_token}}', 'type' => 'string']],
        ];
    }

    if ($body !== null) {
        if ($body['type'] === 'raw') {
            $item['request']['body'] = [
                'mode'    => 'raw',
                'raw'     => json_encode($body['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'options' => ['raw' => ['language' => 'json']],
            ];
            $item['request']['header'][] = ['key' => 'Content-Type', 'value' => 'application/json'];
        } elseif ($body['type'] === 'formdata') {
            $item['request']['body'] = [
                'mode'      => 'formdata',
                'formdata'  => $body['data'],
            ];
        }
    }

    return $item;
}

// ─────────────────────────────────────────────────────────────────────────────
// Provider → Packages folder
// ─────────────────────────────────────────────────────────────────────────────
$providerPackagesFolder = [
    'name' => 'Packages',
    'description' => 'Provider: list packages and subscribe',
    'item' => [
        makeItem(
            'List Packages',
            'GET',
            "$baseUrl/api/provider/packages",
            null,
            'bearer',
            'List all active subscription packages available for the provider.'
        ),
        makeItem(
            'Subscribe to Package',
            'POST',
            "$baseUrl/api/provider/packages/1/subscribe",
            ['type' => 'raw', 'data' => ['payment_method' => 'wallet']],
            'bearer',
            'Subscribe to a package. Deducts the price from the provider\'s wallet (payment_method=wallet) or returns a payment link (online). Required: active wallet balance >= package price.'
        ),
        makeItem(
            'Current Subscription',
            'GET',
            "$baseUrl/api/provider/subscription",
            null,
            'bearer',
            'Returns the provider\'s current active subscription with remaining ads count and expiry date.'
        ),
    ],
];

// ─────────────────────────────────────────────────────────────────────────────
// Provider → Ads folder
// ─────────────────────────────────────────────────────────────────────────────
$providerAdsFolder = [
    'name' => 'Ads',
    'description' => 'Provider: manage own ads (requires active subscription)',
    'item' => [
        makeItem(
            'List My Ads',
            'GET',
            "$baseUrl/api/provider/ads",
            null,
            'bearer',
            'List all ads submitted by the authenticated provider. Shows status (pending/approved/rejected) and reject_reason if applicable.'
        ),
        makeItem(
            'Create Ad',
            'POST',
            "$baseUrl/api/provider/ads",
            [
                'type' => 'formdata',
                'data' => [
                    ['key' => 'title',         'value' => 'عرض خاص للعملاء الجدد', 'type' => 'text'],
                    ['key' => 'description',   'value' => 'خصم 20% على الحجز الأول لكل عميل جديد', 'type' => 'text'],
                    ['key' => 'start_at',      'value' => date('Y-m-d'), 'type' => 'text', 'description' => 'Offer start date (Y-m-d or datetime), must be today or later'],
                    ['key' => 'price',         'value' => '150', 'type' => 'text', 'description' => 'Ad price (numeric, min 0)'],
                    ['key' => 'images[]',      'src' => '', 'type' => 'file', 'description' => 'Ad image (optional, max 5)'],
                ],
            ],
            'bearer',
            'Create a new ad. Requires an active subscription with remaining ad slots. The ad is submitted with status=pending and must be approved by admin before it becomes visible to clients.'
        ),
        makeItem(
            'Show Ad',
            'GET',
            "$baseUrl/api/provider/ads/1",
            null,
            'bearer',
            'Get details of a specific ad owned by the authenticated provider.'
        ),
        makeItem(
            'Delete Ad',
            'DELETE',
            "$baseUrl/api/provider/ads/1",
            null,
            'bearer',
            'Delete an ad. Soft-deletes the record.'
        ),
    ],
];

// ─────────────────────────────────────────────────────────────────────────────
// Client → Ads folder
// ─────────────────────────────────────────────────────────────────────────────
$clientAdsFolder = [
    'name' => 'Ads',
    'description' => 'Client: browse approved active provider ads',
    'item' => [
        makeItem(
            'Browse Ads',
            'GET',
            "$baseUrl/api/client/ads",
            null,
            'client',
            'Returns all approved active provider ads. Shown on client home feed. No auth required (optional auth).'
        ),
    ],
];

// ─────────────────────────────────────────────────────────────────────────────
// Inject into collection
// ─────────────────────────────────────────────────────────────────────────────
$providerFolderIndex = null;
$clientFolderIndex   = null;

foreach ($collection['item'] as $i => $folder) {
    if (isset($folder['name'])) {
        if ($folder['name'] === 'Provider') $providerFolderIndex = $i;
        if ($folder['name'] === 'Client')   $clientFolderIndex   = $i;
    }
}

// Add Packages + Ads into Provider folder
if ($providerFolderIndex !== null) {
    $existingNames = array_map(fn($f) => $f['name'] ?? '', $collection['item'][$providerFolderIndex]['item'] ?? []);
    if (!in_array('Packages', $existingNames)) {
        $collection['item'][$providerFolderIndex]['item'][] = $providerPackagesFolder;
    }
    if (!in_array('Ads', $existingNames)) {
        $collection['item'][$providerFolderIndex]['item'][] = $providerAdsFolder;
    }
} else {
    // If no top-level Provider folder, append as top-level
    $collection['item'][] = ['name' => 'Provider', 'item' => [$providerPackagesFolder, $providerAdsFolder]];
}

// Add Ads into Client folder
if ($clientFolderIndex !== null) {
    $existingNames = array_map(fn($f) => $f['name'] ?? '', $collection['item'][$clientFolderIndex]['item'] ?? []);
    if (!in_array('Ads', $existingNames)) {
        // Insert at beginning of client folder
        array_unshift($collection['item'][$clientFolderIndex]['item'], $clientAdsFolder);
    }
} else {
    $collection['item'][] = ['name' => 'Client', 'item' => [$clientAdsFolder]];
}

// Save
file_put_contents($collectionPath, json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "Postman collection updated.\n";

echo "Done. Run: php scripts/push-postman-collection.php to push to cloud.\n";
