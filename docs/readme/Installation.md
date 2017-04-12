Add to required section of your `composer.json`:

```json
"hiqdev/composer-config-plugin": "*"
```

Out of the box this plugin supports configs in PHP and JSON formats.

To enable additional formats require:

- [vlucas/phpdotenv] - for `.env` files
- [symfony/yaml] - for YAML files, `.yml` and `.yaml`

[vlucas/phpdotenv]: https://github.com/vlucas/phpdotenv
[symfony/yaml]: https://github.com/symfony/yaml
