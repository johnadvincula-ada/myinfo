<?php

return [
    /*
     * The call back URL for MyInfo
     */
    'call_back_url' => env('MYINFO_CALLBACK_URL'),

    /*
     * Lists of attributes that we want to get from MyInfo
     * Can be read more about it on -
     */
    'attributes'    => env('MYINFO_ATTRIBUTES'),

    /*
     * The Client ID from MyInfo
     */
    'client_id'     => env('MYINFO_CLIENT_ID'),

    /*
     * The Client Secret from MyInfo
     */
    'client_secret' => env('MYINFO_CLIENT_SECRET'),

    /*
     * Purpose of getting data from MyInfo, which describe to the user
     */
    'purpose'       => env('MYINFO_PURPOSE'),

    /*
     * Realm, the domain of your website
     */
    'realm'         => env('MYINFO_REALM'),

    /*
     * Paths of your private key and public key
     * which use to encrypt and decrypt the data of user
     * and generating authorization headers
     */
    'keys'  => [
        'private' => env('MY_INFO_PRIVATE_KEY'),

        'public'  => env('MY_INFO_PUBLIC_KEY'),
    ],

    /*
     * Lists of MyInfo API endpoints
     *
     *  Authorize is MyInfo Authorize API
     *  Token is MyInfo Token API
     *  Personal is MyInfo Personal API
     */
    'api' => [
        'authorise' => env('MYINFO_AUTHORIZE_API'),

        'token'     => env('MYINFO_TOKEN_API'),

        'personal' => env('MYINFO_PERSON_API'),
    ],
];
