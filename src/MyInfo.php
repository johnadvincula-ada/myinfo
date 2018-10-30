<?php

namespace CarroPublic\MyInfo;

use GuzzleHttp\Client;

class MyInfo
{
    public function createAuthorizeUrl()
    {
    	$callBackUrl		= config('myinfo.call_back_url');
    	$myInfoAuthorizeURL = config('myinfo.api.authorise');
    	$clientId 			= config('myinfo.client_id');
    	$attributes			= config('myinfo.attributes');
    	$purpose			= config('myinfo.purpose');
    	$state 				= 123;

    	return "{$myInfoAuthorizeURL}?client_id={$clientId}&attributes={$attributes}&purpose={$purpose}&state={$state}&redirect_uri={$callBackUrl}";
    }


    public function createTokenRequest($code)
    {
        $nonceValue = mt_rand(0000000, 9999999);
        $time = (time() * 1000);

        $baseString = "POST&https://myinfosgstg.e.api.gov.sg/test/v2/token&apex_l2_eg_app_id=STG2-MYINFO-SELF-TEST&apex_l2_eg_nonce=".
                    $nonceValue."&apex_l2_eg_signature_method=SHA256withRSA&apex_l2_eg_timestamp=".
                    $time."&apex_l2_eg_version=1.0&client_id=STG2-MYINFO-SELF-TEST&client_secret=44d953c796cccebcec9bdc826852857ab412fbe2&code=".
                    $code ."&grant_type=authorization_code&redirect_uri=http://localhost:3001/callback";
        \Log::info('Base String '. $baseString);

        $signature = $this->getSignature($baseString);

        \Log::info('Signature '. $signature);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cache-Control' => 'no-cache',
            'Authorization' => "Apex_L2_Eg realm=\"realm\",apex_l2_eg_app_id=\"STG2-MYINFO-SELF-TEST\",apex_l2_eg_nonce=\"{$nonceValue}\",apex_l2_eg_signature_method=\"SHA256withRSA\",apex_l2_eg_signature=\"{$signature}\",apex_l2_eg_timestamp=\"{$time}\",apex_l2_eg_version=\"1.0\"",                     
        ];

        \Log::info($headers['Authorization']);

        $client = new Client();
        $request = $client->request('POST','https://myinfosgstg.api.gov.sg/test/v2/token', [
            'headers' => $headers
        ]);

        $resp = $request->send();

        dd($resp);
    }

    private function getSignature($baseString)
    {
        $privateKey = \File::get(storage_path('ssl/private.pem'));
        $algorithm = "sha256WithRSAEncryption";

        openssl_sign($baseString, $binary_signature, $privateKey, $algorithm);

        return base64_encode($binary_signature);
    }

    public function decryptTokenData()
    {

    }

    public function getUserData()
    {

    }
}