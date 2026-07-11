<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

trait FirebaseV1Trait
{
    use NotificationMessageTrait;

    private $authFailedCount = 3;

    private function prepareData($language): array
    {
        $newData = array_map('strval', $this->data);
        Carbon::setLocale($language);
        $newData['created_at'] = Carbon::now()->diffForHumans();

        return $newData;
    }

    private function credentials()
    {
        return json_decode(file_get_contents(storage_path('app/firebase_credentials.json')), true);
    }

    function sendFirebaseNotification($notifiable): void
    {
        $deviceTokens = $notifiable->devices()->select('device_id', 'device_type')->get();

        foreach ($deviceTokens as $deviceToken) {
            $this->sendFirebaseNotifications($notifiable->lang ?? 'ar', $deviceToken);
        }
    }

    private function sendFirebaseNotifications($lang, $deviceToken): void
    {
        $token = $deviceToken->device_id;
        $type = $deviceToken->device_type;
        $this->prepareData($lang);
        $accessToken = Cache::rememberForever('firebase_access_token', function () {
            return $this->getToken();
        });
        $title = $this->getTitle($this->data['type'], $lang);
        $body = $this->getBody($this->data, $lang);
        $projectId = $this->credentials()['project_id'];
        $fcmUrl = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

        $notificationData = [
            'title' => $title,
            'body' => $body,
            'image' => url('/') . settings()['logo'],
        ];

        $notification = match ($type) {
            'ios' => $this->getIosMessageFormat($token, $notificationData, $lang),
            'android' => $this->getAndroidMessageFormat($token, $lang),
            default => $this->getWebMessageFormat($token, $notificationData, $lang),
        };

        // Set the headers for the HTTP request
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken, // Use the OAuth2 token here
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($fcmUrl, $notification);

        if ($response->getStatusCode() == 401 && $this->authFailedCount > 0) {
            $this->authFailedCount--;
            Cache::forget('firebase_access_token');
            $this->sendFirebaseNotifications($lang, $deviceToken);
        }
    }

    public function getToken()
    {
        $clientEmail = $this->credentials()['client_email'];
        $privateKey = $this->credentials()['private_key'];

        $secret = openssl_get_privatekey($privateKey);

        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'RS256'
        ]);

        // Get seconds since 1 January 1970
        $time = time();

        $payload = json_encode([
            "iss" => $clientEmail,
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => "https://oauth2.googleapis.com/token",
            "exp" => $time + 3600,
            "iat" => $time
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);

        $base64UrlPayload = $this->base64UrlEncode($payload);

        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $secret, OPENSSL_ALGO_SHA256);

        $base64UrlSignature = $this->base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $client = new Client();

        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $responseBody = json_decode($response->getBody());

        if (!isset($responseBody->access_token)) {
            throw new \Exception("Failed to get access token: " . json_encode($responseBody));
        }

        return $responseBody->access_token;
    }

    private function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    private function getIosMessageFormat($token, $notification, $language): array
    {
        return [
            'message' => [
                'token' => $token,
                'notification' => $notification,
                'data' => $this->prepareData($language),
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10', // High priority for immediate delivery
                        'apns-push-type' => 'alert', // For alert notifications
                    ],
                    'payload' => [
                        'aps' => [
                            'alert' => $notification,
                            'sound' => 'default',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getWebMessageFormat($token, $notification, $language): array
    {
        return [
            'message' => [
                'token' => $token,
                'data' => $this->prepareData($language),
                'notification' => $notification,
                "webpush" => [
                    "fcm_options" => [
                        "link" => array_key_exists('url', $this->data) ? $this->data['url'] : url('/')
                    ]
                ],
            ]
        ];
    }

    private function getAndroidMessageFormat($token, $language): array
    {
        return [
            'message' => [
                'token' => $token,
                'data' => $this->prepareData($language),
            ],
        ];
    }
}
