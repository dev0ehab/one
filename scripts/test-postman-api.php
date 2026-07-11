<?php

$envFile = dirname(__DIR__) . '/.env';
$key = '';
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with($line, 'POSTMAN_API_KEY=')) {
        $key = trim(substr($line, strlen('POSTMAN_API_KEY=')));
        break;
    }
}

if ($key === '') {
    fwrite(STDERR, "POSTMAN_API_KEY not found in .env\n");
    exit(1);
}

$id = '54926212-e39f2c1c-6eea-4f44-8d11-d928212207ff';
$ch = curl_init("https://api.getpostman.com/collections/{$id}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => ['X-Api-Key: ' . $key],
    CURLOPT_TIMEOUT        => 60,
]);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP {$code}\n";
if ($error) {
    echo "curl error: {$error}\n";
    exit(1);
}

if ($code !== 200) {
    echo substr((string) $response, 0, 500) . "\n";
    exit(1);
}

$data = json_decode($response, true);
$names = [];
$walk = function (array $items) use (&$walk, &$names) {
    foreach ($items as $item) {
        if (isset($item['request'])) {
            $names[] = $item['name'] ?? '';
        }
        if (!empty($item['item'])) {
            $walk($item['item']);
        }
    }
};
$walk($data['collection']['item'] ?? []);

$check = [
    'Accept Additional Invoice',
    'Reject Additional Invoice',
    'Pay Additional Invoice (wallet)',
    'Pay Additional Invoice (online)',
    'Issue Additional Invoice',
];
foreach ($check as $name) {
    echo (in_array($name, $names, true) ? '[OK] ' : '[MISSING] ') . $name . "\n";
}

$issueBody = '';
$rejectHasReason = false;
$find = function (array $items) use (&$find, &$issueBody, &$rejectHasReason) {
    foreach ($items as $item) {
        if (($item['name'] ?? '') === 'Issue Additional Invoice') {
            $issueBody = $item['request']['body']['raw'] ?? '';
        }
        if (($item['name'] ?? '') === 'Reject Additional Invoice') {
            $rejectHasReason = str_contains($item['request']['body']['raw'] ?? '', 'reason');
        }
        if (!empty($item['item'])) {
            $find($item['item']);
        }
    }
};
$find($data['collection']['item'] ?? []);

echo (str_contains($issueBody, 'reason') ? '[OK] ' : '[WARN] ') . "Issue body includes reason\n";
echo ($rejectHasReason ? '[OK] ' : '[WARN] ') . "Reject body includes reason\n";
