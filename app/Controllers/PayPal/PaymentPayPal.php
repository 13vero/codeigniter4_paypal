<?php

namespace App\Controllers\PayPal;

use App\Controllers\BaseController;

class PaymentPayPal extends BaseController 
{
    private $clientId = 'AeImMgOGFxyy7EWxwjIJ1SR6W1WgVSA33Ix3RIIHYCqkR5wYKDb2JUrRZL7OXVHBu8zwd3IQYDBKkSH1';
    private $secret = 'EPSoWBCihxRzbxvsUXv0mfQSMzQoFd1ZGcgJCK9TD43FsGm3tyE1TCpRqU7jylcPF85AWvFWJPmYm0PS';
    private $baseURL = 'https://api-m.sandbox.paypal.com';

    public function index()
    {
        return view('shopping/paypal');
    }

    public function process($orderId = null) 
    {
        try {
            $accessToken = $this->getAccessToken();

            $curl = curl_init($this->baseURL."/v2/checkout/orders/$orderId/capture");
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array (
                    'Authorization: Bearer '.$accessToken,
                    'Content-Type: application/json'
                )
            ));
            
            $res = json_decode(curl_exec($curl));
            curl_close($curl);
            
            if($res) {
                if($res->status == 'COMPLETED') {
                    return $this->response->setJSON(array('msj'=>'Orden procesada'));
                }
            }

            return $this->response->setJSON(array('msj'=>'Orden no procesada'));

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // public function processCodeIgniter($orderId = null) 
    // {
    //     try {
    //         $accessToken = $this->getAccessToken();
    //         $client = \Config\Services::curlrequest();

    //         $response = $client->request('POST', $this->baseURL."/v2/checkout/orders/$orderId/capture", [
    //             'headers' => [
    //                 'Authorization' => 'Bearer '.$accessToken,
    //                 'Content-Type' => 'application/json'
    //             ]
    //         ]);

    //         var_dump($res);
    //         return $res;
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }

    public function getAccessToken()
    {
        try {
            $client = \Config\Services::curlrequest();

            $response = $client->request('POST', $this->baseURL.'/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->secret],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $res = json_decode($response->getBody());
            return $res->access_token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}