<?php

/**
 * Complete Postman collection: auto-login prerequest, fix URLs, bodies, saved examples.
 *
 * Usage:
 *   php scripts/complete-postman-collection.php [collection.json] [--capture]
 */

declare(strict_types=1);

$collectionPath = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$captureExamples = in_array('--capture', $argv, true);

if (!is_file($collectionPath)) {
    fwrite(STDERR, "Collection not found: {$collectionPath}\n");
    exit(1);
}

passthru(
    PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/audit-fix-postman-bodies.php') . ' ' . escapeshellarg($collectionPath),
    $auditCode
);

$collection = json_decode(file_get_contents($collectionPath), true, 512, JSON_THROW_ON_ERROR);

$stats = [
    'urls_fixed' => 0,
    'auth_headers' => 0,
    'examples_added' => 0,
    'tests_deduped' => 0,
];

$baseUrl = rtrim(envValue('APP_URL', 'http://one.test'), '/');
$tokens = $captureExamples ? captureAuthTokens($baseUrl) : ['client_token' => '', 'provider_token' => ''];

require_once __DIR__ . '/postman-auth-events.php';

mergePostmanCollectionAuth($collection);

processItems(
    $collection['item'],
    $baseUrl,
    $tokens,
    staticExampleBodies(),
    $stats,
    $captureExamples
);

file_put_contents(
    $collectionPath,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "URLs fixed: {$stats['urls_fixed']}, auth headers: {$stats['auth_headers']}, examples added: {$stats['examples_added']}, tests deduped: {$stats['tests_deduped']}\n";
echo "Written: {$collectionPath}\n";

if ($captureExamples) {
    passthru(
        PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/enrich-postman-collection.php')
        . ' ' . escapeshellarg($collectionPath) . ' --capture',
        $captureCode
    );
    $collection = json_decode(file_get_contents($collectionPath), true, 512, JSON_THROW_ON_ERROR);
    mergePostmanCollectionAuth($collection);
    $collection['info']['description'] .= "\n\nRe-capture: `php scripts/complete-postman-collection.php --capture`";
    file_put_contents(
        $collectionPath,
        json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
}

exit(0);

function envValue(string $key, string $default = ''): string
{
    $envFile = dirname(__DIR__) . '/.env';
    if (!is_file($envFile)) {
        return $default;
    }
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, $key . '=')) {
            return trim(substr($line, strlen($key) + 1), " \t\"'");
        }
    }

    return $default;
}

function endpointKey(string $method, string $path): string
{
    $path = '/' . trim($path, '/');
    if (!str_starts_with($path, '/api')) {
        $path = '/api' . ($path === '/' ? '' : $path);
    }
    $path = preg_replace('#/\d+#', '/{id}', $path) ?? $path;

    return strtoupper($method) . ' ' . $path;
}

function pathFromPostmanUrl(array $url): string
{
    $parts = $url['path'] ?? [];
    $parts = array_values(array_filter($parts, static fn ($p) => !str_contains((string) $p, '{{')));
    $path = '/' . implode('/', $parts);

    return preg_replace(['#/\d+#', '#/\{\{[^}]+\}\}#'], '/{id}', $path) ?? $path;
}

