# Composer Config Plugin

**Composer plugin for config assembling**

[![Latest Stable Version](https://poser.pugx.org/hiqdev/composer-config-plugin/v/stable)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Total Downloads](https://poser.pugx.org/hiqdev/composer-config-plugin/downloads)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Build Status](https://img.shields.io/travis/hiqdev/composer-config-plugin.svg)](https://travis-ci.org/hiqdev/composer-config-plugin)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Dependency Status](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master/badge.svg)](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master)

This [Composer] plugin provides assembling
of configurations distributed with composer packages.
This allows to put configuration needed to use package right inside of
the package thus implementing plugin system: package becomes a plugin
holding both the code and it's configuration.

How it works?

- scans installed packages for `config-plugin` extra option in their
  `composer.json`
- loads `dotenv` files to set `$_ENV` variables
- requires `defines` files to set constants
- requires `params` files
- requires config files
- options collected on earlier steps could and should be used in later
  steps, e.g. `$_ENV` should be used for constants and parameters, which
  in turn should be used for configs
- files processing order is crucial to achieve expected behavior: options
  in root package have priority over options from included packages, more
  about it later in **Files processing order** section
- collected configs are written as PHP files in
  `vendor/hiqdev/composer-config-plugin-output`
  directory together with information needed to rebuild configs on demand
- then assembled configs can be loaded into application with `require`

[composer]: https://getcomposer.org/

## Installation

Add to require section of your `composer.json`:

```json
"hiqdev/composer-config-plugin": "*"
```

Out of the box this plugin supports configs in PHP and JSON formats.

To enable additional formats require:

- [vlucas/phpdotenv] - for `.env` files
- [symfony/yaml] - for YAML files, `.yml` and `.yaml`

[vlucas/phpdotenv]: https://github.com/vlucas/phpdotenv
[symfony/yaml]: https://github.com/symfony/yaml

## Usage

List your config files in `composer.json` like the following:

```json
"extra": {
    "config-plugin": {
        "params": [
            "src/config/params.php",
            "?src/config/params-local.php"
        ],
        "hisite": "src/config/hisite.php",
        "other": "src/config/other.php"
    }
},
```

`?` marks optional files, absence of other files will cause exception.

Define your configs like this:

```php
return [
    'components' => [
        'db' => [
            'class' => \my\Db::class,
            'name' => $params['db.name'],
            'password' => $params['db.password'],
        ],
    ],
];
```

To load assembled configs in your application use require:

```php
$config = require hiqdev\composer\config\Builder::path('hisite');
```

### Refreshing config

Plugin hangs on composer `POST_AUTOLOAD_DUMP` event.
I.e. composer runs this plugin on `install`, `update` and `dump-autoload`
commands.
As the result configs are just ready to use after packages installation
or updating. To reassemble configs manually run:

```sh
composer dump-autoload
```

Can be shortened to `composer du`.

Also, you can force config rebuild from your application like this:

```php
// Don't do it in production, assembling takes it's time
if (ENVIRONMENT === 'dev') {
    hiqdev\composer\config\Builder::rebuild();
}
```

### Files processing order

Config files are processed in proper order to achieve naturally expected
behavior:

- options in outer packages override options from inner packages
- plugin respects order your configs are listed in `composer.json`
- different types of options are processed in this order:
    - environment variables from `dotenv`
    - constants from `defines`
    - parameters from `params`
    - configs are processed last of all

## Known issues

This plugin treats configs as simple PHP arrays, no specific
structure or semantics are expected and handled.
It is simple and straightforward, but I'm in doubt...
What about errors and typos?
I think about adding config validation rules provided together with
plugins. Will it solve all the problems?

Anonymous functions must be used in multiline form only:

```php
return [
    'works' => function () {
        return 'value';
    },
    // this will not work
    'noway' => function () { return 'value'; },
];
```

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2016-2017, HiQDev (http://hiqdev.com/)
