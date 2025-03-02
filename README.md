# A package to easily create and manage a sound map with Laravel

![GitHub commit activity](https://img.shields.io/github/commit-activity/m/codiceovvio/soundmap)
![GitHub License](https://img.shields.io/github/license/codiceovvio/soundmap)

With Laravel Sound Map you can create and manage a Leaflet map to add and display sound recordings as well as images and texts, attaching them to map markers.
This package is primarily intended to use for sound map development, but it can be customized with any content type you may need to display and geolocate.

## Preview
![markers](https://github.com/user-attachments/assets/5ab62ff2-e764-4d7a-b55b-6f2644601544)

## Installation

You can install the package via composer:

```bash
composer require codiceovvio/soundmap
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="soundmap-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="soundmap-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="soundmap-views"
```

## Usage

```php
$soundmap = new CodiceOvvio\Soundmap();
echo $soundmap->echoPhrase('Hello, CodiceOvvio!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Codice Ovvio](https://github.com/codiceovvio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