function staticExampleBodies(): array
{
    return [
        'GET /api/client/ads' => [
            'key' => 'success',
            'msg' => 'تم بنجاح',
            'data' => [['id' => 1, 'title' => 'عرض خاص', 'price' => 150, 'status' => 'approved']],
        ],
        'POST /api/client/register' => [
            'key' => 'success',
            'msg' => 'تم التسجيل بنجاح',
            'data' => ['phone' => '512345678', 'need_activation' => true],
        ],
        'POST /api/client/resend-code' => ['key' => 'success', 'msg' => 'تم إرسال الرمز'],
        'PUT /api/client/activate' => [
            'key' => 'success',
            'msg' => 'تم التفعيل',
            'data' => ['token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.example'],
        ],
        'GET /api/client/providers/{id}/reviews' => [
            'key' => 'success',
            'data' => [['id' => 1, 'rate' => 5, 'comment' => 'خدمة ممتازة']],
        ],
        'GET /api/client/providers/{id}/ads' => [
            'key' => 'success',
            'data' => [['id' => 1, 'title' => 'عرض خاص', 'price' => 150]],
        ],
        'GET /api/client/providers/{id}/booking-slots' => [
            'key' => 'success',
            'data' => ['dates' => [['date' => '2026-06-01', 'slots' => [['time' => '14:00', 'available' => true]]]]],
        ],
        'DELETE /api/client/delete-account' => ['key' => 'success', 'msg' => 'تم حذف الحساب'],
        'POST /api/provider/register' => ['key' => 'success', 'msg' => 'تم التسجيل', 'data' => ['need_activation' => true]],
        'POST /api/provider/resend-code' => ['key' => 'success', 'msg' => 'تم إرسال الرمز'],
        'PUT /api/provider/activate' => [
            'key' => 'success',
            'data' => ['token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.provider'],
        ],
        'GET /api/provider/booking-service' => [
            'key' => 'success',
            'data' => ['has_booking_service' => true, 'service_duration_minutes' => 30, 'monthly_fee' => 100],
        ],
        'POST /api/provider/booking-service/subscribe' => ['key' => 'success', 'msg' => 'تم الاشتراك'],
        'PATCH /api/provider/booking-service' => ['key' => 'success', 'msg' => 'تم التحديث'],
        'GET /api/provider/work-days' => ['key' => 'success', 'data' => ['weekly_schedule' => []]],
        'PUT /api/provider/work-days/update-work-days' => ['key' => 'success', 'msg' => 'تم تحديث أيام العمل'],
        'GET /api/provider/packages' => [
            'key' => 'success',
            'data' => [['id' => 1, 'name' => 'باقة أساسية', 'ads_count' => 5, 'price' => 200]],
        ],
        'POST /api/provider/packages/{id}/subscribe' => ['key' => 'success', 'msg' => 'تم الاشتراك في الباقة'],
        'GET /api/provider/subscription' => [
            'key' => 'success',
            'data' => ['package_id' => 1, 'remaining_ads' => 3, 'expires_at' => '2026-06-24'],
        ],
        'GET /api/provider/ads' => [
            'key' => 'success',
            'data' => [['id' => 1, 'title' => 'عرض خاص', 'status' => 'approved']],
        ],
        'POST /api/provider/ads' => [
            'key' => 'success',
            'data' => ['id' => 1, 'title' => 'عرض خاص للعملاء الجدد', 'status' => 'pending'],
        ],
        'GET /api/provider/ads/{id}' => [
            'key' => 'success',
            'data' => ['id' => 1, 'title' => 'عرض خاص', 'status' => 'approved'],
        ],
        'DELETE /api/provider/ads/{id}' => ['key' => 'success', 'msg' => 'تم الحذف'],
    ];
}

function normalizeUrlPath(array &$url): bool
{
    $changed = false;
    $path = $url['path'] ?? [];
    $clean = [];
    foreach ($path as $segment) {
        if ($segment === '{{base_url}}' || $segment === '{{base_url}}/') {
            $changed = true;
            continue;
        }
        $clean[] = $segment;
    }
    if ($clean !== $path) {
        $url['path'] = $clean;
        $changed = true;
    }
    if (($url['host'] ?? []) !== ['{{base_url}}']) {
        $url['host'] = ['{{base_url}}'];
        $changed = true;
    }
    $pathStr = '/' . implode('/', $clean);
    $query = '';
    if (!empty($url['query'])) {
        $parts = [];
        foreach ($url['query'] as $q) {
            if (!empty($q['disabled'])) {
                continue;
            }
            $parts[] = rawurlencode($q['key']) . '=' . rawurlencode((string) ($q['value'] ?? ''));
        }
        if ($parts !== []) {
            $query = '?' . implode('&', $parts);
        }
    }
    $expectedRaw = '{{base_url}}' . $pathStr . $query;
    if (($url['raw'] ?? '') !== $expectedRaw) {
        $url['raw'] = $expectedRaw;
        $changed = true;
    }

    return $changed;
}

