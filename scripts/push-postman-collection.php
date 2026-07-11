<?php

/**
 * Push local Postman collection to Postman API (requires POSTMAN_API_KEY).
 * Usage: POSTMAN_API_KEY=xxx php scripts/push-postman-collection.php [collection.json]
 */

$collectionPath = dirname(__DIR__) . '/postman/One.postman_collection.json';
$collectionId = '54926212-e39f2c1c-6eea-4f44-8d11-d928212207ff';
$apiKey = null;

foreach ($argv as $i => $arg) {
    if ($i === 0) {
        continue;
    }
    if (str_starts_with($arg, '--api-key=')) {
        $apiKey = trim(substr($arg, strlen('--api-key=')));
        continue;
    }
    if (!str_starts_with($arg, '--') && is_file($arg)) {
        $collectionPath = $arg;
    }
}

$apiKey = $apiKey ?? getenv('POSTMAN_API_KEY') ?: getenv('PMAK') ?: '';

if ($apiKey === '') {
    $envFile = dirname(__DIR__) . '/.env';
    if (is_file($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with($line, 'POSTMAN_API_KEY=')) {
                $apiKey = trim(substr($line, strlen('POSTMAN_API_KEY=')), " \t\"'");
                break;
            }
        }
    }
}

if ($apiKey === '') {
    $keyFile = dirname(__DIR__) . '/.postman/api_key';
    if (is_file($keyFile)) {
        $apiKey = trim(file_get_contents($keyFile));
    }
}

if ($apiKey === '') {
    fwrite(STDERR, "POSTMAN_API_KEY is not set.\n");
    fwrite(STDERR, "Add to .env: POSTMAN_API_KEY=your-key\n");
    fwrite(STDERR, "Or create .postman/api_key (one line, gitignored)\n");
    fwrite(STDERR, "Or run: php scripts/push-postman-collection.php --api-key=PMAK-...\n");
    fwrite(STDERR, "Get a key: https://postman.postman.co/settings/me/api-keys\n");
    exit(1);
}

$collection = json_decode(file_get_contents($collectionPath), true);
if (!$collection) {
    fwrite(STDERR, "Invalid collection JSON\n");
    exit(1);
}

$payload = json_encode(['collection' => $collection], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$ch = curl_init("https://api.getpostman.com/collections/{$collectionId}");
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-Api-Key: ' . $apiKey,
    ],
]);

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP {$code}\n";
echo $response . "\n";

exit($code >= 200 && $code < 300 ? 0 : 1);
