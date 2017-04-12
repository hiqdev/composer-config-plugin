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

Use assembled configs like this:

```php
$config = require hiqdev\composer\config\Builder::path('hisite');
```

### Refreshing config

Plugin hangs on composer `POST_AUTOLOAD_DUMP` event.
I.e. on `install`, `update` and `dump-autoload` commands.

So configs are just ready to use after packages installation
or updating. And to reassemble configs manually run:
```sh
composer dump-autoload
```

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

