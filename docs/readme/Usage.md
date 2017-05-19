List your config files in `composer.json` like the following:

```json
"extra": {
    "config-plugin": {
        "params": [
            "src/config/params.php",
            "?src/config/params-local.php"
        ],
        "common": "src/config/common.php",
        "web": [
            "$common",
            "src/config/web.php"
        ],
        "other": "src/config/other.php"
    }
},
```

`?` marks optional files, absence of other files will cause exception.

`$common` is inclusion - `common` config will be merged into `web`.

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
$config = require hiqdev\composer\config\Builder::path('web');
```

### Refreshing config

Plugin hangs on composer `POST_AUTOLOAD_DUMP` event.
I.e. composer runs this plugin on `install`, `update` and `dump-autoload`
commands.
As the result configs are just ready to be used after packages installation
or updating.

After you make changes to any of configs you may want to reassemble configs
manually - run:

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
