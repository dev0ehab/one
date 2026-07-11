<?php

$base = 'http://one.test';
foreach (file(dirname(__DIR__) . '/.env') as $line) {
    $line = trim($line);
    if (str_starts_with($line, 'APP_URL=')) {
        $base = rtrim(trim(substr($line, 8), '"\''), '/');
    }
    if (str_starts_with($line, 'APP_ENV=')) {
        echo 'APP_ENV = ' . trim(substr($line, 8), '"\'') . PHP_EOL;
    }
}
echo 'APP_URL = ' . $base . PHP_EOL;

$get = static function (string $url, array $headers = []): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array_merge(['Accept: application/json', 'lang: ar'], $headers),
        CURLOPT_TIMEOUT         => 10,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_HEADER          => true,
    ]);
    $raw  = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $hlen = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    $head = substr($raw ?: '', 0, $hlen);
    $body = substr($raw ?: '', $hlen);
    preg_match('/X-RateLimit-Remaining:\s*(\d+)/i', $head, $rem);
    preg_match('/X-RateLimit-Limit:\s*(\d+)/i', $head, $lim);
    return [
        'code'      => $code,
        'body'      => $body,
        'remaining' => $rem[1] ?? '?',
        'limit'     => $lim[1] ?? '?',
    ];
};

$post = static function (string $url, array $data, array $headers = []): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => json_encode($data),
        CURLOPT_HTTPHEADER      => array_merge(['Accept: application/json', 'Content-Type: application/json', 'lang: ar'], $headers),
        CURLOPT_TIMEOUT         => 10,
        CURLOPT_HEADER          => true,
    ]);
    $raw  = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $hlen = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    $head = substr($raw ?: '', 0, $hlen);
    $body = substr($raw ?: '', $hlen);
    preg_match('/X-RateLimit-Remaining:\s*(\d+)/i', $head, $rem);
    preg_match('/X-RateLimit-Limit:\s*(\d+)/i', $head, $lim);
    return [
        'code'      => $code,
        'json'      => json_decode($body, true) ?? [],
        'remaining' => $rem[1] ?? '?',
        'limit'     => $lim[1] ?? '?',
    ];
};

// 1. No header - see if throttle active
echo PHP_EOL . '--- Test 1: /api/settings WITHOUT X-ONE-Postman ---' . PHP_EOL;
$r = $get($base . '/api/settings');
$j = json_decode($r['body'], true) ?? [];
echo 'HTTP ' . $r['code'] . ' | key=' . ($j['key'] ?? '?') . ' | limit=' . $r['limit'] . ' remaining=' . $r['remaining'] . PHP_EOL;

// 2. With header - verify bypass
echo PHP_EOL . '--- Test 2: /api/settings WITH X-ONE-Postman ---' . PHP_EOL;
$r = $get($base . '/api/settings', ['X-ONE-Postman: 1']);
$j = json_decode($r['body'], true) ?? [];
echo 'HTTP ' . $r['code'] . ' | key=' . ($j['key'] ?? '?') . ' | limit=' . $r['limit'] . ' remaining=' . $r['remaining'] . PHP_EOL;

// 3. Login attempt - see exact error
echo PHP_EOL . '--- Test 3: Client login ---' . PHP_EOL;
$r = $post($base . '/api/client/login', [
    'country_code'     => '966',
    'phone'            => '512345678',
    'password'         => 'Password123',
    'device_type'      => 'android',
    'device_id'        => 'postman-device-1',
    'mac_address'      => '00:11:22:33:44:55',
    'preferred_locale' => 'ar',
], ['X-ONE-Postman: 1']);
echo 'HTTP ' . $r['code'] . ' | key=' . ($r['json']['key'] ?? '?') . ' | msg=' . ($r['json']['msg'] ?? '?') . PHP_EOL;
if (!empty($r['json']['errors'])) {
    foreach ($r['json']['errors'] as $field => $msgs) {
        echo "  error[$field]: " . (is_array($msgs) ? implode(', ', $msgs) : $msgs) . PHP_EOL;
    }
}

// 4. Provider login
echo PHP_EOL . '--- Test 4: Provider login ---' . PHP_EOL;
$r = $post($base . '/api/provider/login', [
    'country_code'     => '966',
    'phone'            => '512345679',
    'password'         => 'Password123',
    'device_type'      => 'android',
    'device_id'        => 'postman-device-provider',
    'mac_address'      => '00:11:22:33:44:56',
    'preferred_locale' => 'ar',
], ['X-ONE-Postman: 1']);
echo 'HTTP ' . $r['code'] . ' | key=' . ($r['json']['key'] ?? '?') . ' | msg=' . ($r['json']['msg'] ?? '?') . PHP_EOL;
if (!empty($r['json']['errors'])) {
    foreach ($r['json']['errors'] as $field => $msgs) {
        echo "  error[$field]: " . (is_array($msgs) ? implode(', ', $msgs) : $msgs) . PHP_EOL;
    }
}

// 5. Blast 70 rapid requests WITHOUT header - reproduce rate limit
echo PHP_EOL . '--- Test 5: 70 rapid requests WITHOUT bypass header (reproduce issue) ---' . PHP_EOL;
$limited = 0;
for ($i = 0; $i < 70; $i++) {
    $r = $get($base . '/api/settings');
    $j = json_decode($r['body'], true) ?? [];
    if (($j['key'] ?? '') === 'fail' && str_contains(strtolower($j['msg'] ?? ''), 'too many')) {
        $limited++;
        if ($limited === 1) {
            echo '  First rate-limit hit at request #' . ($i + 1) . ' (remaining was ' . $r['remaining'] . ')' . PHP_EOL;
        }
    }
}
echo ($limited > 0 ? "REPRODUCED: $limited rate-limited responses (throttle is still active without bypass)" : 'OK: no rate limits without bypass (throttle disabled globally)') . PHP_EOL;
