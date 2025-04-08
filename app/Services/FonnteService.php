<?php

namespace App\Services;

use App\Http\Helpers\ResponseHelpers;
use App\Models\Otp;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => env('FONNTE_BASE_URL')]);
        $this->apiKey = env('FONNTE_API_KEY');
    }

    public function requestOtp($phoneNumber, $message)
    {
        try {
            $response = $this->client->post('send', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                ],
                'form_params' => [
                    'target' => $phoneNumber,
                    'message' => $message,
                    'countryCode' => '62',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function checkNumber($phoneNumber)
    {
        try {
            $response = $this->client->post('validate', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                ],
                'form_params' => [
                    'target' => $phoneNumber,
                    'countryCode' => '62',
                ],
            ]);

            $response = json_decode($response->getBody(), true);
            // Log::info($response);

            return $response;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
