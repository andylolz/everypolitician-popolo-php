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

You can download a Popolo file manually from [EveryPolitician](http://everypolitician.org/).

The following example uses [Åland Lagting](https://github.com/everypolitician/everypolitician-data/raw/master/data/Aland/Lagting/ep-popolo-v1.0.json)
(which is the legislature of the Åland islands, available as
JSON data from the [EveryPolitician page for Åland](http://everypolitician.org/aland/)).

First you'll need to require the library and read in a file from disk.

``` php
use \mySociety\EveryPoliticianPopolo\Popolo;
$popolo = Popolo::fromFilename('ep-popolo-v1.0.json');
```

All Popolo classes used by EveryPolitician are implemented:

 * [Person](http://www.popoloproject.com/specs/person.html)
 * [Organization](http://www.popoloproject.com/specs/organization.html)
 * [Area](http://www.popoloproject.com/specs/area.html)
 * [Event](http://www.popoloproject.com/specs/event.html)
 * [Membership](http://www.popoloproject.com/specs/membership.html)

There are methods defined for each property on a class, e.g. for a
Person:

``` php
count($popolo->persons); // 60
$person = $popolo->persons->first;
echo $person->id; // e3aab23e-a883-4763-be0d-92e5936024e2
echo $person->name; // Aaltonen Carina
echo $person->image; // http://www.lagtinget.ax/files/aaltonen_carina.jpg
echo $person->wikidata; // Q4934081
```

You can also find individual records or collections based on their
attributes:

``` php
echo $popolo->persons->get(["name" => "Aaltonen Carina"]); // <Person: Aaltonen Carina>

$organizations = $popolo->organizations->filter(["classification" => "party"]);
foreach ($organizations as $organization) {
    echo $organization;
}
// <Organization: Liberalerna>
// <Organization: Liberalerna på Åland r.f.>
// <Organization: Moderat Samling>
// <Organization: Moderat Samling på Åland r.f.>
// <Organization: Moderat samling>
// <Organization: Moderaterna på Åland>
// <Organization: Obunden Samling>
// <Organization: Obunden Samling på Åland>
// <Organization: Ålands Framtid>
// <Organization: Ålands Socialdemokrater>
// <Organization: Ålands framtid>
// <Organization: Ålands socialdemokrater>
// <Organization: Åländsk Center>
// <Organization: Åländsk Center r.f.>
// <Organization: Åländsk Demokrati>
// <Organization: Åländsk center>
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

[ico-version]: https://img.shields.io/packagist/v/andylolz/everypolitician-popolo.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/andylolz/everypolitician-popolo-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andylolz/everypolitician-popolo-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andylolz/everypolitician-popolo-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/andylolz/everypolitician-popolo.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/andylolz/everypolitician-popolo
[link-travis]: https://travis-ci.org/andylolz/everypolitician-popolo-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/andylolz/everypolitician-popolo-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/andylolz/everypolitician-popolo-php
[link-downloads]: https://packagist.org/packages/andylolz/everypolitician-popolo
[link-author]: https://github.com/andylolz
[link-contributors]: ../../contributors
