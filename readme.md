# MyInfo

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require carropublic/myinfo
```

## Usage

Clone to `packages/CarroPublic` and move `ssl` folder to `storage` folder.

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

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/carropublic/myinfo.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carropublic/myinfo.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/carropublic/myinfo/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/carropublic/myinfo
[link-downloads]: https://packagist.org/packages/carropublic/myinfo
[link-travis]: https://travis-ci.org/carropublic/myinfo
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/carropublic
[link-contributors]: ../../contributors]