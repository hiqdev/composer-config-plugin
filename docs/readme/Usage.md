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

