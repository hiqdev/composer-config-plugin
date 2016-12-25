<?php

/*
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use Composer\IO\IOInterface;

/**
 * Builder assembles config files.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Builder
{
    /**
     * @var string path to output assembled configs
     */
    protected $outputDir;

    /**
     * @var array files to process: config name => list of files
     */
    protected $files = [];

    /**
     * @var array collected variables
     */
    protected $vars = [];

    const BASE_DIR_SAMPLE = '<base-dir>';
    const FILES_FILENAME  = '__files';

    public function __construct($outputDir, array $files = [])
    {
        $this->files = $files;
        $this->outputDir = $outputDir;
    }

    public function loadFiles()
    {
        $this->files = $this->readConfig(static::FILES_FILENAME);
    }

    public function saveFiles()
    {
        $this->writeConfig(static::FILES_FILENAME, $this->files);
    }

    public function buildConfigs($files = null)
    {
        if (is_null($files)) {
            $files = $this->files;
        }
        foreach ($files as $name => $pathes) {
            $configs = [];
            foreach ($pathes as $path) {
                $configs[] = $this->readFile($path);
            }
            $this->buildConfig($name, $configs);
        }
    }

    /**
     * Merges given configs and writes at given name.
     * @param mixed $name
     * @param array $configs
     */
    public function buildConfig($name, array $configs)
    {
        if (!$this->isSpecialConfig($name)) {
            array_push($configs, [
                'params' => $this->vars['params'],
            ]);
        }
        $this->vars[$name] = call_user_func_array([Helper::className(), 'mergeConfig'], $configs);
        $this->writeConfig($name, (array) $this->vars[$name]);
    }

    protected function isSpecialConfig($name)
    {
        return in_array($name, ['defines', 'params'], true);
    }

    /**
     * Writes config file by name.
     * @param string $name
     * @param array $data
     */
    public function writeConfig($name, array $data)
    {
        static::writeFile($this->getOutputPath($name), $data);
    }

    public function getOutputPath($name)
    {
        return $this->outputDir . DIRECTORY_SEPARATOR . $name . '.php';
    }

    /**
     * Writes config file by full path.
     * @param string $path
     * @param array $data
     */
    public static function writeFile($path, array $data)
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        $array = str_replace("'" . self::BASE_DIR_SAMPLE, '$baseDir . \'', Helper::exportVar($data));
        file_put_contents($path, "<?php\n\n\$baseDir = dirname(dirname(dirname(__DIR__)));\n\nreturn $array;\n");
    }

    /**
     * Reads config file.
     * @param string $__path
     * @return array configuration read from file
     */
    public function readFile($__path)
    {
        if (strncmp($__path, '?', 1) === 0) {
            $__skippable = true;
            $__path = substr($__path, 1);
        }

        if (file_exists($__path)) {
            /// Expose variables to be used in configs
            extract($this->vars);

            return (array) require $__path;
        }

        if (empty($__skippable)) {
            $this->writeError('<error>Non existent config file</error> ' . $__path);
        }

        return [];
    }

    /**
     * @var IOInterface
     */
    protected $io;

    public function setIo(IOInterface $io)
    {
        $this->io = $io;
    }

    protected function writeError($text)
    {
        if (isset($this->io)) {
            $this->io->writeError($text);
        } else {
            echo $text . "\n";
        }
    }

}
