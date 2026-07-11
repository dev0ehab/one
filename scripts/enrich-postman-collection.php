<?php

/**
 * Enrich Postman collection: tests, descriptions (Figma links), query params, saved examples.
 *
 * Usage:
 *   php scripts/enrich-postman-collection.php [collection.json] [--capture]
 */

declare(strict_types=1);

$collectionPath = $argv[1] ?? dirname(__DIR__) . '/postman/One.postman_collection.json';
$captureExamples = in_array('--capture', $argv, true);

if (!is_file($collectionPath)) {
    fwrite(STDERR, "Collection not found: {$collectionPath}\n");
    exit(1);
}

$figmaMap = json_decode(
    file_get_contents(dirname(__DIR__) . '/design/figma-one1-screen-map.json'),
    true,
    512,
    JSON_THROW_ON_ERROR
);

$figmaBase = $figmaMap['figma']['url'];
$fileKey = $figmaMap['figma']['fileKey'];

/** @var array<string, array{section:string,figmaUrl:string,side?:string,notes?:string,queryParams?:array<string,string>,designPng?:string,screenUrl?:string,screenLabel?:string}> */
$registry = buildFigmaRegistry($figmaMap, $figmaBase);
$registry = array_merge($registry, supplementalEndpointMeta($figmaBase));
$screenMap = buildEndpointScreenMap($figmaMap);
foreach ($screenMap as $key => $screen) {
    if (isset($registry[$key])) {
        $registry[$key]['screenUrl'] = $screen['screenUrl'];
        $registry[$key]['screenLabel'] = $screen['screenLabel'];
    } else {
        $registry[$key] = array_merge([
            'section'  => $screen['section'] ?? 'ONE',
            'figmaUrl' => $screen['sectionUrl'] ?? $screen['screenUrl'],
        ], $screen);
    }
}

/** @var array<string, list<array{key:string,value:string,description:string,disabled?:bool}>> */
$queryRegistry = buildQueryRegistry();

$collection = json_decode(file_get_contents($collectionPath), true, 512, JSON_THROW_ON_ERROR);

$tokens = ['client_token' => '', 'provider_token' => ''];
$baseUrl = rtrim(envValue('APP_URL', 'http://one.test'), '/');

if ($captureExamples) {
    $tokens = captureAuthTokens($baseUrl);
    echo "Captured auth tokens for examples.\n";
}

$stats = ['requests' => 0, 'described' => 0, 'tests' => 0, 'queries' => 0, 'examples' => 0];

enrichItems(
    $collection['item'],
    $registry,
    $queryRegistry,
    $captureExamples,
    $baseUrl,
    $tokens,
    $stats
);

$collection['info']['description'] =
    "ONE App API — Client & Provider.\n\n"
    . "Each request includes:\n"
    . "- **Description** with [Figma](https://www.figma.com/design/{$fileKey}/One1) screen link\n"
    . "- **Tests** (status + API envelope)\n"
    . "- **Query params** with descriptions (when applicable)\n"
    . "- **Saved example** (when run with `--capture`)\n\n"
    . "Demo: client `966512345678` / provider `966512345679` — password `Password123`";

