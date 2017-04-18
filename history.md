# hiqdev/composer-config-plugin

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
[Under development]: https://github.com/hiqdev/composer-config-plugin/compare/0.2.2...HEAD
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
