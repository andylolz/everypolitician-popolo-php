# everypolitician-popolo for PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a port of the Python package [everypolitician-popolo-python](https://github.com/everypolitician/everypolitician-popolo-python) to PHP, which itself is a port of the Ruby gem [everypolitician-popolo](https://github.com/everypolitician/everypolitician-popolo) to Python.

## Install

Via Composer

``` bash
$ composer require andylolz/everypolitician-popolo
```

## Usage

``` php
use \mySociety\EverypoliticianPopolo\Popolo;
$popolo = Popolo::fromFilename($filename);
var_dump($popolo->persons->first);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email a.lulham@gmail.com instead of using the issue tracker.

## Credits

- [Andy Lulham][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/andylolz/everypolitician-popolo-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/andylolz/everypolitician-popolo-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andylolz/everypolitician-popolo-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andylolz/everypolitician-popolo-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/andylolz/everypolitician-popolo-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/andylolz/everypolitician-popolo
[link-travis]: https://travis-ci.org/andylolz/everypolitician-popolo-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/andylolz/everypolitician-popolo-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/andylolz/everypolitician-popolo-php
[link-downloads]: https://packagist.org/packages/andylolz/everypolitician-popolo
[link-author]: https://github.com/andylolz
[link-contributors]: ../../contributors
