<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $id): string
{
    return "54926212-{$id}";
}

function publicJsonHeaders(): array
{
    return [
        ['key' => 'Accept', 'value' => 'application/json'],
        ['key' => 'Content-Type', 'value' => 'application/json'],
        ['key' => 'lang', 'value' => 'ar'],
    ];
}

function authJsonHeaders(string $tokenVar): array
{
    return array_merge(publicJsonHeaders(), [
        ['key' => 'Authorization', 'value' => "Bearer {{{$tokenVar}}}"],
    ]);
}

function makeRequest(
    string $name,
    string $id,
    string $method,
    string $urlPath,
    array $headers,
    ?array $body = null
): array {
    global $now;

    $req = [
        'name'      => $name,
        'id'        => $id,
        'uid'       => uid($id),
        'createdAt' => $now,
        'updatedAt' => $now,
        'request'   => [
            'method' => $method,
            'header' => $headers,
            'url'    => [
                'raw'  => "{{base_url}}/{$urlPath}",
                'host' => ['{{base_url}}'],
                'path' => explode('/', $urlPath),
            ],
        ],
        'response' => [],
    ];

    if ($body !== null) {
        $req['request']['body'] = [
            'mode'    => 'raw',
            'raw'     => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'options' => ['raw' => ['language' => 'json']],
        ];
    }

    return $req;
}

$forgetItems = [
    makeRequest(
        'Forget Password (send code)',
        'p7f8a9b0-c1d2-4e3f-a111-111111111111',
        'POST',
        'api/provider/password/forget',
        publicJsonHeaders(),
        ['country_code' => '966', 'phone' => '512345679']
    ),
    makeRequest(
        'Forget Password (check code)',
        'p8f9a0b1-c2d3-4f4a-b222-222222222222',
        'POST',
        'api/provider/password/check-code',
        publicJsonHeaders(),
        ['country_code' => '966', 'phone' => '512345679', 'code' => '123456']
    ),
    makeRequest(
        'Forget Password (reset)',
        'p9a0b1c2-d3e4-4f5b-c333-333333333333',
        'POST',
        'api/provider/password/reset',
        publicJsonHeaders(),
        [
            'country_code' => '966',
            'phone'        => '512345679',
            'code'         => '123456',
            'password'     => 'NewPass123',
        ]
    ),
];

$updatePassword = makeRequest(
    'Update Password',
    'p0a1b2c3-d4e5-4f6c-d444-444444444444',
    'PATCH',
    'api/provider/update-password',
    authJsonHeaders('provider_token'),
    ['old_password' => 'Password123', 'password' => 'NewPass456']
);

function insertAfterLogin(array &$items, array $toInsert): bool
{
    foreach ($items as $i => $item) {
        if (($item['name'] ?? '') === 'Login') {
            array_splice($items, $i + 1, 0, $toInsert);
            return true;
        }
    }

    return false;
}

function removeByNames(array &$items, array $names): void
{
    $items = array_values(array_filter($items, fn ($item) => !in_array($item['name'] ?? '', $names, true)));
}

foreach ($collection['item'] as &$root) {
    if (($root['name'] ?? '') !== 'Provider') {
        continue;
    }

    foreach ($root['item'] as &$folder) {
        if (($folder['name'] ?? '') === 'Auth') {
            removeByNames($folder['item'], [
                'Forget Password (send code)',
                'Forget Password (check code)',
                'Forget Password (reset)',
            ]);
            if (!insertAfterLogin($folder['item'], $forgetItems)) {
                $folder['item'] = array_merge($folder['item'], $forgetItems);
            }
        }

        if (($folder['name'] ?? '') === 'Profile') {
            removeByNames($folder['item'], ['Update Password']);
            $inserted = false;
            foreach ($folder['item'] as $pi => $pitem) {
                if (($pitem['name'] ?? '') === 'Get Profile') {
                    array_splice($folder['item'], $pi + 1, 0, [$updatePassword]);
                    $inserted = true;
                    break;
                }
            }
            if (!$inserted) {
                array_unshift($folder['item'], $updatePassword);
            }
        }
    }
}
unset($root, $folder);

file_put_contents(
    $path,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Added Provider forget-password (3) + Update Password\n";