function ensureAuthHeader(array &$request, string $endpointKey): bool
{
    $isAuthEndpoint = (bool) preg_match('#/(login|register|activate|resend-code|password/)#', $endpointKey);
    $needsClient = str_contains($endpointKey, '/api/client/') && !$isAuthEndpoint;
    $needsProvider = str_contains($endpointKey, '/api/provider/') && !$isAuthEndpoint;
    if (!$needsClient && !$needsProvider) {
        return false;
    }

    $tokenVar = $needsProvider ? '{{provider_token}}' : '{{client_token}}';
    $headers = $request['header'] ?? [];
    $hasAuth = false;
    foreach ($headers as $h) {
        if (strtolower($h['key'] ?? '') === 'authorization') {
            $hasAuth = true;
        }
    }
    if (!$hasAuth) {
        $headers[] = ['key' => 'Authorization', 'value' => 'Bearer ' . $tokenVar];
        $request['header'] = $headers;
    }
    $request['auth'] = [
        'type'   => 'bearer',
        'bearer' => [['key' => 'token', 'value' => $tokenVar, 'type' => 'string']],
    ];

    return true;
}

function dedupeTestScript(array &$item, array &$stats): void
{
    foreach ($item['event'] ?? [] as &$event) {
        if (($event['listen'] ?? '') !== 'test') {
            continue;
        }
        $exec = $event['script']['exec'] ?? [];
        if (!is_array($exec)) {
            continue;
        }
        $seen = [];
        $out = [];
        foreach ($exec as $line) {
            $trim = trim($line);
            if ($trim === '') {
                if ($out !== [] && end($out) !== '') {
                    $out[] = '';
                }
                continue;
            }
            if (isset($seen[$trim])) {
                $stats['tests_deduped']++;
                continue;
            }
            $seen[$trim] = true;
            $out[] = $line;
        }
        $event['script']['exec'] = $out;
    }
    unset($event);
}

function endpointKeyFromItem(array $item): string
{
    $req = $item['request'];

    return endpointKey(strtoupper($req['method'] ?? 'GET'), pathFromPostmanUrl($req['url'] ?? []));
}

function endpointKeyMatches(string $actual, string $pattern): bool
{
    $a = explode(' ', $actual, 2);
    $p = explode(' ', $pattern, 2);
    if (($a[0] ?? '') !== ($p[0] ?? '')) {
        return false;
    }
    $aNorm = preg_replace('#/\d+#', '/{id}', $a[1] ?? '') ?? '';
    $pNorm = preg_replace('#/\d+#', '/{id}', $p[1] ?? '') ?? '';

    return $aNorm === $pNorm;
}

function buildExample(array $item, int $status, string $body, string $baseUrl, array $tokens): array
{
    $request = $item['request'];
    $headers = $request['header'] ?? [];
    foreach ($headers as &$h) {
        if (strtolower($h['key'] ?? '') === 'authorization') {
            if (str_contains($h['value'] ?? '', 'client_token') && $tokens['client_token']) {
                $h['value'] = 'Bearer ' . $tokens['client_token'];
            }
            if (str_contains($h['value'] ?? '', 'provider_token') && $tokens['provider_token']) {
                $h['value'] = 'Bearer ' . $tokens['provider_token'];
            }
        }
    }
    unset($h);
    $url = $request['url'];
    $raw = str_replace('{{base_url}}', $baseUrl, $url['raw'] ?? '');

    return [
        'name'            => 'Example — ' . ($status === 200 ? 'success' : 'response'),
        'originalRequest' => [
            'method' => $request['method'],
            'header' => $headers,
            'url'    => array_merge($url, ['raw' => $raw]),
            'body'   => $request['body'] ?? null,
        ],
        'status'                    => $status === 200 ? 'OK' : 'Error',
        'code'                      => $status,
        '_postman_previewlanguage'  => 'json',
        'header'                    => [['key' => 'Content-Type', 'value' => 'application/json']],
        'body'                      => $body,
    ];
}

function captureAuthTokens(string $baseUrl): array
{
    $login = static function (string $path, array $payload) use ($baseUrl): ?string {
        $ch = curl_init($baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json', 'Content-Type: application/json', 'lang: ar'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        if (!$body) {
            return null;
        }
        $json = json_decode($body, true);

        return $json['data']['token'] ?? null;
    };

    return [
        'client_token' => $login('/api/client/login', [
            'country_code'     => '966',
            'phone'            => '512345678',
            'password'         => 'Password123',
            'device_type'      => 'android',
            'device_id'        => 'postman-device-1',
            'mac_address'      => '00:11:22:33:44:55',
            'preferred_locale' => 'ar',
        ]) ?? '',
        'provider_token' => $login('/api/provider/login', [
            'country_code'     => '966',
            'phone'            => '512345679',
            'password'         => 'Password123',
            'device_type'      => 'android',
            'device_id'        => 'postman-device-provider',
            'mac_address'      => '00:11:22:33:44:56',
            'preferred_locale' => 'ar',
        ]) ?? '',
    ];
}

