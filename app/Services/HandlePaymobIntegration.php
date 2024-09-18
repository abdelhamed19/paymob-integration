<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class HandlePaymobIntegration
{
    private $uri;
    private array $secrets;
    public function __construct()
    {
        $this->uri = 'https://accept.paymob.com/api/';
        $this->secrets = [
            'api_key'=> config('services.paymob.api_key'),
            'integration_id'=> config('services.paymob.integration_id'),
            'iframe_id'=> config('services.paymob.iframe_id')
        ];
    }
    public function credit()
    {
        return Redirect::away('https://accept.paymob.com/api/acceptance/iframes/'.
        $this->secrets['iframe_id'].
        '?payment_token='.$this->getPaymentToken());
    }
    private function callEndpoint($uriParameter, $method, $params = null)
    {
        $call = Http::$method($this->uri.$uriParameter, $params);
        return $call;
    }
    public function getToken()
    {
        $response = $this->callEndpoint('auth/tokens', 'post', [
            'api_key' => $this->secrets['api_key']
        ]);
        return $response->json()['token'];
    }
    public function createOrder() {
        $total = 100;
        $items = [
            [ "name"=> "ASC1515",
                "amount_cents"=> "500000",
                "description"=> "Smart Watch",
                "quantity"=> "1"
            ]];
        $data = [
            "auth_token" =>   $this->getToken(),
            "delivery_needed" =>"false",
            "amount_cents"=> $total*100,
            "currency"=> "EGP",
            "items"=> $items,];
        $response = $this->callEndpoint('ecommerce/orders', 'post', $data);
        return $response->json();
    }
    public function getPaymentToken()
    {
        $billingData = [
            "apartment" => '45',
            "email" => "newmail@gmai.com",
            "floor" => '5',
            "first_name" => 'maher',
            "street" => "NA",
            "building" => "NA",
            "phone_number" => '0123456789',
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "cairo",
            "country" => "NA",
            "last_name" => "fared",
            "state" => "NA"
        ];
        $data = [
            "auth_token" => $this->getToken(),
            "amount_cents" => 100*100,
            "expiration" => 3600,
            "order_id" => $this->createOrder()['id'],
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $this->secrets['integration_id'],
        ];
        $response = $this->callEndpoint('acceptance/payment_keys', 'post', $data);
        return $response->json()['token'];
    }
}
