# hiqdev/composer-config-plugin

## [0.3.0] - 2019-04-11

- Fixed config reading and merging
    - [efcb091] 2019-04-11 Changed plugin versions to all dev-master [@hiqsol]
    - [597227a] 2019-03-22 Added support for both 2nd and 3rd versions of `phpdotenv` [@hiqsol]
    - [9f61a78] 2019-01-11 Added processing `UnsetArrayValue` and `ReplaceArrayValue`, allows to fix #20 [@hiqsol]
    - [83bc091] 2019-01-11 Added substitution and files reordering, fixes #19 [@hiqsol]
    - [0c8c821] 2018-12-20 Added `Plugin::addFile()` and used in loadDotEnv to prevent dublicating [@hiqsol]
- Added dev-only configs
    - [959c971] 2018-12-08 Added `config-plugin-dev` [@hiqsol]
    - [faab5ed] 2018-12-05 Tuned output to fit composer 1.8+ [@hiqsol]
    - [49f65f7] 2018-11-15 Added skiping repeated values [@hiqsol]
    - [bf49430] 2018-10-08 Code style fixes (#16) [@samdark]
    - [e3e4f78] 2018-10-07 There's no need to duplicate docs since they're all in the readme [@samdark]
    - [f4ef5d2] 2018-10-07 More consistency [@samdark]
    - [1d807d0] 2018-10-07 Improved readme [@samdark]
    - [2543887] 2018-10-07 Explicitly declare PHP 7 requirement in composer.json [@samdark]
    - [5cbfdac] 2018-10-07 Fix wrong constructor call arguments [@samdark]
    - [99c5696] 2018-09-08 Added `COMPOSER_CONFIG_PLUGIN_BASEDIR` constant definition to generated configs [@hiqsol]
- Changed to use `defines` files as is to keep values
    - [34cfa36] 2018-08-24 Fixed writing `defines` with added merging env vars [@hiqsol]
    - [3ec43a4] 2018-08-23 Changed `Defines` builder to `require_once` instead of require [@hiqsol]
    - [7e6e4fa] 2018-08-13 Redone `defines` building, require all instead of assembling [@hiqsol]
    - [6d2c277] 2018-08-03 Added `DotEnv` class, no defines and dotenv needed for dotenv [@hiqsol]
- Reworked configuration files building
    - [ed34c6e] 2018-08-23 csfixed [@hiqsol]
    - [faf6cec] 2018-08-22 Added more type hinting [@hiqsol]
    - [8ba86d9] 2018-08-08 Collecting all `packages` instead of yii2-extensions only [@hiqsol]
    - [462c616] 2018-08-08 Removed check for `yii2-extension`, collecting aliases from all packages [@hiqsol]
    - [7534700] 2018-08-04 Added `1.0.x-dev` branch-alias [@hiqsol]
    - [22965f1] 2018-08-03 csfixed [@hiqsol]
    - [1d78abf] 2018-08-03 Added merging ENV vars in normal configs [@hiqsol]
    - [e88f547] 2018-08-01 Fixed `System::load()` to allow file not exist [@hiqsol]
    - [19b80be] 2018-07-31 Fixed `Builder::rebuild()` by fixing aliases loading [@hiqsol]
    - [3c7654c] 2018-07-31 Allowed return null in reference functions [@hiqsol]
    - [39fc269] 2018-07-31 Changed recommended config path to `config` (was src/config) [@hiqsol]
    - [03d7359] 2018-07-31 csfixed [@hiqsol]
    - [f73714d] 2018-07-31 Added lot of phpdocs [@hiqsol]
    - [3378133] 2018-07-31 Extracted package logic to `Package` class: prefer data from `composer.json` [@hiqsol]
    - [4e4f6b9] 2018-07-30 Added `suggest` section to composer.json [@hiqsol]
    - [8273a37] 2018-07-30 Moved aliases and extensions building to `Builder` [@hiqsol]
    - [5f84f43] 2018-07-30 Removed `__addition` config and `Builder::files` , passing files explicitly [@hiqsol]
    - [1d52609] 2018-07-30 Added `System` config `setValue()`, `mergeValues()` and `build()` [@hiqsol]
    - [5ccb875] 2018-07-30 Removed expired `Yii.php` loading stuff [@hiqsol]
    - [eba7d87] 2018-07-30 Removed `io` from `Builder` [@hiqsol]
    - [b8e8baf] 2018-07-28 Extracted `Config::renderVars()` method [@hiqsol]
    - [117b250] 2018-07-28 Removed `isSpecialConfig` (cleaning up) [@hiqsol]
    - [540be97] 2018-07-25 Fixed `substitutePath()` to substitute also exact path again [@hiqsol]
    - [1df0194] 2018-07-25 Merged rework [@hiqsol]
    - [349da2d] 2018-07-25 HUGE refactoring out config classes [@hiqsol]
    - [b08f320] 2018-07-25 Added tests for readers factory [@hiqsol]
    - [c2d1320] 2018-07-25 Refactored readers to have builder [@hiqsol]
    - [f4fd70b] 2018-07-25 Fixed `Helper::mergeConfig()` for empty arguments list [@hiqsol]
    - [8a9d349] 2018-07-25 Moved `ReaderFactory` to readers dir [@hiqsol]
    - [c02b3cd] 2018-07-25 csfixed [@hiqsol]
    - [5596670] 2018-07-20 Fixed `Builder::substitutePath()` to substitute also exact path [@hiqsol]
    - [77aa87a] 2018-07-10 Fixed collecting files list: adding only unique [@hiqsol]
    - [c3e0699] 2018-07-10 renamed application to `app` [@hiqsol]
    - [05af3cc] 2018-07-08 trying yii 3.0 version [@hiqsol]
    - [8304c24] 2018-07-08 removed unused `$rawParams` [@hiqsol]
    - [14a42b0] 2018-01-29 Merge pull request #5 from marclaporte/patch-1 [@hiqsol]
    - [af29b9b] 2018-01-27 Fix a typo [@marclaporte]
    - [b1a84e7] 2018-01-27 csfixed [@hiqsol]
    - [6796608] 2017-11-30 still fixing to work in Windows [@hiqsol]
    - [6621a38] 2017-11-30 Merge pull request #4 from loveorigami/patch-1 [@hiqsol]
    - [2e358df] 2017-11-30 normalizeDir for windows path [@loveorigami]
    - [167ae38] 2017-11-30 quickfixed to `normalizePath` to force unix directory separator for substitutions to work in Windows [@hiqsol]
    - [4c7e79d] 2017-11-19 added `normalizePath` to convert Windows backslashes to normal slashes [@hiqsol]
    - [fdc740a] 2017-10-17 csfixed [@hiqsol]
    - [2b9795d] 2017-10-17 fixed expects array error [@hiqsol]
    - [05fff11] 2017-10-17 added pushing env vars into params with `pushEnvVars` [@hiqsol]
    - [35823f1] 2017-09-27 disabled require Yii.php because it sets `Yii_` constants wrong [@hiqsol]
    - [32105a5] 2017-09-27 added require Yii in `Plugin::initAutoload` [@hiqsol]
    - [67bf230] 2017-09-27 csfixed [@hiqsol]
    - [bea1b98] 2017-09-27 switched to phpunit 6 [@hiqsol]
    - [078c488] 2017-09-27 added links to app-organization article [@hiqsol]

## [0.2.5] - 2017-05-19

- Added showing package dependencies hierarchy tree with `composer du -v`
    - [a08ff85] 2017-05-19 csfixed [@hiqsol]
    - [f6b00f4] 2017-05-19 docs [@hiqsol]
    - [37dcf77] 2017-05-19 improved tree colors [@hiqsol]
    - [3ddd313] 2017-05-19 added showing packages hierarchy tree with `showDepsTree()` [@hiqsol]
    - [aaa59c6] 2017-05-19 docs [@hiqsol]

## [0.2.4] - 2017-05-18

- Added proper resolving of config dependencies with `Resolver` class
    - [4889e11] 2017-05-18 removed phpunit 6 compatibility hack [@hiqsol]
    - [b38c0f5] 2017-05-11 csfixed [@hiqsol]
    - [bea8462] 2017-05-11 renamed `hidev.yml` <- .hidev/config.yml [@hiqsol]
    - [a0f372d] 2017-05-11 added proper resolving of config dependencies with `Resolver` class [@hiqsol]
- Fixed exportVar closures in Windows
    - [b411092] 2017-04-28 Merge pull request #1 from edgardmessias/patch-1 [@SilverFire]
    - [42cf9ad] 2017-04-28 Fixed exportVar closures in Windows Environment [@edgardmessias]

## [0.2.3] - 2017-04-18

- Added vendor dir arg to `Builder::path` to get config path at given vendor dir
    - [ed7f586] 2017-04-18 csfixed [@hiqsol]
    - [06c9079] 2017-04-18 added vendor dir optional argument to `Builder::path` [@hiqsol]

## [0.2.2] - 2017-04-12

- Improved README
    - [2920c4e] 2017-04-12 added Known issues to readme [@hiqsol]
    - [4a317e4] 2017-04-12 greatly improved docs [@hiqsol]
- Added support for `.env`, JSON and YAML
    - [43c0d3c] 2017-04-12 refactored out readers and ReaderFactory [@hiqsol]
    - [8ec1461] 2017-04-12 refactored exceptions [@hiqsol]
    - [e89b1ab] 2017-04-12 added saving loaded constants to `defines` [@hiqsol]
    - [94d957b] 2017-04-12 added saving loaded env vars [@hiqsol]
    - [a379609] 2017-04-12 added Collection stub [@hiqsol]
    - [dc0397a] 2017-04-11 changed `Plugin::addFiles`: renamed from processFiles and reversed order of `defines` [@hiqsol]
    - [66521da] 2017-04-05 changed dotenv loading to common used behavior: shell environment will override those set in the `.env` [@hiqsol]
    - [a490575] 2017-04-04 added support for JSON and YAML [@hiqsol]
    - [d11c2bb] 2017-04-04 added support for `.env` [@hiqsol]

## [0.2.1] - 2017-03-23

- Fixed wrong call of `Composer\Config::get()`
    - [57e403a] 2017-03-23 csfixed [@SilverFire]
    - [33192bb] 2017-03-23 Added PHPUnit 6 compatibility [@SilverFire]
    - [d32060d] 2017-03-23 Updated travis config [@SilverFire]
    - [e1bea13] 2017-03-23 Fixed wrong call of composer\config::get() [@SilverFire]

## [0.2.0] - 2017-03-15

- Added initializaion of composer autoloading for project classes become usable in configs
    - [e1d9513] 2017-03-15 added initAutoload [@hiqsol]
- Added work with `$config_name` paths for use of already built config
    - [4555b54] 2017-03-09 csfixed [@hiqsol]
    - [9bba22d] 2017-03-09 added `$config` paths to include built configs [@hiqsol]
    - [5f3d05c] 2017-03-08 minor: grammarnazi pathes -> paths [@hiqsol]
    - [6d3fb2b] 2017-02-02 csfixed [@hiqsol]
    - [a8f42dd] 2017-02-02 return defines back to default list of files [@hiqsol]
- Renamed pathes -> paths everywhere
    - [82a0652] 2017-02-02 renamed paths <- pathes [@hiqsol]
    - [c13991e] 2017-02-02 added exporting defines [@hiqsol]
    - [864f8b1] 2017-01-12 renamed `Builder::substitutePaths` <- substitutePathes [@hiqsol]
    - [2d68985] 2016-12-31 doc [@hiqsol]
- Added collecting dev aliases for root package
    - [49f229b] 2016-12-26 + collect dev aliases for root package [@hiqsol]

## [0.1.0] - 2016-12-26

- Added proper rebuild
    - [623d741] 2016-12-26 add FailedWriteException [@hiqsol]
    - [c71fd6f] 2016-12-26 fixed fileGet when file does not exist [@hiqsol]
    - [02019cd] 2016-12-26 csfixed work with skippable [@hiqsol]
    - [9f4362e] 2016-12-26 + proper propagating skippable sign [@hiqsol]
    - [778096f] 2016-12-26 + putFile: checks if content changed before writing [@hiqsol]
    - [42ffadd] 2016-12-26 fixed namespace in tests [@hiqsol]
    - [4554fdd] 2016-12-26 csfixed [@hiqsol]
    - [333717a] 2016-12-26 + use dev self for .hidev/vendor [@hiqsol]
    - [e61e88b] 2016-12-26 + copying `__rebuild` script [@hiqsol]
- Changed output dir to `composer-config-plugin-output`
    - [2a4a539] 2016-12-26 CHANGED output dir to `composer-config-plugin-output` [@hiqsol]
    - [ec65010] 2016-12-26 changed: `path()` and substituting pathes moved into Builder [@hiqsol]
    - [436f10d] 2016-12-25 added work with `addition` [@hiqsol]
- Changed: splitted out `Builder`
    - [b85299b] 2016-12-25 basically finished splitting out Builder [@hiqsol]
    - [c53bb32] 2016-12-24 + `Helper::className()` [@hiqsol]
- Changed namespace to `hiqdev\composer\config`
    - [ab86c3c] 2016-12-24 started BIG redoing: changed namespace, added Builder, output to other dir [@hiqsol]
    - [e447659] 2016-12-23 doc [@hiqsol]
    - [e1565a6] 2016-10-04 removed use of `::class` to be older php compatible [@hiqsol]

## [0.0.9] - 2016-09-22

- Fixed infinite loop in case of circular dependencies in composer
    - [434673d] 2016-09-22 fixed: prevented infinite loop in case of circular dependencies [@hiqsol]
    - [5b6b30e] 2016-09-14 improved readme [@hiqsol]

## [0.0.8] - 2016-08-27

- Added showing ordered list of packages when verbose option
    - [5de8257] 2016-08-27 added showing list of packages if verbose [@hiqsol]

## [0.0.7] - 2016-08-26

- Fixed packages processing order again, used original `composer.json`
    - [a9c0ba1] 2016-08-26 fixed scrutinizer issues [@hiqsol]
    - [cc15516] 2016-08-25 redone iterateDependencies and used getPrettyName() instead of getName() [@hiqsol]

## [0.0.6] - 2016-08-23

- Fixed packages processing order
    - [c4bf7f9] 2016-08-23 more ideas [@hiqsol]
    - [8340080] 2016-08-23 + require-dev phpunit 5.5 [@hiqsol]
    - [e08e6c7] 2016-08-23 fixed tests for composer 5.5 [@hiqsol]
    - [94284df] 2016-08-23 csfixed [@hiqsol]
    - [2faafaa] 2016-08-23 redone to chkipper for bumping [@hiqsol]
    - [0e4f55b] 2016-08-23 added fixed packages processing order [@hiqsol]

## [0.0.5] - 2016-06-22

- Added multiple defines
    - [e58cc7a] 2016-06-22 allowed travis build failure for PHP 5.5 [@hiqsol]
    - [5b84dc8] 2016-06-22 added ability to have multiple defines [@hiqsol]
    - [827d606] 2016-05-22 csfixed [@hiqsol]

## [0.0.4] - 2016-05-21

- Added multiple configs and params
    - [e9c4899] 2016-05-21 forced arrays [@hiqsol]
    - [d1fdc77] 2016-05-20 + added multiple configs and params [@hiqsol]

## [0.0.3] - 2016-05-20

- Changed aliases assembling
    - [1076668] 2016-05-20 csfixed [@hiqsol]
    - [174c848] 2016-05-19 simplified aliases assembling [@hiqsol]
    - [5f232e4] 2016-05-19 improved Idea [@hiqsol]
    - [a976f3e] 2016-05-19 added Idea readme section [@hiqsol]

## [0.0.2] - 2016-05-19

- Removed replace composer-extension-plugin
    - [0a3d1a6] 2016-05-19 removed replace composer-extension-plugin [@hiqsol]

## [0.0.1] - 2016-05-18

- Added basics
    - [15e92b4] 2016-05-18 fixed getting baseDir [@hiqsol]
    - [ec3bda1] 2016-05-18 rehideved [@hiqsol]
    - [470dc87] 2016-05-18 looks working [@hiqsol]
    - [65d1a3e] 2016-05-18 redoing [@hiqsol]
    - [927a73f] 2016-05-18 + replace composer-extension-plugin [@hiqsol]
    - [6270475] 2016-05-18 redoing [@hiqsol]
    - [79b5c49] 2016-05-18 inited [@hiqsol]

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
[e58cc7a]: https://github.com/hiqdev/composer-config-plugin/commit/e58cc7a
[5b84dc8]: https://github.com/hiqdev/composer-config-plugin/commit/5b84dc8
[827d606]: https://github.com/hiqdev/composer-config-plugin/commit/827d606
[e9c4899]: https://github.com/hiqdev/composer-config-plugin/commit/e9c4899
[d1fdc77]: https://github.com/hiqdev/composer-config-plugin/commit/d1fdc77
[1076668]: https://github.com/hiqdev/composer-config-plugin/commit/1076668
[174c848]: https://github.com/hiqdev/composer-config-plugin/commit/174c848
[5f232e4]: https://github.com/hiqdev/composer-config-plugin/commit/5f232e4
[a976f3e]: https://github.com/hiqdev/composer-config-plugin/commit/a976f3e
[0a3d1a6]: https://github.com/hiqdev/composer-config-plugin/commit/0a3d1a6
[15e92b4]: https://github.com/hiqdev/composer-config-plugin/commit/15e92b4
[ec3bda1]: https://github.com/hiqdev/composer-config-plugin/commit/ec3bda1
[470dc87]: https://github.com/hiqdev/composer-config-plugin/commit/470dc87
[65d1a3e]: https://github.com/hiqdev/composer-config-plugin/commit/65d1a3e
[927a73f]: https://github.com/hiqdev/composer-config-plugin/commit/927a73f
[6270475]: https://github.com/hiqdev/composer-config-plugin/commit/6270475
[79b5c49]: https://github.com/hiqdev/composer-config-plugin/commit/79b5c49
[0e4f55b]: https://github.com/hiqdev/composer-config-plugin/commit/0e4f55b
[c4bf7f9]: https://github.com/hiqdev/composer-config-plugin/commit/c4bf7f9
[8340080]: https://github.com/hiqdev/composer-config-plugin/commit/8340080
[e08e6c7]: https://github.com/hiqdev/composer-config-plugin/commit/e08e6c7
[94284df]: https://github.com/hiqdev/composer-config-plugin/commit/94284df
[2faafaa]: https://github.com/hiqdev/composer-config-plugin/commit/2faafaa
[cc15516]: https://github.com/hiqdev/composer-config-plugin/commit/cc15516
[a9c0ba1]: https://github.com/hiqdev/composer-config-plugin/commit/a9c0ba1
[5de8257]: https://github.com/hiqdev/composer-config-plugin/commit/5de8257
[434673d]: https://github.com/hiqdev/composer-config-plugin/commit/434673d
[5b6b30e]: https://github.com/hiqdev/composer-config-plugin/commit/5b6b30e
[c71fd6f]: https://github.com/hiqdev/composer-config-plugin/commit/c71fd6f
[02019cd]: https://github.com/hiqdev/composer-config-plugin/commit/02019cd
[9f4362e]: https://github.com/hiqdev/composer-config-plugin/commit/9f4362e
[778096f]: https://github.com/hiqdev/composer-config-plugin/commit/778096f
[42ffadd]: https://github.com/hiqdev/composer-config-plugin/commit/42ffadd
[4554fdd]: https://github.com/hiqdev/composer-config-plugin/commit/4554fdd
[333717a]: https://github.com/hiqdev/composer-config-plugin/commit/333717a
[e61e88b]: https://github.com/hiqdev/composer-config-plugin/commit/e61e88b
[2a4a539]: https://github.com/hiqdev/composer-config-plugin/commit/2a4a539
[ec65010]: https://github.com/hiqdev/composer-config-plugin/commit/ec65010
[436f10d]: https://github.com/hiqdev/composer-config-plugin/commit/436f10d
[b85299b]: https://github.com/hiqdev/composer-config-plugin/commit/b85299b
[c53bb32]: https://github.com/hiqdev/composer-config-plugin/commit/c53bb32
[ab86c3c]: https://github.com/hiqdev/composer-config-plugin/commit/ab86c3c
[e447659]: https://github.com/hiqdev/composer-config-plugin/commit/e447659
[e1565a6]: https://github.com/hiqdev/composer-config-plugin/commit/e1565a6
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
[623d741]: https://github.com/hiqdev/composer-config-plugin/commit/623d741
[0.1.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.0.9...0.1.0
[e1d9513]: https://github.com/hiqdev/composer-config-plugin/commit/e1d9513
[4555b54]: https://github.com/hiqdev/composer-config-plugin/commit/4555b54
[9bba22d]: https://github.com/hiqdev/composer-config-plugin/commit/9bba22d
[5f3d05c]: https://github.com/hiqdev/composer-config-plugin/commit/5f3d05c
[6d3fb2b]: https://github.com/hiqdev/composer-config-plugin/commit/6d3fb2b
[a8f42dd]: https://github.com/hiqdev/composer-config-plugin/commit/a8f42dd
[82a0652]: https://github.com/hiqdev/composer-config-plugin/commit/82a0652
[c13991e]: https://github.com/hiqdev/composer-config-plugin/commit/c13991e
[864f8b1]: https://github.com/hiqdev/composer-config-plugin/commit/864f8b1
[2d68985]: https://github.com/hiqdev/composer-config-plugin/commit/2d68985
[49f229b]: https://github.com/hiqdev/composer-config-plugin/commit/49f229b
[0.2.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.1.0...0.2.0
[e1bea13]: https://github.com/hiqdev/composer-config-plugin/commit/e1bea13
[0.2.1]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.0...0.2.1
[57e403a]: https://github.com/hiqdev/composer-config-plugin/commit/57e403a
[33192bb]: https://github.com/hiqdev/composer-config-plugin/commit/33192bb
[d32060d]: https://github.com/hiqdev/composer-config-plugin/commit/d32060d
[2920c4e]: https://github.com/hiqdev/composer-config-plugin/commit/2920c4e
[43c0d3c]: https://github.com/hiqdev/composer-config-plugin/commit/43c0d3c
[8ec1461]: https://github.com/hiqdev/composer-config-plugin/commit/8ec1461
[e89b1ab]: https://github.com/hiqdev/composer-config-plugin/commit/e89b1ab
[94d957b]: https://github.com/hiqdev/composer-config-plugin/commit/94d957b
[a379609]: https://github.com/hiqdev/composer-config-plugin/commit/a379609
[4a317e4]: https://github.com/hiqdev/composer-config-plugin/commit/4a317e4
[dc0397a]: https://github.com/hiqdev/composer-config-plugin/commit/dc0397a
[66521da]: https://github.com/hiqdev/composer-config-plugin/commit/66521da
[a490575]: https://github.com/hiqdev/composer-config-plugin/commit/a490575
[d11c2bb]: https://github.com/hiqdev/composer-config-plugin/commit/d11c2bb
[0.2.2]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.1...0.2.2
[ed7f586]: https://github.com/hiqdev/composer-config-plugin/commit/ed7f586
[06c9079]: https://github.com/hiqdev/composer-config-plugin/commit/06c9079
[0.2.3]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.2...0.2.3
[4889e11]: https://github.com/hiqdev/composer-config-plugin/commit/4889e11
[b38c0f5]: https://github.com/hiqdev/composer-config-plugin/commit/b38c0f5
[bea8462]: https://github.com/hiqdev/composer-config-plugin/commit/bea8462
[a0f372d]: https://github.com/hiqdev/composer-config-plugin/commit/a0f372d
[b411092]: https://github.com/hiqdev/composer-config-plugin/commit/b411092
[42cf9ad]: https://github.com/hiqdev/composer-config-plugin/commit/42cf9ad
[0.2.4]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.3...0.2.4
[a08ff85]: https://github.com/hiqdev/composer-config-plugin/commit/a08ff85
[f6b00f4]: https://github.com/hiqdev/composer-config-plugin/commit/f6b00f4
[37dcf77]: https://github.com/hiqdev/composer-config-plugin/commit/37dcf77
[3ddd313]: https://github.com/hiqdev/composer-config-plugin/commit/3ddd313
[aaa59c6]: https://github.com/hiqdev/composer-config-plugin/commit/aaa59c6
[0.2.5]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.4...0.2.5
[67bf230]: https://github.com/hiqdev/composer-config-plugin/commit/67bf230
[bea1b98]: https://github.com/hiqdev/composer-config-plugin/commit/bea1b98
[078c488]: https://github.com/hiqdev/composer-config-plugin/commit/078c488
[34cfa36]: https://github.com/hiqdev/composer-config-plugin/commit/34cfa36
[3ec43a4]: https://github.com/hiqdev/composer-config-plugin/commit/3ec43a4
[ed34c6e]: https://github.com/hiqdev/composer-config-plugin/commit/ed34c6e
[faf6cec]: https://github.com/hiqdev/composer-config-plugin/commit/faf6cec
[7e6e4fa]: https://github.com/hiqdev/composer-config-plugin/commit/7e6e4fa
[8ba86d9]: https://github.com/hiqdev/composer-config-plugin/commit/8ba86d9
[462c616]: https://github.com/hiqdev/composer-config-plugin/commit/462c616
[7534700]: https://github.com/hiqdev/composer-config-plugin/commit/7534700
[6d2c277]: https://github.com/hiqdev/composer-config-plugin/commit/6d2c277
[22965f1]: https://github.com/hiqdev/composer-config-plugin/commit/22965f1
[1d78abf]: https://github.com/hiqdev/composer-config-plugin/commit/1d78abf
[e88f547]: https://github.com/hiqdev/composer-config-plugin/commit/e88f547
[19b80be]: https://github.com/hiqdev/composer-config-plugin/commit/19b80be
[3c7654c]: https://github.com/hiqdev/composer-config-plugin/commit/3c7654c
[39fc269]: https://github.com/hiqdev/composer-config-plugin/commit/39fc269
[03d7359]: https://github.com/hiqdev/composer-config-plugin/commit/03d7359
[f73714d]: https://github.com/hiqdev/composer-config-plugin/commit/f73714d
[3378133]: https://github.com/hiqdev/composer-config-plugin/commit/3378133
[4e4f6b9]: https://github.com/hiqdev/composer-config-plugin/commit/4e4f6b9
[8273a37]: https://github.com/hiqdev/composer-config-plugin/commit/8273a37
[5f84f43]: https://github.com/hiqdev/composer-config-plugin/commit/5f84f43
[1d52609]: https://github.com/hiqdev/composer-config-plugin/commit/1d52609
[5ccb875]: https://github.com/hiqdev/composer-config-plugin/commit/5ccb875
[eba7d87]: https://github.com/hiqdev/composer-config-plugin/commit/eba7d87
[b8e8baf]: https://github.com/hiqdev/composer-config-plugin/commit/b8e8baf
[117b250]: https://github.com/hiqdev/composer-config-plugin/commit/117b250
[540be97]: https://github.com/hiqdev/composer-config-plugin/commit/540be97
[1df0194]: https://github.com/hiqdev/composer-config-plugin/commit/1df0194
[349da2d]: https://github.com/hiqdev/composer-config-plugin/commit/349da2d
[b08f320]: https://github.com/hiqdev/composer-config-plugin/commit/b08f320
[c2d1320]: https://github.com/hiqdev/composer-config-plugin/commit/c2d1320
[f4fd70b]: https://github.com/hiqdev/composer-config-plugin/commit/f4fd70b
[8a9d349]: https://github.com/hiqdev/composer-config-plugin/commit/8a9d349
[c02b3cd]: https://github.com/hiqdev/composer-config-plugin/commit/c02b3cd
[5596670]: https://github.com/hiqdev/composer-config-plugin/commit/5596670
[77aa87a]: https://github.com/hiqdev/composer-config-plugin/commit/77aa87a
[c3e0699]: https://github.com/hiqdev/composer-config-plugin/commit/c3e0699
[05af3cc]: https://github.com/hiqdev/composer-config-plugin/commit/05af3cc
[8304c24]: https://github.com/hiqdev/composer-config-plugin/commit/8304c24
[14a42b0]: https://github.com/hiqdev/composer-config-plugin/commit/14a42b0
[af29b9b]: https://github.com/hiqdev/composer-config-plugin/commit/af29b9b
[b1a84e7]: https://github.com/hiqdev/composer-config-plugin/commit/b1a84e7
[6796608]: https://github.com/hiqdev/composer-config-plugin/commit/6796608
[6621a38]: https://github.com/hiqdev/composer-config-plugin/commit/6621a38
[2e358df]: https://github.com/hiqdev/composer-config-plugin/commit/2e358df
[167ae38]: https://github.com/hiqdev/composer-config-plugin/commit/167ae38
[4c7e79d]: https://github.com/hiqdev/composer-config-plugin/commit/4c7e79d
[fdc740a]: https://github.com/hiqdev/composer-config-plugin/commit/fdc740a
[2b9795d]: https://github.com/hiqdev/composer-config-plugin/commit/2b9795d
[05fff11]: https://github.com/hiqdev/composer-config-plugin/commit/05fff11
[35823f1]: https://github.com/hiqdev/composer-config-plugin/commit/35823f1
[32105a5]: https://github.com/hiqdev/composer-config-plugin/commit/32105a5
[efcb091]: https://github.com/hiqdev/composer-config-plugin/commit/efcb091
[597227a]: https://github.com/hiqdev/composer-config-plugin/commit/597227a
[9f61a78]: https://github.com/hiqdev/composer-config-plugin/commit/9f61a78
[83bc091]: https://github.com/hiqdev/composer-config-plugin/commit/83bc091
[0c8c821]: https://github.com/hiqdev/composer-config-plugin/commit/0c8c821
[959c971]: https://github.com/hiqdev/composer-config-plugin/commit/959c971
[faab5ed]: https://github.com/hiqdev/composer-config-plugin/commit/faab5ed
[49f65f7]: https://github.com/hiqdev/composer-config-plugin/commit/49f65f7
[bf49430]: https://github.com/hiqdev/composer-config-plugin/commit/bf49430
[e3e4f78]: https://github.com/hiqdev/composer-config-plugin/commit/e3e4f78
[f4ef5d2]: https://github.com/hiqdev/composer-config-plugin/commit/f4ef5d2
[1d807d0]: https://github.com/hiqdev/composer-config-plugin/commit/1d807d0
[2543887]: https://github.com/hiqdev/composer-config-plugin/commit/2543887
[5cbfdac]: https://github.com/hiqdev/composer-config-plugin/commit/5cbfdac
[99c5696]: https://github.com/hiqdev/composer-config-plugin/commit/99c5696
[0.3.0]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.5...0.3.0
