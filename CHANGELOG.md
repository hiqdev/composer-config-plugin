# hiqdev/composer-config-plugin

## [0.3.0] - 2019-04-11

- Fixed config reading and merging ([@hiqsol])
- Added dev-only configs ([@hiqsol], [@samdark])
- Changed to use `defines` files as is to keep values ([@hiqsol])
- Reworked configuration files building ([@hiqsol], [@marclaporte], [@loveorigami])

## [0.2.5] - 2017-05-19

- Added showing package dependencies hierarchy tree with `composer du -v` ([@hiqsol])

## [0.2.4] - 2017-05-18

- Added proper resolving of config dependencies with `Resolver` class ([@hiqsol])
- Fixed exportVar closures in Windows ([@SilverFire], [@edgardmessias])

## [0.2.3] - 2017-04-18

- Added vendor dir arg to `Builder::path` to get config path at given vendor dir ([@hiqsol])

## [0.2.2] - 2017-04-12

- Improved README ([@hiqsol])
- Added support for `.env`, JSON and YAML ([@hiqsol])

## [0.2.1] - 2017-03-23

- Fixed wrong call of `Composer\Config::get()` ([@SilverFire])

## [0.2.0] - 2017-03-15

- Added initializaion of composer autoloading for project classes become usable in configs ([@hiqsol])
- Added work with `$config_name` paths for use of already built config ([@hiqsol])
- Renamed pathes -> paths everywhere ([@hiqsol])
- Added collecting dev aliases for root package ([@hiqsol])

## [0.1.0] - 2016-12-26

- Added proper rebuild ([@hiqsol])
- Changed output dir to `composer-config-plugin-output` ([@hiqsol])
- Changed: splitted out `Builder` ([@hiqsol])
- Changed namespace to `hiqdev\composer\config` ([@hiqsol])

## [0.0.9] - 2016-09-22

- Fixed infinite loop in case of circular dependencies in composer ([@hiqsol])

## [0.0.8] - 2016-08-27

- Added showing ordered list of packages when verbose option ([@hiqsol])

## [0.0.7] - 2016-08-26

- Fixed packages processing order again, used original `composer.json` ([@hiqsol])

## [0.0.6] - 2016-08-23

- Fixed packages processing order ([@hiqsol])

## [0.0.5] - 2016-06-22

- Added multiple defines ([@hiqsol])

## [0.0.4] - 2016-05-21

- Added multiple configs and params ([@hiqsol])

## [0.0.3] - 2016-05-20

- Changed aliases assembling ([@hiqsol])

## [0.0.2] - 2016-05-19

- Removed replace composer-extension-plugin ([@hiqsol])

## [0.0.1] - 2016-05-18

- Added basics ([@hiqsol])

## [Development started] - 2016-05-18

[@SilverFire]: https://github.com/SilverFire
[d.naumenko.a@gmail.com]: https://github.com/SilverFire
[@tafid]: https://github.com/tafid
[andreyklochok@gmail.com]: https://github.com/tafid
[@BladeRoot]: https://github.com/BladeRoot
[bladeroot@gmail.com]: https://github.com/BladeRoot
[@hiqsol]: https://github.com/hiqsol
[sol@hiqdev.com]: https://github.com/hiqsol
[@edgardmessias]: https://github.com/edgardmessias
[edgardmessias@gmail.com]: https://github.com/edgardmessias
[@samdark]: https://github.com/samdark
[sam@rmcreative.ru]: https://github.com/samdark
[@loveorigami]: https://github.com/loveorigami
[loveorigami@mail.ru]: https://github.com/loveorigami
[@marclaporte]: https://github.com/marclaporte
[marc@laporte.name]: https://github.com/marclaporte
[Under development]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.5...HEAD
[0.0.9]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.8...0.0.9
[0.0.8]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.7...0.0.8
[0.0.7]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.6...0.0.7
[0.0.6]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.5...0.0.6
[0.0.5]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.4...0.0.5
[0.0.4]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.3...0.0.4
[0.0.3]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.2...0.0.3
[0.0.2]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.1...0.0.2
[0.0.1]: https://github.com/hiqdev/composer-config-plugin/releases/tag/0.0.1
[0.1.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.9...0.1.0
[0.2.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.1.0...0.2.0
[0.2.1]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.0...0.2.1
[0.2.2]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.1...0.2.2
[0.2.3]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.2...0.2.3
[0.2.4]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.3...0.2.4
[0.2.5]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.4...0.2.5
[0.3.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.5...0.3.0
