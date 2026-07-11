<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$stats = [
    'requests' => 0,
    'with_response' => 0,
    'without_response' => [],
    'with_auth' => 0,
    'needs_auth_no_header' => [],
    'empty_body_post' => [],
];

function getUrl(array $req): string
{
    $u = $req['url'] ?? '';

    return is_string($u) ? $u : ($u['raw'] ?? '');
}

function needsAuth(string $url): bool
{
    if (!str_contains($url, '/api/')) {
        return false;
    }
    $public = [
        '/login', '/register', '/activate', '/resend-code', '/password/',
        '/app-intro', '/terms', '/settings', '/is-production', '/main',
        '/countries', '/cities', '/contact', '/who-is-us', '/about', '/privacy', '/socials',
    ];
    foreach ($public as $p) {
        if (str_contains($url, $p)) {
            return false;
        }
    }
    if (preg_match('#/api/client/(providers|ads)(/|$|\?)#', $url) && !str_contains($url, '/favorites')) {
        return false;
    }

    return str_contains($url, '/api/client/') || str_contains($url, '/api/provider/');
}

function walk(array $items, string $path, array &$stats): void
{
    foreach ($items as $item) {
        $name = $item['name'] ?? '';
        $p = $path ? "{$path} > {$name}" : $name;
        if (isset($item['item'])) {
            walk($item['item'], $p, $stats);
            continue;
        }
        if (!isset($item['request'])) {
            continue;
        }
        $stats['requests']++;
        $req = $item['request'];
        $url = getUrl($req);
        if (!empty($item['response'])) {
            $stats['with_response']++;
        } else {
            $stats['without_response'][] = "{$req['method']} {$url} | {$p}";
        }
        $hasAuthHeader = false;
        foreach ($req['header'] ?? [] as $h) {
            if (strtolower($h['key'] ?? '') === 'authorization' && str_contains($h['value'] ?? '', 'token')) {
                $hasAuthHeader = true;
            }
        }
        if (($req['auth']['type'] ?? '') === 'bearer') {
            $hasAuthHeader = true;
        }
        if ($hasAuthHeader) {
            $stats['with_auth']++;
        } elseif (needsAuth($url)) {
            $stats['needs_auth_no_header'][] = "{$req['method']} {$url} | {$p}";
        }
        $method = strtoupper($req['method'] ?? 'GET');
        if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $body = $req['body'] ?? [];
            $raw = trim($body['raw'] ?? '');
            if (empty($body['formdata']) && ($raw === '' || $raw === '{}')) {
                $stats['empty_body_post'][] = "{$method} {$url} | {$p}";
            }
        }
    }
}

walk($collection['item'] ?? [], '', $stats);
echo json_encode([
    'requests' => $stats['requests'],
    'with_response' => $stats['with_response'],
    'without_response_count' => count($stats['without_response']),
    'needs_auth_no_header_count' => count($stats['needs_auth_no_header']),
    'empty_body_post_count' => count($stats['empty_body_post']),
], JSON_PRETTY_PRINT) . "\n";
echo "\n--- Without response ---\n";
foreach ($stats['without_response'] as $line) {
    echo $line . "\n";
}
echo "\n--- Needs auth, no header ---\n";
foreach ($stats['needs_auth_no_header'] as $line) {
    echo $line . "\n";
}
echo "\n--- Empty POST body ---\n";
foreach ($stats['empty_body_post'] as $line) {
    echo $line . "\n";
}
