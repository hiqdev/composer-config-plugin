<?php
/**
 * Composer plugin for config assembling.
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
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
        $this->files    = $this->loadConfig('__files');
        $this->addition = $this->loadConfig('__addition');
    }

    public function saveFiles()
    {
        $this->writeConfig('__files',    $this->files);
        $this->writeConfig('__addition', $this->addition);
    }

    public static function rebuild($outputDir = null)
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
        foreach ($files as $name => $paths) {
            $olddefs = get_defined_constants();
            $configs = $this->loadConfigs($paths);
            $newdefs = get_defined_constants();
            $defines = array_diff_assoc($newdefs, $olddefs);
            $this->buildConfig($name, $configs, $defines);
        }
        static::putFile($this->getOutputPath('__rebuild'), file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '__rebuild.php'));
    }

    protected function loadConfigs(array $paths)
    {
        $configs = [];
        foreach ($paths as $path) {
            $config = $this->loadFile($path);
            if (!empty($config)) {
                $configs[] = $config;
            }
        }

        return $configs;
    }

    /**
     * Merges given configs and writes at given name.
     * @param mixed $name
     * @param array $configs
     */
    public function buildConfig($name, array $configs, $defines = [])
    {
        if (!$this->isSpecialConfig($name)) {
            array_push($configs, $this->addition, [
                'params' => $this->vars['params'],
            ]);
        }
        $this->vars[$name] = call_user_func_array([Helper::className(), 'mergeConfig'], $configs);
        $this->writeConfig($name, (array) $this->vars[$name], $defines);
    }

    protected function isSpecialConfig($name)
    {
        return in_array($name, ['dotenv', 'defines', 'params'], true);
    }

    /**
     * Writes config file by name.
     * @param string $name
     * @param array $data
     */
    public function writeConfig($name, array $data, array $defines = [])
    {
        $data = $this->substituteOutputDirs($data);
        $defines = $this->substituteOutputDirs($defines);
        if ($name === 'defines') {
            $data = $defines;
        }
        static::writeFile($this->getOutputPath($name), $data, $defines);
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
    public static function writeFile($path, array $data, array $defines = [])
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        $content = Helper::exportDefines($defines) . "\nreturn " . Helper::exportVar($data);
        $content = str_replace("'" . static::BASE_DIR_MARKER, "\$baseDir . '", $content);
        $content = str_replace("'?" . static::BASE_DIR_MARKER, "'?' . \$baseDir . '", $content);
        static::putFile($path, "<?php\n\n\$baseDir = dirname(dirname(dirname(__DIR__)));\n\n$content;\n");
    }

    /**
     * Writes file if content changed.
     * @param string $path
     * @param string $content
     */
    protected static function putFile($path, $content)
    {
        if (file_exists($path) && $content === file_get_contents($path)) {
            return;
        }
        if (file_put_contents($path, $content) === false) {
            throw new FailedWriteException("Failed write file $path");
        }
    }

    /**
     * Substitute output paths in given data array recursively with marker.
     * @param array $data
     * @return array
     */
    public function substituteOutputDirs($data)
    {
        return static::substitutePaths($data, dirname(dirname(dirname($this->outputDir))), static::BASE_DIR_MARKER);
    }

    /**
     * Substitute all paths in given array recursively with alias if applicable.
     * @param array $data
     * @param string $dir
     * @param string $alias
     * @return array
     */
    public static function substitutePaths($data, $dir, $alias)
    {
        foreach ($data as &$value) {
            if (is_string($value)) {
                $value = static::substitutePath($value, $dir, $alias);
            } elseif (is_array($value)) {
                $value = static::substitutePaths($value, $dir, $alias);
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

    public function loadConfig($name)
    {
        return $this->loadFile($this->getOutputPath($name));
    }

    /**
     * Reads config file.
     * @param string $path
     * @return array configuration read from file
     */
    public function loadFile($path)
    {
        $skippable = strncmp($path, '?', 1) === 0 ? '?' : '';
        if ($skippable) {
            $path = substr($path, 1);
        }

        if (file_exists($path)) {
            $res = $this->readFile($path);

            return is_array($res) ? $res : [];
        }

        if (empty($skippable)) {
            throw new FailedReadException("failed read file: $path");
        }

        return [];
    }

    public function readFile($path)
    {
        $ext = pathinfo($path)['extension'];
        if ($ext === 'env') {
            return $this->readEnvFile($path);
        } elseif ($ext === 'php') {
            return $this->readPhpFile($path);
        } elseif ($ext === 'json') {
            return $this->readJsonFile($path);
        } elseif ($ext === 'yml' || $ext === 'yaml') {
            return $this->readYamlFile($path);
        }

        throw new UnsupportedFileTypeException("unsupported extension: $ext");
    }

    public function readEnvFile($path)
    {
        if (!class_exists('Dotenv\Dotenv')) {
            throw new UnsupportedFileTypeException("for .env support require `vlucas/phpdotenv` in your composer.json");
        }
        $info = pathinfo($path);
        $dotenv = new \Dotenv\Dotenv($info['dirname'], $info['basename']);
        $oldenvs = $_ENV;
        $dotenv->load();
        $newenvs = $_ENV;

        return array_diff_assoc($newenvs, $oldenvs);
    }

    public function readPhpFile($__path)
    {
        if (!is_readable($__path)) {
            throw new FailedReadException("failed read file: $__path");
        }
        /// Expose variables to be used in configs
        extract($this->vars);

        return require $__path;
    }

    public function readJsonFile($path)
    {
        return json_decode($this->getFileContents($path), true);
    }

    public function readYamlFile($path)
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new UnsupportedFileTypeException("for YAML support require `symfony/yaml` in your composer.json");
        }

        return \Symfony\Component\Yaml\Yaml::parse($this->getFileContents($path));
    }

    public function getFileContents($path)
    {
        $res = file_get_contents($path);
        if ($res === FALSE) {
            throw new FailedReadException("failed read file: $path");
        }

        return $res;
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
