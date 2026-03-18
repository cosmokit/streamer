<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NezhaService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://nezhna.com';

    const SERVICE_TWITCH_4H_VIEWERS = '68fa2c72409d3b1fbd5a37a9';
    const SERVICE_TWITCH_CHAT_EN    = '685454094f539a9ec1304f1f';

    public function __construct()
    {
        $this->apiKey = Setting::get('nezhna_api_key', '') ?: env('NEZHNA_API_KEY', '');
    }

    public function getServices(): array
    {
        return $this->request('GET', '/api/services');
    }

    public function purchase(string $serviceId, int $count, array $fields = []): array
    {
        $body = array_merge([
            'serviceId' => $serviceId,
            'count' => $count,
        ], $fields);

        return $this->request('POST', '/api/purchase', $body);
    }

    public function orderViewers(string $twitchChannel, int $count = 65, int $interval = 5): array
    {
        return $this->purchase(self::SERVICE_TWITCH_4H_VIEWERS, $count, [
            'url' => $twitchChannel,
            'interval' => $interval,
        ]);
    }

    public function orderChatBots(string $twitchChannel, int $count = 20): array
    {
        return $this->purchase(self::SERVICE_TWITCH_CHAT_EN, $count, [
            'channel' => $twitchChannel,
        ]);
    }

    protected function request(string $method, string $path, array $body = []): array
    {
        if (!$this->apiKey) {
            throw new \Exception('NEZHNA_API_KEY not configured');
        }

        try {
            $http = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);

            $response = $method === 'GET'
                ? $http->get($this->baseUrl . $path)
                : $http->post($this->baseUrl . $path, $body);

            if (!$response->successful()) {
                throw new \Exception("Nezhna API error [{$response->status()}]: " . $response->body());
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Nezhna connection error: {$e->getMessage()}");
            throw new \Exception('Nezhna API недоступен');
        }
    }
}
