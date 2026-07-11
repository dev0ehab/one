<?php

$base = 'http://one.test';
$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, 'APP_URL=')) {
            $base = rtrim(trim(substr($line, 8), '"\''), '/');
            break;
        }
    }
}

$login = static function (string $path, string $phone) use ($base): string {
    $ch = curl_init($base . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json', 'Content-Type: application/json', 'lang: ar'],
        CURLOPT_POSTFIELDS     => json_encode([
            'country_code'     => '966',
            'phone'            => $phone,
            'password'         => 'Password123',
            'device_id'        => 'postman-device-1',
            'device_type'      => 'android',
            'mac_address'      => '00:11:22:33:44:55',
            'preferred_locale' => 'ar',
        ]),
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $json = json_decode($body ?: '', true);

    if (empty($json['data']['token'])) {
        echo "login HTTP {$code}: " . substr($body ?: 'no body', 0, 120) . PHP_EOL;
    }

    return $json['data']['token'] ?? '';
};

sleep(2);
$token = $login('/api/client/login', '512345678');
echo 'login: ' . ($token ? 'ok' : 'fail') . PHP_EOL;

sleep(2);
$ch = curl_init($base . '/api/client/complains');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'lang: ar', 'Authorization: Bearer ' . $token],
]);
$body = curl_exec($ch);
curl_close($ch);
$json = json_decode($body ?: '', true);
echo 'complains: ' . ($json['key'] ?? '?') . ' — ' . ($json['msg'] ?? '') . PHP_EOL;

exit(($json['key'] ?? '') === 'success' ? 0 : 1);
