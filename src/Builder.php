<?php
/**
 * Composer plugin for config assembling
 *
 * @link      https://github.com/hiqdev/composer-config-plugin
 * @package   composer-config-plugin
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2016-2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\composer\config;

use Composer\IO\IOInterface;
use hiqdev\composer\config\exceptions\FailedWriteException;

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
        $this->outputDir = isset($outputDir) ? $outputDir : static::findOutputDir();
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
     * @param string $vendor path to vendor dir
     * @return string
     */
    public static function findOutputDir($vendor = null)
    {
        if ($vendor) {
            $dir = $vendor . '/hiqdev/' . basename(dirname(__DIR__));
        } else {
            $dir = dirname(__DIR__);
        }

        return $dir . static::OUTPUT_DIR_SUFFIX;
    }

    /**
     * Returns full path to assembled config file.
     * @param string $filename name of config
     * @param string $vendor path to vendor dir
     * @return string absolute path
     */
    public static function path($filename, $vendor = null)
    {
        return static::findOutputDir($vendor) . DIRECTORY_SEPARATOR . $filename . '.php';
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
        $resolver = new Resolver($files);
        $files = $resolver->get();
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
        if ('defines' === $name) {
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
        if (false === file_put_contents($path, $content)) {
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
        $skippable = 0 === strncmp($path, '?', 1) ? '?' : '';
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
        $reader = ReaderFactory::get($path);

        return $reader->read($path, $this);
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

    public function getVars()
    {
        return $this->vars;
    }
}
