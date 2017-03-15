# Composer Config Plugin

**Composer plugin for config assembling**

[![Latest Stable Version](https://poser.pugx.org/hiqdev/composer-config-plugin/v/stable)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Total Downloads](https://poser.pugx.org/hiqdev/composer-config-plugin/downloads)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Build Status](https://img.shields.io/travis/hiqdev/composer-config-plugin.svg)](https://travis-ci.org/hiqdev/composer-config-plugin)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Dependency Status](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master/badge.svg)](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master)

This [Composer](https://getcomposer.org/) plugin provides assembling
of configurations distributed with composer packages.
This allows to put configuration needed to use package right into
the package thus implementing plugin system: package becomes a plugin
holding both code and configuration.

How it works?

- scans installed packages for `config-plugin` extra option in their `composer.json`
- requires all `defines` files to set constants
- collects and writes `params` file (constants can be used in params)
- collects and writes config files (constants and params can be used in configs)
- then you load assembled configurations with `require`

## Installation

Add to require section of your `composer.json`:

```json
    "hiqdev/composer-config-plugin": "*"
```

## Usage

List your config files in `composer.json` like this:

```json
"extra": {
    "config-plugin": {
        "defines": [
            "?src/config/defines-local.php",
            "src/config/defines.php"
        ],
        "params": [
            "src/config/params.php",
            "?src/config/params-local.php"
        ],
        "hisite": "src/config/hisite.php",
        "other": "src/config/other.php"
    }
},
```

Run `composer dump-autoload` to reassemble configs.

Use assembled configs like this:

```php
use hiqdev\composer\config\Builder;

if (ENVIRONMENT == 'dev') {
    Builder::rebuild();
}

$config = require(Builder::path('hisite'));
```

## TODO

- accept config files in different formats: JSON, YML, XML

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2016-2017, HiQDev (http://hiqdev.com/)
