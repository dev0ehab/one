<?php

/**
 * Adds Provider Reviews and Provider Ads requests to the Client → Providers folder.
 */

$collectionPath = __DIR__ . '/../postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($collectionPath), true);

function providerGetItem(string $name, string $suffix, string $description): array
{
    $segments = array_merge(['1'], $suffix !== '' ? [$suffix] : []);
    $path = array_merge(['api', 'client', 'providers'], $segments);
    $urlPath = 'providers/1' . ($suffix !== '' ? '/' . $suffix : '');

    return [
        'name'        => $name,
        'description' => $description,
        'request'     => [
            'method' => 'GET',
            'header' => [
                ['key' => 'Accept', 'value' => 'application/json'],
                ['key' => 'lang', 'value' => 'ar'],
            ],
            'url'    => [
                'raw'   => "{{base_url}}/api/client/{$urlPath}?paginate=10",
                'host'  => ['{{base_url}}'],
                'path'  => $path,
                'query' => [
                    [
                        'key'         => 'paginate',
                        'value'       => '10',
                        'description' => 'Items per page (default 10)',
                        'disabled'    => false,
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
                        "pm.test('Response is JSON API envelope', function () {",
                        "    const json = pm.response.json();",
                        "    pm.expect(json).to.have.property('key');",
                        "    pm.expect(json).to.have.property('msg');",
                        "});",
                    ],
                ],
            ],
        ],
    ];
}

$newItems = [
    providerGetItem(
        'Provider Reviews',
        'reviews',
        "## Provider Reviews\n\n**Endpoint:** `GET /api/client/providers/{id}/reviews`\n\nPaginated list of client ratings for a provider profile screen."
    ),
    providerGetItem(
        'Provider Ads',
        'ads',
        "## Provider Ads\n\n**Endpoint:** `GET /api/client/providers/{id}/ads`\n\nPaginated list of approved active ads for a provider profile screen."
    ),
];

function injectIntoProvidersFolder(array &$collection, array $newItems): bool
{
    foreach ($collection['item'] as &$folder) {
        if (($folder['name'] ?? '') !== 'Client') {
            continue;
        }
        foreach ($folder['item'] as &$sub) {
            if (($sub['name'] ?? '') !== 'Providers') {
                continue;
            }
            $existing = array_column($sub['item'] ?? [], 'name');
            foreach ($newItems as $item) {
                if (!in_array($item['name'], $existing, true)) {
                    $sub['item'][] = $item;
                }
            }
            return true;
        }
    }

    return false;
}

if (!injectIntoProvidersFolder($collection, $newItems)) {
    fwrite(STDERR, "Could not find Client → Providers folder.\n");
    exit(1);
}

file_put_contents(
    $collectionPath,
    json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
);

echo "Added Provider Reviews and Provider Ads to postman/One.postman_collection.json\n";
