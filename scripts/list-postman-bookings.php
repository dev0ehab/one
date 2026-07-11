<?php

$envFile = dirname(__DIR__) . '/.env';
$key = '';
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with($line, 'POSTMAN_API_KEY=')) {
        $key = trim(substr($line, strlen('POSTMAN_API_KEY=')));
        break;
    }
}

$id = '54926212-e39f2c1c-6eea-4f44-8d11-d928212207ff';
$ch = curl_init("https://api.getpostman.com/collections/{$id}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => ['X-Api-Key: ' . $key],
]);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
$items = $data['collection']['item'] ?? [];

function findFolder(array $items, string $path): ?array
{
    $parts = explode('/', $path);
    $current = $items;
    $node = null;
    foreach ($parts as $part) {
        $found = null;
        foreach ($current as $item) {
            if (($item['name'] ?? '') === $part) {
                $found = $item;
                break;
            }
        }
        if (!$found) {
            return null;
        }
        $node = $found;
        $current = $found['item'] ?? [];
    }

    return $node;
}

foreach (['Client/Bookings', 'Provider/Bookings', 'Client/Bookings/Additional Invoice', 'Provider/Bookings/Additional Invoice'] as $folder) {
    echo "=== {$folder} ===\n";
    $node = findFolder($items, $folder);
    if (!$node) {
        echo "FOLDER NOT FOUND\n\n";
        continue;
    }
    foreach ($node['item'] ?? [] as $req) {
        if (isset($req['item'])) {
            echo "- [folder] {$req['name']}\n";
            continue;
        }
        $hasId = isset($req['id']) ? 'id=yes' : 'id=NO';
        $hasUid = isset($req['uid']) ? 'uid=yes' : 'uid=NO';
        echo "- {$req['name']} ({$hasId}, {$hasUid})\n";
    }
    echo "\n";
}
