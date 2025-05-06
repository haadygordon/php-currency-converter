<?php
    class ExchangeRateAPI
    {
        private string $baseUrl;
        private string $apiKey;

        public function __construct(string $baseUrl, string $apiKey)
        {
            $this->baseUrl = rtrim($baseUrl, '/');
            $this->apiKey  = $apiKey;
        }

        /**
         * Fetches conversion rate from $from ➔ $to
         * @throws Exception on HTTP / JSON errors
         */
        public function getRate(string $from, string $to): float
        {
            $url = "{$this->baseUrl}/{$from}?apikey={$this->apiKey}";

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
            ]);

            $body = curl_exec($ch);
            if ($body === false) {
                throw new Exception('cURL error: ' . curl_error($ch));
            }
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status !== 200) {
                throw new Exception("API returned HTTP {$status}");
            }

            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            if (!isset($data['rates'][$to])) {
                throw new Exception("Rate for {$to} not found in API response.");
            }

            return (float) $data['rates'][$to];
        }
    }
?>