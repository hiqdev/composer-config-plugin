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

