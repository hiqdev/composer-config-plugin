Not yet completely implemented.

- accept config files in different formats: PHP, JSON, YML
- collect config files in configured order and structures

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

