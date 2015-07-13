<?php

namespace Orbit\Machine\CLI\Command;

use Orbit\Machine\CLI\Command;
use Orbit\Machine\Support\Arr;
use PhpParser\Lexer;
use PhpParser\Parser;
use ClassPreloader\ClassPreloader;
use ClassPreloader\Parser\DirVisitor;
use ClassPreloader\Parser\FileVisitor;
use ClassPreloader\Parser\NodeTraverser;
use ClassPreloader\Exceptions\SkipFileException;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;

/**
 * Class Optimized Command
 * @author Pieter Lelaona <broklyn.gagah@gmail.com>
 */
class OptimizeCommand extends Command
{
    protected $name = 'optimize';

    protected $description = 'Optimize current application.';

    private $basePath;

    public function __construct()
    {
        parent::__construct();

        $this->basePath = base_path();
    }

    public function fire()
    {
        $this->optimizeConfigs();
        $this->showInfo('Configuration compiled.');

        //$this->optimizeClassLoader();
        //$this->showInfo('CLass Loader compiled.');

        $this->optimizeServices();
        $this->showInfo('Services compiled.');

        $this->showInfo('Application optimized.');
    }

    private function optimizeServices()
    {
        $services = di('config')->get('app.services');
        $dump = "<?php \n\n";
        $dump .= '$di = new \\Phalcon\DI\\FactoryDefault();' . "\n";

        foreach ($services as $key => $value) {

            $dump .= '$di->set(\''. $key .'\', function() use($di, $config) {' . "\n" .
                '    return (new \\' . $value . '($di, $config)' . ")->register();\n });\n\n";

            /*$dump .= '$di[\''. $key .'\'] = function() use($di, $config) {' . "\n" .
                '    return (new \\' . $value . '($di, $config)' . ")->register(); \n}; \n\n";*/
        }

        // add basePath service.
        $dump .= '$di->set(\'basePath\', function() { ' . "\n" . '    return \'' . base_path() . "'; \n}, true); \n";

        $path = $this->basePath . '/storages/apps/services.php';
        file_put_contents($path, $dump . "\n" . 'return $di;' );
    }

    private function optimizeClassLoader()
    {
        $compiledPath = $this->basePath . '/storages/apps/compiled.php';

        $preloader = new ClassPreloader(new PrettyPrinter, new Parser(new Lexer), $this->getTraverser());
        $handle = $preloader->prepareOutput($compiledPath);
        foreach ($this->getClassFiles() as $file) {
            try {
                fwrite($handle, $preloader->getCode($file, false)."\n");
            } catch (SkipFileException $ex) {
                //
            }
        }
        fclose($handle);
    }

    protected function getTraverser()
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new DirVisitor(true));
        $traverser->addVisitor(new FileVisitor(true));

        return $traverser;
    }

    private function getClassFiles()
    {
        $basePath = $this->basePath;
        $core = require __DIR__.'/../preloader/config.php';

        /*$files = array_merge($core, $this->laravel['config']->get('compile.files', []));
        foreach ($this->laravel['config']->get('compile.providers', []) as $provider) {
            $files = array_merge($files, forward_static_call([$provider, 'compiles']));
        }*/

        return $core;
    }

    private function optimizeConfigs()
    {
        $path = $this->basePath . '/storages/apps/config.php';
        $configs = Arr::except(di('config')->getConfig(), ['router']);

        $basePath = $this->basePath;

        $dump = var_export($configs , true);
        $dump = $this->replaceStorageAbsolutePath($dump, $basePath);
        $dump = $this->replaceResourceAbsolutePath($dump, $basePath);
        $dump = $this->replaceAppAbsolutePath($dump, $basePath);

        file_put_contents($path, "<?php \n\n return ".$dump.";\n" );
    }

    private function replaceStorageAbsolutePath($dump, $basePath)
    {
        $regex = preg_quote($basePath . '/storages', '/');
        preg_match_all("/$regex(.*)/", $dump, $match);

        // loop and replace absolute path.
        for ($i=0; $i < count($match[0]); $i++) {

            $search = "'".preg_quote($match[0][$i], '/');
            $replacer = '__DIR__ . \'/..' . str_replace(',', '', $match[1][$i]) . ',';

            $dump = preg_replace("/$search/", $replacer, $dump);
        }

        return $dump;
    }

    private function replaceResourceAbsolutePath($dump, $basePath)
    {
        $regex = preg_quote($basePath . '/resources', '/');
        preg_match_all("/$regex(.*)/", $dump, $match);

        // loop and replace absolute path.
        for ($i=0; $i < count($match[0]); $i++) {

            $search = "'".preg_quote($match[0][$i], '/');
            $replacer = '__DIR__ . \'/../../resources' . str_replace(',', '', $match[1][$i]) . ',';

            $dump = preg_replace("/$search/", $replacer, $dump);
        }

        return $dump;
    }

    private function replaceAppAbsolutePath($dump, $basePath)
    {
        $regex = preg_quote($basePath . '/app', '/');
        preg_match_all("/$regex(.*)/", $dump, $match);

        // loop and replace absolute path.
        for ($i=0; $i < count($match[0]); $i++) {

            $search = "'".preg_quote($match[0][$i], '/');
            $replacer = '__DIR__ . \'/../../app' . str_replace(',', '', $match[1][$i]) . ',';

            $dump = preg_replace("/$search/", $replacer, $dump);
        }

        return $dump;
    }
}