function httpRequest(string $baseUrl, array $item, array $tokens): ?array
{
    $request = $item['request'];
    $method = strtoupper($request['method'] ?? 'GET');
    $url = str_replace('{{base_url}}', $baseUrl, $request['url']['raw'] ?? '');
    foreach ($tokens as $var => $token) {
        $url = str_replace('{{' . $var . '}}', $token, $url);
    }
    $headers = ['Accept: application/json', 'lang: ar'];
    $body = $request['body']['raw'] ?? null;
    foreach ($request['header'] ?? [] as $h) {
        $key = $h['key'] ?? '';
        $val = $h['value'] ?? '';
        foreach ($tokens as $var => $token) {
            $val = str_replace('{{' . $var . '}}', $token, $val);
        }
        if (strtolower($key) === 'authorization' && str_contains($val, 'Bearer {{')) {
            continue;
        }
        if (($request['auth']['type'] ?? '') === 'bearer' && strtolower($key) === 'authorization') {
            $tokenName = str_contains($url, '/api/provider/') ? 'provider_token' : 'client_token';
            if (!empty($tokens[$tokenName])) {
                $headers[] = 'Authorization: Bearer ' . $tokens[$tokenName];
            }
            continue;
        }
        if ($key !== '') {
            $headers[] = "{$key}: {$val}";
        }
    }
    if (($request['auth']['type'] ?? '') === 'bearer') {
        $tokenName = str_contains($url, '/api/provider/') ? 'provider_token' : 'client_token';
        if (!empty($tokens[$tokenName])) {
            $headers[] = 'Authorization: Bearer ' . $tokens[$tokenName];
        }
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    if ($body !== null && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $response = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($response === false || $code === 0) {
        return null;
    }

    return ['code' => $code, 'body' => $response];
}

function addExample(array &$item, string $endpointKey, array $templates, string $baseUrl, array $tokens, array &$stats, bool $overwrite): void
{
    if (!empty($item['response']) && !$overwrite) {
        return;
    }
    $body = ['key' => 'success', 'msg' => 'العملية تمت بنجاح'];
    foreach ($templates as $pattern => $json) {
        if ($endpointKey === $pattern || endpointKeyMatches($endpointKey, $pattern)) {
            $body = $json;
            break;
        }
    }
    $pretty = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $item['response'] = [buildExample($item, 200, $pretty, $baseUrl, $tokens)];
    $stats['examples_added']++;
}

function processItems(
    array &$items,
    string $baseUrl,
    array $tokens,
    array $templates,
    array &$stats,
    bool $capture
): void {
    $skipCapture = '#/(register|activate|resend-code|password/|delete-account)$#';

    foreach ($items as &$item) {
        if (isset($item['item'])) {
            processItems($item['item'], $baseUrl, $tokens, $templates, $stats, $capture);
            continue;
        }
        if (!isset($item['request'])) {
            continue;
        }

        if (isset($item['request']['url']) && is_array($item['request']['url'])) {
            if (normalizeUrlPath($item['request']['url'])) {
                $stats['urls_fixed']++;
            }
        }

        $endpointKey = endpointKeyFromItem($item);
        if (ensureAuthHeader($item['request'], $endpointKey)) {
            $stats['auth_headers']++;
        }
        dedupeTestScript($item, $stats);

        if ($capture) {
            if (!preg_match($skipCapture, $endpointKey)) {
                $result = httpRequest($baseUrl, $item, $tokens);
                if ($result && $result['code'] > 0) {
                    $pretty = $result['body'];
                    $decoded = json_decode($result['body'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $pretty = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                    $item['response'] = [buildExample($item, $result['code'], $pretty, $baseUrl, $tokens)];
                    continue;
                }
            }
        }

        addExample($item, $endpointKey, $templates, $baseUrl, $tokens, $stats, false);
    }
    unset($item);
}
