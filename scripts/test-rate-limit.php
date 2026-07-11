<?php

$base = 'http://one.test';
foreach (file(dirname(__DIR__) . '/.env') as $line) {
    if (str_starts_with(trim($line), 'APP_URL=')) {
        $base = rtrim(trim(substr($line, 8), '"\''), '/');
        break;
    }
}

$hits = 0;
$limited = 0;

for ($i = 0; $i < 15; $i++) {
    $ch = curl_init($base . '/api/settings');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-ONE-Postman: 1', 'lang: ar'],
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($body ?: '', true);
    $hits++;
    if (($json['key'] ?? '') === 'fail' && str_contains(strtolower($json['msg'] ?? ''), 'too many')) {
        $limited++;
    }
}

echo "15 rapid requests: {$hits} sent, {$limited} rate-limited\n";
exit($limited > 0 ? 1 : 0);
