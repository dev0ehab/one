<?php

/**
 * Ensure every POST/PUT/PATCH in One.postman_collection.json has a body (raw JSON or formdata).
 * Usage: php scripts/audit-fix-postman-bodies.php [collection.json]
 */

$path = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$collection = json_decode(file_get_contents($path), true);

if (!$collection) {
    fwrite(STDERR, "Invalid JSON\n");
    exit(1);
}

/** @return array<string, array> path pattern => body spec */
function bodyTemplates(): array
{
    return [
        'register' => [
            'mode' => 'json',
            'client' => [
                'name' => 'أحمد محمد',
                'country_code' => '966',
                'phone' => '512345678',
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
                'accept_terms' => true,
            ],
        ],
        'provider/register' => [
            'mode' => 'formdata',
            'data' => [
                ['key' => 'owner_name', 'value' => 'محمد أحمد', 'type' => 'text'],
                ['key' => 'trade_name', 'value' => 'مؤسسة النور للتجارة', 'type' => 'text'],
                ['key' => 'country_code', 'value' => '966', 'type' => 'text'],
                ['key' => 'phone', 'value' => '512345679', 'type' => 'text'],
                ['key' => 'password', 'value' => 'Password123', 'type' => 'text'],
                ['key' => 'password_confirmation', 'value' => 'Password123', 'type' => 'text'],
                ['key' => 'national_id', 'value' => '1234567890', 'type' => 'text'],
                ['key' => 'category_id', 'value' => '1', 'type' => 'text'],
                ['key' => 'commercial_register_num', 'value' => '1234567890123', 'type' => 'text'],
                ['key' => 'lat', 'value' => '24.7136', 'type' => 'text'],
                ['key' => 'lng', 'value' => '46.6753', 'type' => 'text'],
                ['key' => 'map_desc', 'value' => 'الرياض، المملكة العربية السعودية', 'type' => 'text'],
                ['key' => 'start_time', 'value' => '08:00', 'type' => 'text'],
                ['key' => 'end_time', 'value' => '22:00', 'type' => 'text'],
                ['key' => 'accept_terms', 'value' => '1', 'type' => 'text'],
                ['key' => 'banners[]', 'type' => 'file', 'src' => []],
            ],
        ],
        'provider/update/profile' => [
            'mode' => 'formdata',
            'data' => [
                ['key' => 'owner_name', 'value' => 'محمد أحمد', 'type' => 'text'],
                ['key' => 'trade_name', 'value' => 'مؤسسة النور للتجارة', 'type' => 'text'],
                ['key' => 'category_id', 'value' => '1', 'type' => 'text'],
                ['key' => 'commercial_register_num', 'value' => '1234567890123', 'type' => 'text'],
                ['key' => 'start_time', 'value' => '08:00', 'type' => 'text'],
                ['key' => 'end_time', 'value' => '22:00', 'type' => 'text'],
                ['key' => 'banners[]', 'type' => 'file', 'src' => []],
            ],
        ],
        'activate' => ['mode' => 'json', 'data' => ['code' => '123456', 'country_code' => '966', 'phone' => '512345678', 'device_id' => 'postman-device-1', 'device_type' => 'android']],
        'resend-code' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678']],
        'login' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678', 'password' => 'Password123', 'device_id' => 'postman-device-1', 'device_type' => 'android', 'mac_address' => '00:11:22:33:44:55', 'preferred_locale' => 'ar']],
        'password/forget' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678']],
        'password/check-code' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678', 'code' => '123456']],
        'password/reset' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678', 'code' => '123456', 'password' => 'Password123', 'password_confirmation' => 'Password123']],
        'logout' => ['mode' => 'json', 'data' => ['device_id' => 'postman-device-1']],
        'delete-account' => ['mode' => 'json', 'data' => ['reason' => 'سبب حذف الحساب من التطبيق']],
        'change-lang' => ['mode' => 'json', 'data' => ['lang' => 'ar']],
        'change-location' => ['mode' => 'json', 'data' => ['lat' => 24.7136, 'lng' => 46.6753, 'address' => 'الرياض', 'map_desc' => 'الرياض']],
        'update/profile' => ['mode' => 'json', 'data' => ['name' => 'أحمد محمد المحدّث']],
        'check-password' => ['mode' => 'json', 'data' => ['password' => 'Password123']],
        'update-password' => ['mode' => 'json', 'data' => ['old_password' => 'Password123', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123']],
        'send-code-to-old-phone' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345678']],
        'verify-old-phone' => ['mode' => 'json', 'data' => ['code' => '123456']],
        'send-code-to-new-phone' => ['mode' => 'json', 'data' => ['country_code' => '966', 'phone' => '512345670']],
        'verify-new-phone' => ['mode' => 'json', 'data' => ['code' => '123456']],
        'email/update' => ['mode' => 'json', 'data' => ['email' => 'new@example.com']],
        'email/verify' => ['mode' => 'json', 'data' => ['code' => '123456']],
        'contact' => ['mode' => 'json', 'data' => ['title' => 'استفسار', 'message' => 'نص الرسالة']],
        'profile/complete' => ['mode' => 'json', 'data' => ['name' => 'أحمد']],
        'favorites/toggle' => ['mode' => 'json', 'data' => ['provider_id' => 1]],
        'bookings/dismiss-empty-banner' => ['mode' => 'json', 'data' => new stdClass()],
        'bookings' => ['mode' => 'formdata', 'data' => [
            ['key' => 'provider_id', 'value' => '1', 'type' => 'text'],
            ['key' => 'scheduled_at', 'value' => '2026-06-01 14:00', 'type' => 'text'],
            ['key' => 'service_title', 'value' => 'فحص عام', 'type' => 'text'],
            ['key' => 'service_description', 'value' => 'وصف الخدمة', 'type' => 'text'],
            ['key' => 'price', 'value' => '250', 'type' => 'text'],
            ['key' => 'address', 'value' => 'الرياض', 'type' => 'text'],
        ]],
        'bookings/pay' => ['mode' => 'json', 'data' => ['payment_method' => 'wallet']],
        'bookings/cancel' => ['mode' => 'json', 'data' => ['reason' => 'سبب الإلغاء']],
        'bookings/rate' => ['mode' => 'json', 'data' => ['rate' => 5, 'comment' => 'خدمة ممتازة']],
        'additional-invoice/accept' => ['mode' => 'json', 'data' => new stdClass()],
        'additional-invoice/reject' => ['mode' => 'json', 'data' => ['reason' => 'المبلغ غير مناسب']],
        'additional-invoice/pay' => ['mode' => 'json', 'data' => ['payment_method' => 'wallet']],
        'additional-invoice' => ['mode' => 'json', 'data' => ['amount' => 150, 'reason' => 'مواد إضافية']],
        'bookings/accept' => ['mode' => 'json', 'data' => new stdClass()],
        'bookings/reject' => ['mode' => 'json', 'data' => ['reason' => 'غير متاح']],
        'bookings/complete' => ['mode' => 'json', 'data' => new stdClass()],
        'inquiries/cancel' => ['mode' => 'json', 'data' => new stdClass()],
        'inquiries/accept' => ['mode' => 'json', 'data' => new stdClass()],
        'inquiries/reject' => ['mode' => 'json', 'data' => ['reason' => 'غير متاح حالياً']],
        'inquiries' => ['mode' => 'formdata', 'data' => [
            ['key' => 'category_id', 'value' => '1', 'type' => 'text'],
            ['key' => 'title', 'value' => 'استفسار عن خدمة', 'type' => 'text'],
            ['key' => 'description', 'value' => 'تفاصيل الاستفسار', 'type' => 'text'],
            ['key' => 'target_type', 'value' => 'all', 'type' => 'text'],
        ]],
        'complains' => ['mode' => 'formdata', 'data' => [
            ['key' => 'title', 'value' => 'شكوى', 'type' => 'text'],
            ['key' => 'description', 'value' => 'وصف الشكوى', 'type' => 'text'],
        ]],
        'wallet/charge' => ['mode' => 'json', 'data' => ['amount' => 100, 'type' => 'online']],
        'wallet/withdraw' => ['mode' => 'json', 'data' => ['amount' => 50]],
        'bank-account' => ['mode' => 'json', 'data' => ['bank_name' => 'البنك الأهلي', 'account_name' => 'سارة', 'account_num' => '1234567890', 'iban' => 'SA0000000000000000000000']],
        'contact/store' => ['mode' => 'json', 'data' => ['name' => 'أحمد', 'phone' => '512345678', 'email' => 'a@example.com', 'message' => 'رسالة تواصل']],
    ];
}

function hasBody(array $req): bool
{
    $body = $req['body'] ?? null;
    if (!$body) {
        return false;
    }
    if (!empty($body['formdata']) || !empty($body['urlencoded'])) {
        return true;
    }
    return !empty($body['raw']) && trim($body['raw']) !== '' && trim($body['raw']) !== '{}';
}

function matchTemplate(string $url, string $method): ?array
{
    $templates = bodyTemplates();
    $order = [
        'bookings/dismiss-empty-banner',
        'additional-invoice/accept',
        'additional-invoice/reject',
        'additional-invoice/pay',
        'bookings/pay',
        'bookings/cancel',
        'bookings/rate',
        'bookings/accept',
        'bookings/reject',
        'bookings/complete',
        'additional-invoice',
        'inquiries/cancel',
        'inquiries/accept',
        'inquiries/reject',
        'password/check-code',
        'password/reset',
        'password/forget',
        'send-code-to-old-phone',
        'send-code-to-new-phone',
        'verify-old-phone',
        'verify-new-phone',
        'update/profile',
        'update-password',
        'check-password',
        'change-location',
        'change-lang',
        'delete-account',
        'resend-code',
        'profile/complete',
        'favorites/toggle',
        'wallet/withdraw',
        'wallet/charge',
        'bank-account',
        'contact/store',
        'email/update',
        'email/verify',
        'provider/update/profile',
        'provider/register',
        'register',
        'activate',
        'login',
        'logout',
        'contact',
        'bookings',
        'inquiries',
        'complains',
    ];

    foreach ($order as $key) {
        if (str_contains($url, $key) && isset($templates[$key])) {
            return $templates[$key];
        }
    }

    if ($method === 'PATCH' && str_contains($url, 'switch-notify')) {
        return ['mode' => 'json', 'data' => new stdClass()];
    }

    return null;
}

function applyBody(array &$req, array $template, string $url): void
{
    $isProvider = str_contains($url, '/api/provider/');
    $headers = $req['header'] ?? [];
    $hasContentType = false;
    foreach ($headers as $h) {
        if (strtolower($h['key'] ?? '') === 'content-type') {
            $hasContentType = true;
        }
    }

    if ($template['mode'] === 'formdata') {
        if (!$hasContentType) {
            $headers[] = ['key' => 'Content-Type', 'value' => 'multipart/form-data'];
        }
        $req['body'] = ['mode' => 'formdata', 'formdata' => $template['data']];
    } else {
        $data = $template['data'] ?? null;
        if ($data === null && isset($template['client'])) {
            $data = $template['client'];
        }
        if (!$hasContentType) {
            $headers[] = ['key' => 'Content-Type', 'value' => 'application/json'];
        }
        $req['body'] = [
            'mode'    => 'raw',
            'raw'     => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'options' => ['raw' => ['language' => 'json']],
        ];
    }
    $req['header'] = $headers;
}

$fixed = 0;
$stillMissing = [];

function walk(array &$items, string $path = ''): void
{
    global $fixed, $stillMissing;

    foreach ($items as &$item) {
        $name = $item['name'] ?? '';
        $p = $path ? $path . ' > ' . $name : $name;

        if (isset($item['item'])) {
            walk($item['item'], $p);
            continue;
        }

        $req = &$item['request'];
        if (!$req) {
            continue;
        }

        $method = strtoupper($req['method'] ?? 'GET');
        if (!in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            continue;
        }

        $urlField = $req['url'] ?? '';
        $url = is_string($urlField) ? $urlField : ($urlField['raw'] ?? '');
        if (is_string($urlField) && $url !== '') {
            $parts = explode('/', trim(str_replace('{{base_url}}/', '', $url), '/'));
            $req['url'] = [
                'raw'  => $url,
                'host' => ['{{base_url}}'],
                'path' => $parts,
            ];
        }

        if (hasBody($req)) {
            continue;
        }

        $template = matchTemplate($url, $method);
        if ($template) {
            applyBody($req, $template, $url);
            $fixed++;
            continue;
        }

        $stillMissing[] = "{$method} {$url} | {$p}";
    }
}

function normalizeUrls(array &$items): int
{
    $count = 0;
    foreach ($items as &$item) {
        if (isset($item['item'])) {
            $count += normalizeUrls($item['item']);
            continue;
        }
        if (!isset($item['request']['url']) || !is_string($item['request']['url'])) {
            continue;
        }
        $raw = $item['request']['url'];
        $parts = explode('/', trim(str_replace('{{base_url}}/', '', str_replace('{{base_url}}', '', $raw)), '/'));
        $item['request']['url'] = [
            'raw'  => $raw,
            'host' => ['{{base_url}}'],
            'path' => $parts,
        ];
        $count++;
    }

    return $count;
}

walk($collection['item']);
$normalized = normalizeUrls($collection['item']);

file_put_contents(
    $path,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Fixed bodies: {$fixed}\n";
echo "Normalized URLs: {$normalized}\n";
echo "Still missing: " . count($stillMissing) . "\n";
foreach ($stillMissing as $line) {
    echo "  - {$line}\n";
}

exit(count($stillMissing) > 0 ? 1 : 0);
