<?php
/**
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
     * @var array files to build configs
     * @see buildConfigs()
     */
    protected $files = [];

    /**
     * @var array additional data to be merged into every config (e.g. aliases)
     */
    protected $addition = [];

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var array collected variables
     */
    protected $vars = [];

    const OUTPUT_DIR_SUFFIX = '-output';
    const BASE_DIR_MARKER = '<<<base-dir>>>';

    public function __construct(array $files = [], $outputDir = null)
    {
        $this->setFiles($files);
        $this->setOutputDir($outputDir);
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    public function setOutputDir($outputDir)
    {
        $this->outputDir = isset($outputDir) ? $outputDir : static::defaultOutputDir();
    }

    public function setAddition(array $addition)
    {
        $this->addition = $addition;
    }

    public function loadFiles()
    {
        $this->files    = $this->readConfig('__files');
        $this->addition = $this->readConfig('__addition');
    }

    public function saveFiles()
    {
        $this->writeConfig('__files',    $this->files);
        $this->writeConfig('__addition', $this->addition);
    }

    public static function rebuild($outputDir)
    {
        $builder = new self([], $outputDir);
        $builder->loadFiles();
        $builder->buildConfigs();
    }

    /**
     * Returns default output dir.
     * @return string
     */
    public static function defaultOutputDir()
    {
        return dirname(__DIR__) . static::OUTPUT_DIR_SUFFIX;
    }

    /**
     * Returns full path to assembled config file.
     * @param string $filename name of config
     * @return string absolute path
     */
    public static function path($filename)
    {
        return static::defaultOutputDir() . DIRECTORY_SEPARATOR . $filename . '.php';
    }

    /**
     * Builds configs by given files list.
     * @param null|array $files files to process: config name => list of files
     */
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
        file_put_contents($this->getOutputPath('__rebuild'), file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '__rebuild.php'));
    }

    /**
     * Merges given configs and writes at given name.
     * @param mixed $name
     * @param array $configs
     */
    public function buildConfig($name, array $configs)
    {
        if (!$this->isSpecialConfig($name)) {
            array_push($configs, $this->addition, [
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
        $data = $this->substitutePathes($data, dirname(dirname(dirname($this->outputDir))), static::BASE_DIR_MARKER);
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
        $content = Helper::exportVar($data);
        $content = str_replace("'" . static::BASE_DIR_MARKER, "\$baseDir . '", $content);
        $content = str_replace("'?" . static::BASE_DIR_MARKER, "'?' . \$baseDir . '", $content);
        static::putFile($path, "<?php\n\n\$baseDir = dirname(dirname(dirname(__DIR__)));\n\nreturn $content;\n");
    }

    /**
     * Writes file if content changed.
     * @param string $path 
     * @param string $content 
     */
    public static function putFile($path, $content)
    {
        if ($content !== file_get_contents($path)) {
            file_put_contents($path, $content);
        }
    }

    /**
     * Substitute all pathes in given array recursively with alias if applicable.
     * @param array $data
     * @param string $dir
     * @param string $alias
     * @return string
     */
    public static function substitutePathes($data, $dir, $alias)
    {
        foreach ($data as &$value) {
            if (is_string($value)) {
                $value = static::substitutePath($value, $dir, $alias);
            } elseif (is_array($value)) {
                $value = static::substitutePathes($value, $dir, $alias);
            }
        }

        return $data;
    }

    /**
     * Substitute path with alias if applicable.
     * @param string $path
     * @param string $dir
     * @param string $alias
     * @return string
     */
    protected static function substitutePath($path, $dir, $alias)
    {
        $skippable = strncmp($path, '?', 1) === 0 ? '?' : '';
        if ($skippable) {
            $path = substr($path, 1);
        }
        $result = (substr($path, 0, strlen($dir) + 1) === $dir . DIRECTORY_SEPARATOR) ? $alias . substr($path, strlen($dir)) : $path;

        return $skippable . $result;
    }

    public function readConfig($name)
    {
        return $this->readFile($this->getOutputPath($name));
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
            $this->writeError("Failed read file $__path");
        }

        return [];
    }

    public function setIo(IOInterface $io)
    {
        $this->io = $io;
    }

    protected function writeError($text)
    {
        if (isset($this->io)) {
            $this->io->writeError("<error>$text</error>");
        } else {
            echo $text . "\n";
        }
    }
}
