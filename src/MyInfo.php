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

        // $signature_encoding = mb_convert_encoding($binary_signature, "UTF-8");
        // $encoded_sign = base64_encode($signature_encoding);

        return base64_encode($binary_signature);
    }


    // d2fe38cf-3bd5-4be7-8b11-1e739f422567
    public function createTokenRequestOld($code)
    {
    	$cacheControl = "no-cache";
	  	$contentType = "application/x-www-form-urlencoded";
	  	$method 	 = "POST";

    	$callBackUrl = config('myinfo.call_back_url');
    	$clientId 	 = config('myinfo.client_id');
    	$clientSecret = config('myinfo.client_secret');


	  	$params = "grant_type=authorization_code" .
				    "&code=" . $code .
				    "&redirect_uri=" . $callBackUrl .
				    "&client_id=" . $clientId .
				    "&client_secret=" . $clientSecret;

	  	$strHeader = "Content-Type{$contentType}&Cache-Control={$cacheControl}";

	  	$this->generateAuthorizationHeader($params, $method, $contentType);
    }

    private function generateAuthorizationHeader($params, $contentType, $method='POST')
    {
    	// $realm = config('myinfo.realm');
    	// $tokenUrl = config('myinfo.api.token');
    	// $clientId = config('myinfo.client_id');
    	// $clientSecret = config('myinfo.client_secret');
    	// $privateKeyContent = config('myinfo.kyes.private');

    	// $url = str_replace('.api.gov.sg', '.e.api.gov.sg', $tokenUrl);
        // $nonceValue = mt_rand(0, 999999999);
        // $time = time();

        // // generateSHA256withRSAHeader
    	// $defaultApexHeaders = [
    	// 	'apex_l2_eg_app_id' => ,
    	// 	'apex_l2_eg_nonce'	=> $nonceValue,
    	// 	'apex_l2_eg_signature_method' => 'SHA256withRSA',
    	// 	'apex_l2_eg_timestamp' => time(),
    	// 	'apex_l2_eg_version' => '1.0'
    	// ];

        // $baseString = "{$method}&${url}&apex_l2_eg_app_id={$clientId}&apex_l2_eg_nonce={$nonceValue}&apex_l2_eg_signature_method=SHA256withRSA&apex_l2_eg_timestamp={$time}&apex_l2_eg_version=1.0&client_id={$clientId}&client_secret={$clientSecret}&code={$code}&grant_type=authorization_code&redirect_uri=http://localhost:3001/myinfo/callback";

        // $signature = 




        // https://myinfosgstg.e.api.gov.sg/test/v2/person/S9812381D/&apex_l2_eg_app_id=STG2-MYINFO-SELF-TEST&apex_l2_eg_nonce=154053878805700&apex_l2_eg_signature_method=SHA256withRSA&apex_l2_eg_timestamp=1540538788057&apex_l2_eg_version=1.0&attributes=name,sex,race,nationality,dob,email,mobileno,regadd,housingtype,hdbtype,marital,edulevel,assessableincome,ownerprivate,assessyear,cpfcontributions,cpfbalances&client_id=STG2-MYINFO-SELF-TEST

        // $baseParams = sortJSON(_.merge());

    }

    private function generateKey($data, $signature)
    {
        // fetch private key from file and ready it
        $pkeyid = openssl_pkey_get_private("file://src/openssl-0.9.6/demos/sign/key.pem");

        // compute signature
        openssl_sign($data, $signature, $pkeyid);

        // free the key from memory
        openssl_free_key($pkeyid);
    }

    private function generateSHA256withRSAHeader()
    {

    }

    public function decryptTokenData()
    {

    }

    public function getUserData()
    {

    }
}