<?php

namespace CarroPublic\MyInfo;

use File;
use Jose\Loader;
use GuzzleHttp\Client;
use Jose\Factory\JWKFactory;

class MyInfo
{
    /**
     * Create authorization URL
     * base on the configuration
     *
     * @param  string $state
     *
     * @return string Authorization URL of MyInfo
     */
    public function createAuthorizeUrl($state = "123")
    {
        $params = [
            'attributes'    => config('myinfo.attributes'),
            'client_id'     => config('myinfo.client_id'),
            'purpose'       => config('myinfo.purpose'),
            'state'         => $state,
            'redirect_uri'  => config('myinfo.call_back_url'),
        ];

        return urldecode(config('myinfo.api.authorise') . "?". http_build_query($params));
    }

    /**
     * Call the Token API (with the authorization code)
     *
     * @param  string $code Authorization Code
     *
     * @return mixed
     */
    public function createTokenRequest($code)
    {
        $headers = [
            'Cache-Control' => 'no-cache',
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        $params = [
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => config('myinfo.call_back_url'),
            'client_id'     => config('myinfo.client_id'),
            'client_secret' => config('myinfo.client_secret'),
            'code'          => $code,
        ];

        $method = 'POST';

        $authHeaders = $this->generateSHA256withRSAHeader(
            config('myinfo.api.token'),
            $params,
            $method,
            $headers['Content-Type'],
            config('myinfo.client_id'),
            config('myinfo.client_secret')
        );

        $headers['Authorization'] = $authHeaders;

        $http = new Client;

        $response = $http->post(config('myinfo.api.token'), [
            'form_params' => $params,
            'headers' => $headers,
        ]);

        return $response->getBody();
    }

    /**
     * Create person requests
     *
     * @param  string $userUniFin     Fin of the person
     * @param  string $jwtAccessToken JWT access token
     *
     * @return mixed
     */
    public function createPersonRequest($userUniFin, $jwtAccessToken)
    {
        $url            = config('myinfo.api.personal') . '/' . $userUniFin . '/';

        $params = [
            'attributes' => config('myinfo.attributes'),
            'client_id' => config('myinfo.client_id')
        ];

        $headers = [
            'Cache-Control' => 'no-cache',
        ];

        $authHeaders    = $this->generateSHA256withRSAHeader(
            $url,
            $params,
            'GET',
            '',
            config('myinfo.client_id'),
            config('myinfo.client_secret')
        );

        if (!empty($authHeaders)) {
            $headers['Authorization'] = $authHeaders . ",Bearer " . $jwtAccessToken;
        } else {
            $headers['Authorization'] = "Bearer " . $jwtAccessToken;
        }

        $http = new Client;

        $response = $http->get($url, [
            'query' => $params,
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Decrypt encrypted(with JWE) user data to array
     *
     * https://jwt.io/ (HS256)
     *
     *  https://web-token.spomky-labs.com/v/v1.x/migration/from-spomky-labs-jose/encrypted-tokens-jwe
     *
     * @param  string $encryptedUserData encrypted user data
     *
     * @return array Get user data array from inventory
     */
    public function getUserData($encryptedUserData, $privateKeyPath)
    {
        $key = JWKFactory::createFromKeyFile(
            $privateKeyPath,
            config('myinfo.client_secret'),
            [
                'kid' => 'My Private RSA key',
                'use' => 'enc',
                'alg' => 'RSA-OAEP',
            ]
        );

        $loader = new Loader();
        $userData = $loader->loadAndDecryptUsingKey(
            $encryptedUserData, // String to load and decrypt
            $key,               // The symmetric or private key
            ['RSA-OAEP'],       // A list of allowed key encryption algorithms
            ['A256GCM'],        // A list of allowed content encryption algorithms
            $recipient_index    // If decrypted, this variable will be set with the recipient index used to decrypt
        );
        
        return $this->getJWTPayload($userData->getPayload());
    }

    /**
     * Get JWT Payload
     *
     * @param  string $jwtAccessToken JWT Access Token
     *
     * @return array                  Payload of JWT
     */
    public function getJWTPayload($jwtAccessToken)
    {
        list($header, $payload, $signature) = explode(".", $jwtAccessToken);

        return json_decode(base64_decode($payload), true);
    }

    /**
     * Generate SHA256 with RSAHeader
     *
     * @param  string $url            URL
     * @param  string $params         Parameter for header
     * @param  string $method         Method for header generation
     * @param  string $strContentType Content Type for header
     * @param  string $appId          AppId of MyInfo
     * @param  string $keyCertContent PrivateKey content string
     * @param  string $passphrase     Client secret of my info
     *
     * @return string
     */
    private function generateSHA256withRSAHeader(
        $url,
        $params,
        $method,
        $strContentType,
        $appId,
        $passphrase
    ) {
        $nonceValue = $this->generateNonce(32);
        $timestamp = round(microtime(true) * 1000);
        
        $defaultApexHeaders = [
            'app_id'           => $appId,
            'nonce'            => $nonceValue,
            'signature_method' => 'RS256',
            'timestamp'        => $timestamp,
        ];

        if ($method == 'POST' && $strContentType != 'application/x-www-form-urlencoded') {
            $params = [];
        }

        $baseParams = array_merge($defaultApexHeaders, $params);
        ksort($baseParams);

        $baseParamsStr = urldecode(http_build_query($baseParams));

        $baseString = strtoupper($method) . '&' . $url . '&' . $baseParamsStr;

        $privateKey = File::get(base_path('ssl/private.pem'));

        $signWith[] = $privateKey;
        if (isset($passphrase) && !empty($passphrase)) {
            $signWith[] = $passphrase;
        }

        openssl_sign($baseString, $signature, $signWith, 'sha256WithRSAEncryption');

        return 'PKI_SIGN timestamp="'.$timestamp.
                '",nonce="'.$nonceValue.
                '",app_id="'.$appId.
                '",signature_method="RS256"'.
                ',signature="'. base64_encode($signature) .
                '"';
    }

    /**
     * Generate nonce value
     * Wonder what's nonce? - Read on the following wiki link
     *
     * https://en.wikipedia.org/wiki/Cryptographic_nonce
     *
     * @param  integer $length   Length of nonce
     * @param  integer $strength Length of strength
     *
     * @return string            Nonce
     */
    private function generateNonce($length = 9, $strength = 0)
    {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength & 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }
}
