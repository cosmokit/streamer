<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NezhaService
{
    protected $apiKey;
    protected $baseUrl = 'https://nezhna.com';

    public function __construct()
    {
        $this->apiKey = env('NEZHNA_API_KEY');
    }

    public function getServices()
    {
        if (!$this->apiKey) {
            throw new \Exception('NEZHNA_API_KEY not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/api/services');

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch services: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Nezhna getServices error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function purchaseService($serviceId, $count, $username, $email)
    {
        if (!$this->apiKey) {
            throw new \Exception('NEZHNA_API_KEY not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/purchase', [
                'service_id' => $serviceId,
                'count' => $count,
                'username' => $username,
                'email' => $email,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to purchase service: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Nezhna purchaseService error: ' . $e->getMessage());
            throw $e;
        }
    }
}
