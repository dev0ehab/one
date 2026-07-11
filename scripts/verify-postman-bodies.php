<?php

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);
$stats = ['total' => 0, 'with_body' => 0, 'empty_raw' => 0, 'no_body' => 0];
$issues = [];

function getUrl(array $req): string
{
    $u = $req['url'] ?? '';
    return is_string($u) ? $u : ($u['raw'] ?? '');
}

function walk(array $items, string $path = ''): void
{
    global $stats, $issues;
    foreach ($items as $item) {
        $name = $item['name'] ?? '';
        $p = $path ? "{$path} > {$name}" : $name;
        if (isset($item['item'])) {
            walk($item['item'], $p);
            continue;
        }
        $req = $item['request'] ?? null;
        if (!$req) {
            continue;
        }
        $method = strtoupper($req['method'] ?? 'GET');
        if (!in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            continue;
        }
        $stats['total']++;
        $url = getUrl($req);
        $body = $req['body'] ?? null;
        if (!$body) {
            $stats['no_body']++;
            $issues[] = "NO_BODY: {$p} | {$method} {$url}";
            continue;
        }
        if (!empty($body['formdata']) || !empty($body['urlencoded'])) {
            $stats['with_body']++;
            continue;
        }
        $raw = trim($body['raw'] ?? '');
        if ($raw === '') {
            $stats['empty_raw']++;
            $issues[] = "EMPTY: {$p} | {$method} {$url}";
            continue;
        }
        $stats['with_body']++;
    }
}

walk($collection['item'] ?? []);
echo json_encode($stats, JSON_PRETTY_PRINT) . "\n";
foreach ($issues as $i) {
    echo $i . "\n";
}
exit(count($issues) > 0 ? 1 : 0);
