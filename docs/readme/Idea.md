Not yet completely implemented.

Collects config files in configured order.

```json
"extra": {
    "config-plugin": {
        "defines": "src/config/defines.php",
        "params":  "src/config/params.php",
        "common": {
            "params": "params",
            "aliases": "aliases"
        },
        "hisite": "common",
        "hidev": "common",
        "hisite-dev": "hisite",
        "hisite-prod": "hisite"
    }
}
```
