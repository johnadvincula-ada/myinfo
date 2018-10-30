<?php

return [
    "call_back_url" => env("MYINFO_CALLBACK_URL"),

    "attributes" => "name,sex,race,nationality,dob,email,mobileno,regadd,housingtype,hdbtype,marital,edulevel,assessableincome,hanyupinyinname,aliasname,hanyupinyinaliasname,marriedname,cpfcontributions,cpfbalances",

    "client_id" => env("MYINFO_CLIENT_ID"),

    "client_secret" => env("MYINFO_CLIENT_SECRET"),

    "purpose" => env('MYINFO_PURPOSE'),

    "realm" => env('MYINFO_REALM'),

    "keys" => [
        "private" => env('MY_INFO_PRIVATE_KEY'),

        "public"  => env('MY_INFO_PUBLIC_KEY'),
    ],

    "api" => [
        "authorise" => env("MYINFO_AUTHORIZE_API"),

        "token" => env("MYINFO_TOKEN_API"),

        "personal" => env("MYINFO_PERSON_API"),
    ],
];