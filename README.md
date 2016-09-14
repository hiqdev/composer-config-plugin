Composer Config Plugin
======================

**Composer plugin for config assembling**

[![Latest Stable Version](https://poser.pugx.org/hiqdev/composer-config-plugin/v/stable)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Total Downloads](https://poser.pugx.org/hiqdev/composer-config-plugin/downloads)](https://packagist.org/packages/hiqdev/composer-config-plugin)
[![Build Status](https://img.shields.io/travis/hiqdev/composer-config-plugin.svg)](https://travis-ci.org/hiqdev/composer-config-plugin)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hiqdev/composer-config-plugin.svg)](https://scrutinizer-ci.com/g/hiqdev/composer-config-plugin/)
[![Dependency Status](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master/badge.svg)](https://www.versioneye.com/php/hiqdev:composer-config-plugin/dev-master)

This [Composer](https://getcomposer.org/) plugin that provides assembling of configs
and thus providing extendable plugin system.

- scans installed packages for extra `config-plugin` option in their `composer.json`
- requires all defines files
- collects and writes params file
- collects and writes config files

## Installation

Add to require section of your `composer.json`:

```json
    "hiqdev/composer-config-plugin": "*"
```

## Usage

Define your config files in `composer.json` like this:

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
            "hisite": "src/config/hisite.php"
        }
    },
```

Run `composer dump-autoload` to reassemble configs.

Use assembled configs like this:

```php

$config = VENDOR_DIR . '/hiqdev/config/hisite.php';

```

## Ideas to be implemented later

Not yet completely implemented.

- accept config files in different formats: PHP, JSON, YML
- define order and structure of assembled config files

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2016, HiQDev (http://hiqdev.com/)
