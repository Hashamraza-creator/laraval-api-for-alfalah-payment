<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlfalahPaymentService
{
    private string $merchantId;
    private string $secretKey;
    private string $apiKey;
    private string $endpoint;
    public function __construct()
    {
        $this->merchantId = env('ALFALAH_MERCHANT_ID');
        $this->secretKey = env('ALFALAH_SECRET_KEY');
        $this->apiKey = env('ALFALAH_API_KEY');
        $this->endpoint = env('ALFALAH_ENDPOINT');
    }

    public function initiatePayment(float $amount, string $orderId): array
    {
        $payload = [
            'merchant_id' => $this->merchantId,
            'secret_key' => $this->secretKey,
            'api_key' => $this->apiKey,
            'amount' => $amount,
            'order_id' => $orderId,
            'currency'=> 'PKR',
            'returnUrl' => route('alfalah.callback'),
            'cancelUrl' => route('alfalah.cancel'),
            'signature' => hash_hmac('sha256', $this->merchantId . $orderId . $amount, $this->secret),
        ];

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, $payload)->json();
    }



    public function verifyPayment(string $orderId): array
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("$this->endpoint/verify",['merchantId'=>$this->merchantId, 'orderId'=> $orderId])->json();
    }
}