file_put_contents(
    $collectionPath,
    json_encode($collection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Enriched {$stats['requests']} requests — described: {$stats['described']}, query sets: {$stats['queries']}, examples: {$stats['examples']}\n";
echo "Written: {$collectionPath}\n";

// --- functions ---

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

function figmaSectionUrl(string $nodeId): string
{
    return figmaNodeUrl($nodeId);
}

function figmaNodeUrl(string $nodeId): string
{
    global $figmaBase;

    return $figmaBase . '?node-id=' . str_replace(':', '-', $nodeId);
}

/**
 * @return array<string, array{screenUrl:string,screenLabel:string,section?:string,sectionUrl?:string}>
 */
function buildEndpointScreenMap(array $figmaMap): array
{
    $map = [];

    $set = static function (string $endpointKey, string $nodeId, string $label) use (&$map): void {
        $map[$endpointKey] = [
            'screenUrl'   => figmaNodeUrl($nodeId),
            'screenLabel' => $label,
        ];
    };

    // Client — Splash & onboarding
    $set('GET /api/client/app-intro', '13:653', 'on boarding');
    $set('GET /api/settings', '4:92', 'splash');
    $set('GET /api/is-production', '4:92', 'splash');
    $set('GET /api/main', '4:92', 'splash / banners');

    // Client — Auth
    $set('POST /api/client/register', '25:1249', 'register');
    $set('POST /api/client/login', '18:818', 'login');
    $set('PUT /api/client/activate', '25:972', 'OTP');
    $set('POST /api/client/resend-code', '25:972', 'OTP');
    $set('POST /api/client/password/forget', '18:923', 'forget password');
    $set('POST /api/client/password/check-code', '25:972', 'OTP');
    $set('POST /api/client/password/reset', '25:1057', 'reset password');
    $set('GET /api/client/terms', '1161:4928', 'terms');

    // Client — Home / providers
    $set('GET /api/client/providers', '51:888', 'Service presenter list');
    $set('GET /api/client/providers/map', '462:1620', 'providers on map');
    $set('GET /api/client/providers/{id}', '51:1963', 'Service presenter profile');
    $set('GET /api/client/providers/{id}/reviews', '51:1963', 'Service presenter profile — reviews tab');
    $set('GET /api/client/providers/{id}/ads', '51:1963', 'Service presenter profile — ads tab');
    $set('GET /api/client/providers/{id}/booking-slots', '1421:10319', 'booking flow — date & time');
    $set('POST /api/client/bookings', '1421:10319', 'booking flow — submit');
    $set('GET /api/client/providers/categories', '51:1668', 'filter');
    $set('GET /api/client/favorites', '51:888', 'Service presenter list');
    $set('POST /api/client/favorites/toggle', '51:1963', 'Service presenter profile');

    // Client — Bookings
    $set('GET /api/client/bookings', '1421:10214', 'bookings list (tabs)');
    $set('POST /api/client/bookings', '1421:10319', 'booking flow');
    $set('POST /api/client/bookings/dismiss-empty-banner', '1421:10214', 'bookings empty state');
    $set('GET /api/client/bookings/{id}', '1422:13363', 'booking detail');
    $set('POST /api/client/bookings/{id}/pay', '1421:10319', 'booking payment');
    $set('POST /api/client/bookings/{id}/cancel', '1422:13363', 'booking detail');
    $set('POST /api/client/bookings/{id}/rate', '1422:13363', 'booking detail / rate');
    $set('POST /api/client/bookings/{id}/additional-invoice/accept', '1422:13363', 'additional invoice');
    $set('POST /api/client/bookings/{id}/additional-invoice/reject', '1422:13363', 'additional invoice');
    $set('POST /api/client/bookings/{id}/additional-invoice/pay', '1421:10319', 'additional invoice payment');

    // Client — Notifications
    $set('GET /api/client/notifications', '1421:11232', 'notifications list');
    $set('GET /api/client/count-notifications', '1421:11232', 'notifications list');
    $set('DELETE /api/client/delete-notification/{id}', '1421:11260', 'delete notification');
    $set('DELETE /api/client/delete-notifications', '1421:11266', 'delete all notifications');
    $set('PATCH /api/client/switch-notify', '291:2549', 'settings');

    // Client — More / profile
    $set('GET /api/client/profile', '291:692', 'profile view');
    $set('POST /api/client/update/profile', '291:2671', 'edit profile');
    $set('PATCH /api/client/update-password', '291:2549', 'settings');
    $set('POST /api/client/check-password', '291:2549', 'settings');
    $set('POST /api/client/send-code-to-old-phone', '291:2671', 'edit profile / phone');
    $set('POST /api/client/resend-code-to-old-phone', '291:2671', 'edit profile / phone');
    $set('POST /api/client/verify-old-phone', '291:2671', 'edit profile / phone');
    $set('POST /api/client/send-code-to-new-phone', '291:2671', 'edit profile / phone');
    $set('POST /api/client/resend-code-to-new-phone', '291:2671', 'edit profile / phone');
    $set('POST /api/client/verify-new-phone', '291:2671', 'edit profile / phone');
    $set('DELETE /api/client/delete-account', '291:3237', 'delete account');
    $set('GET /api/client/complains', '277:2294', 'more menu');
    $set('POST /api/client/complains', '277:2294', 'more menu');
    $set('GET /api/client/complains/{id}', '277:2294', 'more menu');
    $set('GET /api/client/wallet', '277:2294', 'more / wallet');
    $set('GET /api/client/wallet/transactions', '277:2294', 'more / wallet');
    $set('POST /api/client/wallet/charge', '277:2294', 'more / wallet');

    // Client — Inquiries
    $set('GET /api/client/inquiries', '51:888', 'home / inquiries');
    $set('POST /api/client/inquiries', '51:888', 'home / inquiries');
    $set('GET /api/client/inquiries/{id}', '51:888', 'inquiry detail');
    $set('POST /api/client/inquiries/{id}/cancel', '51:888', 'inquiry detail');

    // Provider — Splash & onboarding
    $set('GET /api/provider/app-intro', '1102:2130', 'on boarding');
    $set('GET /api/provider/terms', '1102:2242', 'login / terms');

    // Provider — Auth
    $set('POST /api/provider/register', '1102:2258', 'register');
    $set('POST /api/provider/login', '1102:2242', 'login');
    $set('PUT /api/provider/activate', '1102:2242', 'login / OTP');
    $set('POST /api/provider/resend-code', '1102:2242', 'login / OTP');
    $set('POST /api/provider/password/forget', '1102:2242', 'login');
    $set('POST /api/provider/password/check-code', '1102:2242', 'login / OTP');
    $set('POST /api/provider/password/reset', '1102:2242', 'reset password');

    // Provider — Home
    $set('GET /api/provider/home', '1127:1532', 'home dashboard');
    $set('GET /api/provider/profile', '1169:13259', 'more / profile');
    $set('POST /api/provider/update/profile', '1169:13259', 'more / edit profile');
    $set('PATCH /api/provider/change-location', '1127:1532', 'home dashboard');
    $set('PATCH /api/provider/update-password', '1169:13259', 'more / settings');
    $set('POST /api/provider/check-password', '1169:13259', 'more');

    // Provider — Bookings (اعلانات)
    $set('GET /api/provider/bookings', '1169:10286', 'اعلاناتي');
    $set('POST /api/provider/bookings/{id}/accept', '1169:10286', 'booking actions');
    $set('POST /api/provider/bookings/{id}/reject', '1169:10286', 'booking actions');
    $set('POST /api/provider/bookings/{id}/complete', '1169:10286', 'booking actions');
    $set('POST /api/provider/bookings/{id}/additional-invoice', '1169:10286', 'additional invoice');

    // Provider — Inquiries
    $set('GET /api/provider/inquiries', '1169:8858', 'استفسارات');
    $set('GET /api/provider/inquiries/{id}', '1169:8858', 'inquiry detail');
    $set('POST /api/provider/inquiries/{id}/accept', '1169:8858', 'inquiry actions');
    $set('POST /api/provider/inquiries/{id}/reject', '1169:8858', 'inquiry actions');

    // Provider — Notifications
    $set('GET /api/provider/notifications', '1169:7521', 'notifications');
    $set('GET /api/provider/count-notifications', '1169:7521', 'notifications');
    $set('DELETE /api/provider/delete-notification/{id}', '1169:7521', 'notifications');
    $set('DELETE /api/provider/delete-notifications', '1169:7521', 'notifications');
    $set('PATCH /api/provider/switch-notify', '1169:13259', 'more / settings');

    // Provider — Wallet & bank
    $set('GET /api/provider/wallet', '1169:13259', 'more / wallet');
    $set('GET /api/provider/wallet/transactions', '1169:13259', 'more / wallet');
    $set('POST /api/provider/wallet/charge', '1169:13259', 'more / wallet');
    $set('POST /api/provider/wallet/withdraw', '1169:13259', 'more / wallet');
    $set('GET /api/provider/bank-account', '1169:13259', 'more / bank');
    $set('PUT /api/provider/bank-account', '1169:13259', 'more / bank');

    // Provider — Booking service & work days
    $set('GET /api/provider/booking-service', '1169:13259', 'more / booking service');
    $set('POST /api/provider/booking-service/subscribe', '1169:13259', 'more / booking service');
    $set('PATCH /api/provider/booking-service', '1169:13259', 'more / booking service');
    $set('GET /api/provider/work-days', '1169:13259', 'more / booking service');
    $set('PUT /api/provider/work-days/update-work-days', '1169:13259', 'more / booking service');

    // Global
    $set('GET /api/contact', '277:2294', 'contact');
    $set('GET /api/who-is-us', '277:2294', 'who is us');
    $set('GET /api/about', '277:2294', 'about');
    $set('GET /api/privacy', '1161:4928', 'privacy');
    $set('GET /api/socials', '291:2549', 'socials');
    $set('GET /api/countries-with-cities', '25:1249', 'register / location');
    $set('GET /api/country/{id}/cities', '25:1249', 'register / location');
    $set('GET /api/countries', '25:1249', 'countries');
    $set('GET /api/cities', '25:1249', 'cities');

    // Fallback: first screen in each Figma section → all section endpoints
    foreach (['client', 'provider'] as $side) {
        foreach ($figmaMap[$side]['sections'] ?? [] as $section) {
            $sectionUrl = figmaNodeUrl($section['nodeId']);
            $firstScreen = null;
            $firstLabel = $section['name'];
            foreach ($section['screens'] ?? [] as $screen) {
                if (preg_match('/^([\d:]+)\s*(.*)$/', $screen, $m)) {
                    $firstScreen = figmaNodeUrl($m[1]);
                    $firstLabel = trim($m[2]) !== '' ? trim($m[2]) : $section['name'];
                    break;
                }
            }
            $defaultUrl = $firstScreen ?? $sectionUrl;

            foreach ($section['api']['endpoints'] ?? [] as $ep) {
                if (!preg_match('/^(GET|POST|PUT|PATCH|DELETE)\s+([^\s?]+)/', $ep, $m)) {
                    continue;
                }
                $key = endpointKey($m[1], $m[2]);
                if (!isset($map[$key])) {
                    $map[$key] = [
                        'screenUrl'   => $defaultUrl,
                        'screenLabel' => $firstLabel,
                        'section'     => $section['name'],
                        'sectionUrl'  => $sectionUrl,
                    ];
                }
            }
        }
    }

    return $map;
}

function endpointKey(string $method, string $path): string
{
    $path = '/' . trim($path, '/');
    if (!str_starts_with($path, '/api')) {
        $path = '/api' . ($path === '/' ? '' : $path);
    }
    $path = preg_replace('#/\d+#', '/{id}', $path) ?? $path;
    $path = preg_replace('#/\{\{[^}]+\}\}#', '/{id}', $path) ?? $path;
    $path = preg_replace('#/notification_id#', '/{id}', $path) ?? $path;

    return strtoupper($method) . ' ' . $path;
}

function pathFromPostmanUrl(array $url): string
{
    $parts = $url['path'] ?? [];
    $parts = array_values(array_filter($parts, static fn ($p) => !str_contains((string) $p, '{{')));
    $path = '/' . implode('/', $parts);

    return preg_replace([
        '#/\d+#',
        '#/\{\{[^}]+\}\}#',
    ], '/{id}', $path) ?? $path;
}

function buildFigmaRegistry(array $figmaMap, string $figmaBase): array
{
    $registry = [];

    foreach (['client', 'provider'] as $side) {
        foreach ($figmaMap[$side]['sections'] ?? [] as $section) {
            $sectionUrl = figmaSectionUrl($section['nodeId']);
            $meta = [
                'section'    => $section['name'],
                'figmaUrl'   => $sectionUrl,
                'side'       => $side,
                'designPng'  => $section['designExport'] ?? 'design/' . ($side === 'client' ? 'user' : 'provider') . '/' . slug($section['name']) . '.png',
            ];
            if (!empty($section['api']['note'])) {
                $meta['notes'] = $section['api']['note'];
            }
            if (!empty($section['api']['queryParams'])) {
                $meta['queryParams'] = $section['api']['queryParams'];
            }

            foreach ($section['api']['endpoints'] ?? [] as $ep) {
                if (preg_match('/^(GET|POST|PUT|PATCH|DELETE)\s+(\S+)/', $ep, $m)) {
                    $key = endpointKey($m[1], $m[2]);
                    $registry[$key] = $meta;
                }
            }
        }
    }

    return $registry;
}

function slug(string $name): string
{
    $map = [
        'Splash' => 'Splash',
        'on boarding' => 'on boarding',
        'registration & login' => 'registration & login',
        'home' => 'home',
        'حجوزاتي' => 'حجوزاتي',
        'Notification' => 'Notification',
        'more' => 'more',
        'استفسارات' => 'inquiries',
        'الاعلانات' => 'bookings',
        'المزيد' => 'more',
    ];

    return $map[$name] ?? preg_replace('/\s+/', '-', strtolower($name)) ?? 'screen';
}

function supplementalEndpointMeta(string $figmaBase): array
{
    $moreClient = [
        'section' => 'more',
        'figmaUrl' => figmaSectionUrl('277:585'),
        'side' => 'client',
        'designPng' => 'design/user/more.png',
    ];
    $moreProvider = [
        'section' => 'المزيد',
        'figmaUrl' => figmaSectionUrl('1169:13259'),
        'side' => 'provider',
        'designPng' => 'design/provider/more.png',
    ];
    $bookingsClient = [
        'section' => 'حجوزاتي',
        'figmaUrl' => figmaSectionUrl('1619:3482'),
        'side' => 'client',
        'designPng' => 'design/user/حجوزاتي.png',
    ];
    $bookingsProvider = [
        'section' => 'الاعلانات',
        'figmaUrl' => figmaSectionUrl('1169:9943'),
        'side' => 'provider',
        'designPng' => 'design/provider/حجوزاتي.png',
    ];
    $global = [
        'section' => 'Global / Settings',
        'figmaUrl' => $figmaBase . '?node-id=0-1',
        'side' => 'client',
        'designPng' => 'design/user/Splash.png',
    ];

    $map = [];

    $add = static function (array $keys, array $meta) use (&$map): void {
        foreach ($keys as $key) {
            $map[$key] = $meta;
        }
    };

    $add([
        'GET /api/settings', 'GET /api/is-production', 'GET /api/countries-with-cities',
        'GET /api/country/{id}/cities', 'GET /api/contact', 'GET /api/main', 'GET /api/who-is-us',
        'GET /api/about', 'GET /api/terms', 'GET /api/privacy', 'GET /api/socials',
        'GET /api/countries', 'GET /api/cities', 'POST /api/contact/store', 'GET /api/export-pdf',
    ], $global);

    $authClient = [
        'section' => 'registration & login',
        'figmaUrl' => figmaSectionUrl('18:709'),
        'side' => 'client',
        'designPng' => 'design/user/registration & login.png',
    ];
    $authProvider = [
        'section' => 'registration & login',
        'figmaUrl' => figmaSectionUrl('1102:2241'),
        'side' => 'provider',
        'designPng' => 'design/provider/registration & login.png',
    ];

    $add([
        'POST /api/client/register', 'PUT /api/client/activate', 'POST /api/client/resend-code',
        'POST /api/client/login', 'POST /api/client/password/forget', 'POST /api/client/password/check-code',
        'POST /api/client/password/reset', 'POST /api/client/logout', 'DELETE /api/client/delete-account',
        'GET /api/client/profile', 'POST /api/client/update/profile', 'PATCH /api/client/change-lang',
        'POST /api/client/check-password', 'PATCH /api/client/update-password',
        'POST /api/client/send-code-to-old-phone', 'POST /api/client/resend-code-to-old-phone',
        'POST /api/client/verify-old-phone', 'POST /api/client/send-code-to-new-phone',
        'POST /api/client/resend-code-to-new-phone', 'POST /api/client/verify-new-phone',
        'POST /api/client/contact', 'POST /api/client/profile/complete',
        'GET /api/client/about', 'GET /api/client/terms', 'GET /api/client/privacy',
        'GET /api/client/socials', 'GET /api/client/app-intro',
    ], $authClient);

    $add([
        'POST /api/provider/register', 'PUT /api/provider/activate', 'POST /api/provider/resend-code',
        'POST /api/provider/login', 'POST /api/provider/password/forget', 'POST /api/provider/password/check-code',
        'POST /api/provider/password/reset', 'POST /api/provider/logout', 'DELETE /api/provider/delete-account',
        'GET /api/provider/profile', 'POST /api/provider/update/profile', 'PATCH /api/provider/change-lang',
        'PATCH /api/provider/change-location', 'POST /api/provider/check-password', 'PATCH /api/provider/update-password',
        'POST /api/provider/email/update', 'POST /api/provider/email/verify',
        'POST /api/provider/send-code-to-old-phone', 'POST /api/provider/resend-code-to-old-phone',
        'POST /api/provider/verify-old-phone', 'POST /api/provider/send-code-to-new-phone',
        'POST /api/provider/resend-code-to-new-phone', 'POST /api/provider/verify-new-phone',
        'POST /api/provider/contact', 'POST /api/provider/profile/complete',
        'GET /api/provider/terms', 'GET /api/provider/app-intro',
    ], $authProvider);

    $add([
        'GET /api/client/complains', 'POST /api/client/complains', 'GET /api/client/complains/{id}',
        'GET /api/client/wallet', 'GET /api/client/wallet/transactions', 'POST /api/client/wallet/charge',
    ], $moreClient);

    $add([
        'GET /api/client/bookings', 'POST /api/client/bookings', 'POST /api/client/bookings/dismiss-empty-banner',
        'GET /api/client/bookings/{id}', 'POST /api/client/bookings/{id}/pay', 'POST /api/client/bookings/{id}/cancel',
        'POST /api/client/bookings/{id}/rate', 'POST /api/client/bookings/{id}/additional-invoice/accept',
        'POST /api/client/bookings/{id}/additional-invoice/reject', 'POST /api/client/bookings/{id}/additional-invoice/pay',
    ], $bookingsClient);

    $add([
        'GET /api/client/inquiries', 'POST /api/client/inquiries', 'GET /api/client/inquiries/{id}',
        'POST /api/client/inquiries/{id}/cancel',
    ], [
        'section' => 'استفسارات',
        'figmaUrl' => figmaSectionUrl('25:1312'),
        'side' => 'client',
        'designPng' => 'design/user/home.png',
        'notes' => 'Inquiry flow from home/more — see Figma home section',
    ]);

    $add([
        'GET /api/provider/home', 'GET /api/provider/wallet', 'GET /api/provider/wallet/transactions',
        'POST /api/provider/wallet/charge', 'POST /api/provider/wallet/withdraw',
        'GET /api/provider/bank-account', 'PUT /api/provider/bank-account',
    ], $moreProvider);

    $add([
        'GET /api/provider/bookings', 'POST /api/provider/bookings/{id}/accept', 'POST /api/provider/bookings/{id}/reject',
        'POST /api/provider/bookings/{id}/additional-invoice', 'POST /api/provider/bookings/{id}/complete',
    ], $bookingsProvider);

    $add([
        'GET /api/provider/inquiries', 'GET /api/provider/inquiries/{id}',
        'POST /api/provider/inquiries/{id}/accept', 'POST /api/provider/inquiries/{id}/reject',
    ], [
        'section' => 'استفسارات',
        'figmaUrl' => figmaSectionUrl('1169:8858'),
        'side' => 'provider',
        'designPng' => 'design/provider/home.png',
    ]);

    $notifClient = [
        'section' => 'Notification',
        'figmaUrl' => figmaSectionUrl('1421:11186'),
        'side' => 'client',
        'designPng' => 'design/user/Notification.png',
    ];
    $notifProvider = [
        'section' => 'Notification',
        'figmaUrl' => figmaSectionUrl('1169:7521'),
        'side' => 'provider',
        'designPng' => 'design/provider/Notification.png',
    ];

    $add([
        'PATCH /api/client/switch-notify', 'GET /api/client/notifications', 'GET /api/client/count-notifications',
        'DELETE /api/client/delete-notification/{id}', 'DELETE /api/client/delete-notifications',
    ], $notifClient);

    $add([
        'PATCH /api/provider/switch-notify', 'GET /api/provider/notifications', 'GET /api/provider/count-notifications',
        'DELETE /api/provider/delete-notification/{id}', 'DELETE /api/provider/delete-notifications',
    ], $notifProvider);

    return $map;
}

function buildQueryRegistry(): array
{
    $paginate = [
        ['key' => 'paginate', 'value' => '10', 'description' => 'Items per page (default 10)', 'disabled' => true],
        ['key' => 'is_paginated', 'value' => '1', 'description' => '1 = paginated response, 0 = full list', 'disabled' => true],
    ];

    return [
        'GET /api/client/providers' => array_merge([
            ['key' => 'search', 'value' => '', 'description' => 'Search clinic name, map_desc, or address', 'disabled' => true],
            ['key' => 'category_id', 'value' => '1', 'description' => 'Filter by parent category (activity tab)', 'disabled' => true],
            ['key' => 'activity_type_id', 'value' => '', 'description' => 'Alias for category_id', 'disabled' => true],
            ['key' => 'city_id', 'value' => '1', 'description' => 'Filter by city', 'disabled' => true],
            ['key' => 'has_booking_service', 'value' => '1', 'description' => '1 = offers booking, 0 = inquiry only', 'disabled' => true],
            ['key' => 'min_rate', 'value' => '4', 'description' => 'Minimum avg_rate (e.g. 4)', 'disabled' => true],
            ['key' => 'sort', 'value' => 'nearest', 'description' => 'nearest | highest_rate | lowest_rate', 'disabled' => true],
            ['key' => 'lat', 'value' => '24.7136', 'description' => 'Client latitude (required for nearest sort)', 'disabled' => true],
            ['key' => 'lng', 'value' => '46.6753', 'description' => 'Client longitude (required for nearest sort)', 'disabled' => true],
            ['key' => 'radius', 'value' => '20', 'description' => 'Search radius in km (default 20)', 'disabled' => true],
        ], $paginate),
        'GET /api/client/providers/map' => [
            ['key' => 'lat', 'value' => '24.7136', 'description' => 'Map center latitude', 'disabled' => false],
            ['key' => 'lng', 'value' => '46.6753', 'description' => 'Map center longitude', 'disabled' => false],
            ['key' => 'sort', 'value' => 'nearest', 'description' => 'Use nearest when lat/lng provided', 'disabled' => true],
            ['key' => 'category_id', 'value' => '', 'description' => 'Filter by category', 'disabled' => true],
            ['key' => 'search', 'value' => '', 'description' => 'Search text', 'disabled' => true],
        ],
        'GET /api/client/providers/categories' => $paginate,
        'GET /api/client/providers/{id}/reviews' => $paginate,
        'GET /api/client/providers/{id}/ads' => $paginate,
        'GET /api/client/providers/{id}/booking-slots' => [
            ['key' => 'days', 'value' => '14', 'description' => 'Days ahead (1–60, default 14)', 'disabled' => true],
        ],
        'GET /api/client/bookings' => array_merge([
            ['key' => 'tab', 'value' => 'current', 'description' => 'current | past | cancelled | pending_payment', 'disabled' => false],
        ], $paginate),
        'GET /api/provider/bookings' => array_merge([
            ['key' => 'tab', 'value' => 'current', 'description' => 'Provider booking tabs (same as client)', 'disabled' => true],
            ['key' => 'status', 'value' => '', 'description' => 'Filter by booking status', 'disabled' => true],
        ], $paginate),
        'GET /api/client/inquiries' => array_merge([
            ['key' => 'tab', 'value' => 'pending', 'description' => 'Client: pending (default) | accepted | rejected | cancelled', 'disabled' => false],
        ], $paginate),
        'GET /api/provider/inquiries' => array_merge([
            ['key' => 'tab', 'value' => 'pending', 'description' => 'Provider: pending | accepted | rejected', 'disabled' => false],
        ], $paginate),
        'GET /api/client/notifications' => [
            ['key' => 'unread', 'value' => '0', 'description' => '1 = unread only, 0 = all', 'disabled' => true],
            ['key' => 'paginate', 'value' => '15', 'description' => 'Items per page', 'disabled' => true],
        ],
        'GET /api/provider/notifications' => [
            ['key' => 'unread', 'value' => '0', 'description' => '1 = unread only, 0 = all', 'disabled' => true],
            ['key' => 'paginate', 'value' => '15', 'description' => 'Items per page', 'disabled' => true],
        ],
        'GET /api/client/favorites' => $paginate,
        'GET /api/client/complains' => $paginate,
        'GET /api/client/wallet/transactions' => $paginate,
        'GET /api/provider/wallet/transactions' => $paginate,
        'GET /api/country/{id}/cities' => [
            ['key' => 'country_id', 'value' => '1', 'description' => 'Set in path as {country_id}', 'disabled' => true],
        ],
        'GET /api/cities' => [
            ['key' => 'country_id', 'value' => '1', 'description' => 'Filter cities by country', 'disabled' => true],
        ],
        'GET /api/export-pdf' => [
            ['key' => 'type', 'value' => '', 'description' => 'Export type (see admin docs)', 'disabled' => true],
        ],
    ];
}

function findMeta(string $key, array $registry): ?array
{
    if (isset($registry[$key])) {
        return $registry[$key];
    }

    $alt = preg_replace('#/delete-notification/#', '/delete-notification/{id}', $key);
    if ($alt !== null && isset($registry[$alt])) {
        return $registry[$alt];
    }

    return null;
}

function buildDescription(string $endpointKey, string $name, ?array $meta, array $queryParams): string
{
    $lines = [];
    $lines[] = '## ' . $name;
    $lines[] = '';
    $lines[] = '**Endpoint:** `' . $endpointKey . '`';

    $screenUrl = $meta['screenUrl'] ?? $meta['figmaUrl'] ?? null;
    $screenLabel = $meta['screenLabel'] ?? ($meta['section'] ?? null);

    if ($screenUrl) {
        $lines[] = '';
        $lines[] = '### Figma screen';
        $lines[] = '';
        if ($screenLabel) {
            $lines[] = '**Screen:** ' . $screenLabel;
            $lines[] = '';
        }
        $lines[] = $screenUrl;
        $lines[] = '';
        $lines[] = '[Open screen in Figma](' . $screenUrl . ')';
    }

    if ($meta) {
        if (!empty($meta['section']) && !empty($meta['figmaUrl']) && ($meta['figmaUrl'] ?? '') !== $screenUrl) {
            $lines[] = '';
            $lines[] = '**Figma section:** ' . $meta['section'];
            $lines[] = '';
            $lines[] = $meta['figmaUrl'];
            $lines[] = '';
            $lines[] = '[Open section frame in Figma](' . $meta['figmaUrl'] . ')';
        }
        if (!empty($meta['designPng'])) {
            $lines[] = '';
            $lines[] = '**Design PNG:** `' . $meta['designPng'] . '`';
        }
        if (!empty($meta['notes'])) {
            $lines[] = '';
            $lines[] = '**Note:** ' . $meta['notes'];
        }
        if (!empty($meta['queryParams']) && is_array($meta['queryParams'])) {
            $lines[] = '';
            $lines[] = '### API query params (from Figma map)';
            foreach ($meta['queryParams'] as $param => $desc) {
                $lines[] = '- `' . $param . '` — ' . $desc;
            }
        }
    } elseif (!$screenUrl) {
        $lines[] = '';
        $lines[] = '_Figma: [ONE1 file](' . ($GLOBALS['figmaBase'] ?? 'https://www.figma.com/design/ifLgxTuiF3pXJowbS9aNk7/One1') . ')_';
    }

    if ($queryParams !== []) {
        $lines[] = '';
        $lines[] = '### Query parameters (Postman)';
        foreach ($queryParams as $qp) {
            $state = !empty($qp['disabled']) ? ' _(optional)_' : ' _(example enabled)_';
            $lines[] = '- `' . $qp['key'] . '`' . $state . ' — ' . $qp['description'];
            if (($qp['value'] ?? '') !== '') {
                $lines[] = '  - Example: `' . $qp['value'] . '`';
            }
        }
    }

    $lines[] = '';
    $lines[] = '### Tests';
    $lines[] = 'Collection runs status `200` and checks JSON `key` + `msg`.';

    return implode("\n", $lines);
}

function standardTestScript(string $method): array
{
    $lines = [
        "pm.test('Status code is 200', function () {",
        '    pm.response.to.have.status(200);',
        '});',
        "pm.test('Response is JSON API envelope', function () {",
        '    const json = pm.response.json();',
        "    pm.expect(json).to.have.property('key');",
        "    pm.expect(json).to.have.property('msg');",
        '});',
    ];

    if (in_array($method, ['GET'], true)) {
        $lines[] = "pm.test('Success responses include data', function () {";
        $lines[] = '    const json = pm.response.json();';
        $lines[] = "    if (json.key === 'success') {";
        $lines[] = "        pm.expect(json).to.have.property('data');";
        $lines[] = '    }';
        $lines[] = '});';
    }

    return $lines;
}

function tokenCaptureScript(string $varName): array
{
    return [
        'const res = pm.response.json();',
        "if (res.key === 'success' && res.data && res.data.token) {",
        "    pm.collectionVariables.set('{$varName}', res.data.token);",
        '}',
    ];
}

function mergeEvents(array $existing, string $method, string $endpointKey): array
{
    $tests = [
        'listen' => 'test',
        'script' => [
            'type' => 'text/javascript',
            'exec' => standardTestScript($method),
        ],
    ];

    $tokenScripts = [];
    foreach ($existing as $event) {
        if (($event['listen'] ?? '') === 'test') {
            $exec = $event['script']['exec'] ?? [];
            $joined = is_array($exec) ? implode("\n", $exec) : (string) $exec;
            if (str_contains($joined, 'collectionVariables.set')) {
                $tokenScripts = $exec;
            }
        }
    }

    if ($tokenScripts === []) {
        if (str_contains($endpointKey, '/client/login')) {
            $tokenScripts = tokenCaptureScript('client_token');
        } elseif (str_contains($endpointKey, '/provider/login') || str_contains($endpointKey, '/provider/activate')) {
            $tokenScripts = tokenCaptureScript('provider_token');
        }
    }

    if ($tokenScripts !== []) {
        $tests['script']['exec'] = array_merge($tests['script']['exec'], [''], $tokenScripts);
    }

    return [$tests];
}

function applyQueryParams(array &$url, array $params): void
{
    if ($params === []) {
        return;
    }

    $url['query'] = $params;

    $path = '/' . implode('/', $url['path'] ?? []);
    $queryParts = [];
    foreach ($params as $p) {
        if (!empty($p['disabled'])) {
            continue;
        }
        $queryParts[] = rawurlencode($p['key']) . '=' . rawurlencode((string) ($p['value'] ?? ''));
    }
    $raw = '{{base_url}}' . $path;
    if ($queryParts !== []) {
        $raw .= '?' . implode('&', $queryParts);
    }
    $url['raw'] = $raw;
}

function buildExample(array $item, int $status, string $body, string $baseUrl, array $tokens): array
{
    $request = $item['request'];
    $headers = $request['header'] ?? [];
    $authHeader = null;
    foreach ($headers as $h) {
        if (strtolower($h['key'] ?? '') === 'authorization') {
            $authHeader = $h['value'] ?? '';
            break;
        }
    }
    if ($authHeader !== null) {
        foreach ($headers as &$h) {
            if (strtolower($h['key'] ?? '') === 'authorization') {
                if (str_contains($h['value'], 'client_token') && $tokens['client_token']) {
                    $h['value'] = 'Bearer ' . $tokens['client_token'];
                }
                if (str_contains($h['value'], 'provider_token') && $tokens['provider_token']) {
                    $h['value'] = 'Bearer ' . $tokens['provider_token'];
                }
            }
        }
        unset($h);
        $request['header'] = $headers;
    }

    $url = $request['url'];
    $raw = $url['raw'] ?? '';
    $raw = str_replace('{{base_url}}', $baseUrl, $raw);

    return [
        'name'           => 'Example — ' . ($status === 200 ? 'success' : 'response'),
        'originalRequest'=> [
            'method' => $request['method'],
            'header' => $headers,
            'url'    => array_merge($url, ['raw' => $raw]),
            'body'   => $request['body'] ?? null,
        ],
        'status'         => $status === 200 ? 'OK' : 'Error',
        'code'           => $status,
        '_postman_previewlanguage' => 'json',
        'header'         => [
            ['key' => 'Content-Type', 'value' => 'application/json'],
        ],
        'body'           => $body,
    ];
}

function paceCaptureRequest(): void
{
    static $lastAt = 0.0;
    $gapMs = (int) (getenv('POSTMAN_CAPTURE_GAP_MS') ?: 2000);
    $gap = max(0.5, $gapMs / 1000);
    $now = microtime(true);
    $elapsed = $now - $lastAt;
    if ($lastAt > 0 && $elapsed < $gap) {
        usleep((int) (($gap - $elapsed) * 1_000_000));
    }
    $lastAt = microtime(true);
}

function httpRequest(string $baseUrl, array $item, array $tokens): ?array
{
    paceCaptureRequest();

    $request = $item['request'];
    $method = strtoupper($request['method'] ?? 'GET');
    $url = $request['url']['raw'] ?? '';
    $url = str_replace('{{base_url}}', $baseUrl, $url);

    foreach ($tokens as $var => $token) {
        $url = str_replace('{{' . $var . '}}', $token, $url);
    }

    $headers = ["Accept: application/json", "lang: ar"];
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
        if ($key !== '') {
            $headers[] = "{$key}: {$val}";
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

function captureAuthTokens(string $baseUrl): array
{
    $tokens = ['client_token' => '', 'provider_token' => ''];

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

    $tokens['client_token'] = $login('/api/client/login', [
        'country_code'     => '966',
        'phone'            => '512345678',
        'password'         => 'Password123',
        'device_type'      => 'android',
        'device_id'        => 'postman-device-1',
        'mac_address'      => '00:11:22:33:44:55',
        'preferred_locale' => 'ar',
    ]) ?? '';

    $tokens['provider_token'] = $login('/api/provider/login', [
        'country_code'     => '966',
        'phone'            => '512345679',
        'password'         => 'Password123',
        'device_type'      => 'android',
        'device_id'        => 'postman-device-provider',
        'mac_address'      => '00:11:22:33:44:56',
        'preferred_locale' => 'ar',
    ]) ?? '';

    return $tokens;
}

function enrichItems(
    array &$items,
    array $registry,
    array $queryRegistry,
    bool $capture,
    string $baseUrl,
    array $tokens,
    array &$stats
): void {
    foreach ($items as &$item) {
        if (isset($item['item'])) {
            enrichItems($item['item'], $registry, $queryRegistry, $capture, $baseUrl, $tokens, $stats);
            continue;
        }

        if (!isset($item['request'])) {
            continue;
        }

        $stats['requests']++;
        $method = strtoupper($item['request']['method'] ?? 'GET');
        $path = pathFromPostmanUrl($item['request']['url'] ?? []);
        $endpointKey = endpointKey($method, $path);
        $meta = findMeta($endpointKey, $registry);

        $queryParams = $queryRegistry[$endpointKey] ?? [];
        if ($queryParams !== []) {
            applyQueryParams($item['request']['url'], $queryParams);
            $stats['queries']++;
        }

        $description = buildDescription($endpointKey, $item['name'] ?? 'Request', $meta, $queryParams);
        $item['description'] = $description;
        $item['request']['description'] = $description;
        if ($meta) {
            $stats['described']++;
        }

        $item['event'] = mergeEvents($item['event'] ?? [], $method, $endpointKey);
        $stats['tests']++;

        if ($capture) {
            $skipCapture = preg_match(
                '#/(register|activate|resend-code|password/|delete-account|send-code|verify-|logout)$#',
                $endpointKey
            );
            if (!$skipCapture) {
                $result = httpRequest($baseUrl, $item, $tokens);
                if ($result && $result['code'] > 0) {
                    $pretty = $result['body'];
                    $decoded = json_decode($result['body'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $pretty = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                    $item['response'] = [
                        buildExample($item, $result['code'], $pretty, $baseUrl, $tokens),
                    ];
                    $stats['examples']++;
                }
            }
        }
    }
    unset($item);
}
