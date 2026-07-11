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
$response = json_decode(curl_exec($ch), true);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code !== 200) {
    echo "Cloud fetch failed HTTP {$code}\n";
    exit(1);
}

foreach ($response['collection']['item'] ?? [] as $folder) {
    if (($folder['name'] ?? '') !== 'Global') {
        continue;
    }
    echo "Global on cloud: YES\n";
    echo 'Subfolders: ' . count($folder['item'] ?? []) . "\n";
    foreach ($folder['item'] ?? [] as $sub) {
        echo '  - ' . ($sub['name'] ?? '') . ' (' . count($sub['item'] ?? []) . " requests)\n";
    }
    exit(0);
}

echo "Global on cloud: NO\n";
exit(1);
