# MyInfo

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This is a Laravel Pacakge for [MyInfo](https://www.ndi-api.gov.sg/library/trusted-data/myinfo/introduction). You can read more about MyInfo on their page.
There is [[PUBLIC] Demo client application for integrating with MyInfo](https://github.com/jamesleegovtech/myinfo-demo-app) which was in Nodejs. So, I implemented my own for Laravel Package base on their Nodejs demo.

Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require carropublic/myinfo
```

## Usage

Run `php artisan vendor:publish` which will move `private.pem` and `public.pem` to your `storage/ssl` folder. I got the private/public key pair from MyInfo demo page. You have to use your own

Setup Environment

	MYINFO_AUTHORIZE_API="https://test.api.myinfo.gov.sg/com/v3/authorise"
	MYINFO_TOKEN_API="https://test.api.myinfo.gov.sg/com/v3/token"
	MYINFO_PERSON_API="https://test.api.myinfo.gov.sg/com/v3/person"
	MYINFO_CALLBACK_URL="http://localhost:3001/callback"
	MYINFO_CLIENT_ID="STG2-MYINFO-SELF-TEST"
	MYINFO_CLIENT_SECRET="44d953c796cccebcec9bdc826852857ab412fbe2"
	MYINFO_REALM="http://localhost:3001"
	MY_INFO_PRIVATE_KEY="ssl/private.pem"
	MY_INFO_PUBLIC_KEY="ssl/public.pem"
	MYINFO_ATTRIBUTES="uinfin,name,sex,race,nationality,dob,email,mobileno,regadd,housingtype,hdbtype,marital,edulevel,noa-basic,ownerprivate,cpfcontributions,cpfbalances"
	MYINFO_PURPOSE="demonstrating MyInfo APIs"

Usage,

You can create aturhoize URL something like the following.

	MyInfo::createAuthorizeUrl($code); # $code in this case for state

When user agree to retrieve their info, then it's gonna redirect to `MYINFO_CALLBACK_URL` base on your configruation. 
Then you need to handle something like the following

	// This is the state that you passed when creating authorize URL
	$state           = $request->input('state');

	// This is the authorizon code
	$code           = $request->input('code');

	// Create token request base on authorize code
	$myInfoToken    = MyInfo::createTokenRequest($code);
	$token          = json_decode($myInfoToken, true);

	if (!$token) {
		// handle error when there is no token		
	}

	$jwtPayload = MyInfo::getJWTPayload($token['access_token']);

	if(!$jwtPayload) {
		// handle error when there is problem with getting JWT
	}

	$userUniFin = $jwtPayload['sub'];

	// Create person request
	$personRequest = MyInfo::createPersonRequest(
		$userUniFin,
		$token['access_token']
	);

	if(!$personRequest) {
		// handle error when there is problem with person request
	}

	// Get user data using private key
	$userData = MyInfo::getUserData(
		$personRequest,
		storage_path(config('myinfo.keys.private'))
	);

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todo list.

## Security

If you discover any security related issues, please email <a href="mail:universe@carro.co?Subject=Security Bug In MyInfo">author email</a> instead of using the issue tracker.

## Credits

- [Carro.sg][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/carropublic/myinfo.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carropublic/myinfo.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/carropublic/myinfo
[link-downloads]: https://packagist.org/packages/carropublic/myinfo
[link-author]: https://github.com/carro-public
[link-contributors]: ../../contributors]