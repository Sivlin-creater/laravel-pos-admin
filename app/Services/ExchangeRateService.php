<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeRateService {
    public function getUsdToKhrRate(): float {
        //Cache for 1 hour
        return Cache::remember('usd_to_khr', 3600, function() {
            $apiKey = env('EXCHANGERATE_API_KEY');
            $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD";

            $response = Http::get($url);

            if($response->successful() && isset($response['conversion_rates']['KHR'])) {
                return floatval($response['conversion_rates']['KHR']);
            }

            //Fallback if API fails
            return 4100.0;
        });
    }
}