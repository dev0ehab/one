<?php

/**
 * Rebuild Postman "Global" folder — public APIs (no Bearer token).
 * Usage: php scripts/add-postman-global-folder.php [collection.json]
 */

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$owner = '54926212';
$now = gmdate('Y-m-d\TH:i:s.000\Z');

function uid(string $id): string
{
    return "54926212-{$id}";
}

function publicHeaders(bool $json = false): array
{
    $h = [
        ['key' => 'Accept', 'value' => 'application/json'],
        ['key' => 'lang', 'value' => 'ar'],
    ];
    if ($json) {
        $h[] = ['key' => 'Content-Type', 'value' => 'application/json'];
    }

    return $h;
}

function url(string $path, ?string $query = null): array
{
    $path = ltrim($path, '/');
    $raw = "{{base_url}}/{$path}";
    if ($query) {
        $raw .= '?' . $query;
    }

    return [
        'raw'  => $raw,
        'host' => ['{{base_url}}'],
        'path' => explode('/', $path),
    ];
}

function getReq(string $name, string $id, string $path, ?string $query = null): array
{
    global $now;

    return [
        'name'      => $name,
        'id'        => $id,
        'uid'       => uid($id),
        'createdAt' => $now,
        'updatedAt' => $now,
        'request'   => [
            'method'  => 'GET',
            'header'  => publicHeaders(),
            'url'     => url($path, $query),
        ],
        'response' => [],
    ];
}

function postJson(string $name, string $id, string $path, array $body): array
{
    global $now;

    return [
        'name'      => $name,
        'id'        => $id,
        'uid'       => uid($id),
        'createdAt' => $now,
        'updatedAt' => $now,
        'request'   => [
            'method'  => 'POST',
            'header'  => publicHeaders(true),
            'body'    => [
                'mode'    => 'raw',
                'raw'     => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'options' => ['raw' => ['language' => 'json']],
            ],
            'url'     => url($path),
        ],
        'response' => [],
    ];
}

function folder(string $name, string $id, string $description, array $items): array
{
    global $now;

    return [
        'name'        => $name,
        'id'          => $id,
        'uid'         => uid($id),
        'description' => $description,
        'createdAt'   => $now,
        'updatedAt'   => $now,
        'item'        => $items,
    ];
}

$global = folder(
    'Global',
    'g0000000-0000-4000-8000-000000000001',
    'API عامة بدون توكن — optional.auth في routes/api.php + client/provider public',
    [
        folder('Settings', 'g0000001-0000-4000-8000-000000000002', 'إعدادات التطبيق', [
            getReq('Get Settings', 'g1000001-0000-4000-8000-000000000001', 'api/settings'),
            getReq('Is Production', 'g1000002-0000-4000-8000-000000000002', 'api/is-production'),
            getReq('Get Main', 'g1000003-0000-4000-8000-000000000003', 'api/main'),
            getReq('Who Is Us', 'g1000004-0000-4000-8000-000000000004', 'api/who-is-us'),
            getReq('Get Socials', 'g1000005-0000-4000-8000-000000000005', 'api/socials'),
        ]),
        folder('Static Pages (root)', 'g0000002-0000-4000-8000-000000000003', '/api/about|terms|privacy — بدون client/', [
            getReq('About Us', 'g2000001-0000-4000-8000-000000000001', 'api/about'),
            getReq('Terms', 'g2000002-0000-4000-8000-000000000002', 'api/terms'),
            getReq('Privacy', 'g2000003-0000-4000-8000-000000000003', 'api/privacy'),
        ]),
        folder('Countries & Cities', 'g0000003-0000-4000-8000-000000000004', 'دول ومدن', [
            getReq('List Countries', 'g3000001-0000-4000-8000-000000000001', 'api/countries'),
            getReq('List Cities', 'g3000002-0000-4000-8000-000000000002', 'api/cities', 'country_id=1'),
            getReq('Countries With Cities', 'g3000003-0000-4000-8000-000000000003', 'api/countries-with-cities'),
            getReq('Cities By Country', 'g3000004-0000-4000-8000-000000000004', 'api/country/1/cities'),
        ]),
        folder('Contact', 'g0000004-0000-4000-8000-000000000005', 'تواصل', [
            getReq('Get Contact Page', 'g4000001-0000-4000-8000-000000000001', 'api/contact'),
            postJson('Submit Contact', 'g4000002-0000-4000-8000-000000000002', 'api/contact/store', [
                'name'         => 'أحمد محمد',
                'country_code' => '966',
                'phone'        => '512345678',
                'message'      => 'نص رسالة التواصل',
            ]),
        ]),
        folder('Client — Public', 'g0000005-0000-4000-8000-000000000006', 'optional.auth — بدون تسجيل دخول', [
            getReq('App Intro', 'g5000001-0000-4000-8000-000000000001', 'api/client/app-intro'),
            getReq('About', 'g5000002-0000-4000-8000-000000000002', 'api/client/about'),
            getReq('Terms', 'g5000003-0000-4000-8000-000000000003', 'api/client/terms'),
            getReq('Privacy', 'g5000004-0000-4000-8000-000000000004', 'api/client/privacy'),
            getReq('Socials', 'g5000005-0000-4000-8000-000000000005', 'api/client/socials'),
            getReq('List Providers', 'g5000006-0000-4000-8000-000000000006', 'api/client/providers', 'paginate=15'),
            getReq('Providers Map', 'g5000007-0000-4000-8000-000000000007', 'api/client/providers/map', 'lat=24.7136&lng=46.6753&radius=20'),
            getReq('Provider Categories', 'g5000008-0000-4000-8000-000000000008', 'api/client/providers/categories'),
            getReq('Provider Details', 'g5000009-0000-4000-8000-000000000009', 'api/client/providers/1'),
        ]),
        folder('Provider — Public', 'g0000006-0000-4000-8000-000000000007', 'قبل تسجيل الدخول', [
            getReq('App Intro', 'g6000001-0000-4000-8000-000000000001', 'api/provider/app-intro'),
            getReq('Terms', 'g6000002-0000-4000-8000-000000000002', 'api/provider/terms'),
        ]),
    ]
);

$found = false;
foreach ($collection['item'] as $i => $item) {
    if (($item['name'] ?? '') === 'Global') {
        $collection['item'][$i] = $global;
        $found = true;
        break;
    }
}

if (!$found) {
    $collection['item'][] = $global;
}

file_put_contents(
    $path,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Rebuilt Global folder (" . count($global['item']) . " subfolders)\n";
