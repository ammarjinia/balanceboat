<?php

namespace App\Services;


class WaService
{
    /**
     * Send notification to multiple devices.
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @return mixed
     */
    public function sendMessage(array $data)
    {
        // Get the access token
        $accessToken = env("KRAYA_API_KEY", 'K3Pu25paYPI27nNn');

        // Prepare HTTP headers for FCM request
        $headers = [
            "X-KRAYA-API-KEY: $accessToken",
            'Content-Type: application/json'
        ];

        // JSON encode the data
        $payload = json_encode($data);
        
        \Log::info("==========Send To Kraya ===========");
        \Log::info($payload);

        // Initialize cURL to send the POST request to Firebase
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.kraya-ai.com/api/external/2HVwSgar/leads");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Execute cURL request and get the response
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        // Return error or response
        if ($err) {
            //return ['error' => 'Curl Error: ' . $err];
            \Log::info("Curl WA Error: " . $err);
            return;
        }

        \Log::info("Curl WA Success: " . $response);
        //return json_decode($response, true);
    }
}
