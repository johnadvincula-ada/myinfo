# MyInfo

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Please be aware - this is still working in progress.

This is a Laravel Pacakge for [MyInfo](https://www.ndi-api.gov.sg/library/trusted-data/myinfo/introduction). You can read more about MyInfo on their page.
There is [[PUBLIC] Demo client application for integrating with MyInfo](https://github.com/jamesleegovtech/myinfo-demo-app) which was in Nodejs. So, I implemented my own for PHP base on their Nodejs demo.

Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require carropublic/myinfo
```

## Usage

Run `php artisan vendor:publish` which will move `private.pem` and `public.pem` to your `storage/ssl` folder. I got the private/public key pair from MyInfo demo page. You have to use your own

Setup Environment

	MYINFO_AUTHORIZE_API=https://myinfosgstg.api.gov.sg/test/v2/authorise
	MYINFO_TOKEN_API=https://myinfosgstg.api.gov.sg/test/v2/token
	MYINFO_PERSON_API=https://myinfosgstg.api.gov.sg/test/v2/person
	MYINFO_CALLBACK_URL=http://localhost:3001/callback
	MYINFO_CLIENT_ID=STG2-MYINFO-SELF-TEST
	MYINFO_CLIENT_SECRET=44d953c796cccebcec9bdc826852857ab412fbe2
	MYINFO_REALM=realm
	MY_INFO_PRIVATE_KEY=ssl/public.pem
	MY_INFO_PUBLIC_KEY=ssl/private.pem
	MYINFO_ATTRIBUTES="name,sex,race,nationality,dob,email,mobileno,regadd,housingtype,hdbtype,marital,edulevel,assessableincome,hanyupinyinname,aliasname,hanyupinyinaliasname,marriedname,cpfcontributions,cpfbalances"

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
[link-author]: https://github.com/carropublic
[link-contributors]: ../../contributors]