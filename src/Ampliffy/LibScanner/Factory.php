<?php

namespace Ampliffy\LibScanner;

use Dotenv\Dotenv;
use Pimple\Container;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Ampliffy\LibScanner\Config\Parameters;
use Ampliffy\LibScanner\Config\EnvLoader;
use Ampliffy\LibScanner\Dao\DB;
use Ampliffy\LibScanner\Controller\LibScannerController;

class Factory
{

    /**
     * @var Container
     */
    protected static $container;

    /**
     * @return Container
     */
    public static function getContainer()
    {
        if (isset(static::$container)) {
            return static::$container;
        }

        $container = new Container();

        $container['base-dir'] = realpath(__DIR__ . '/../../../');

        $container['env-file'] = '.env';

        $container['config-dir'] = function ($c) {
            $dir = $c['base-dir'] . '/config';
            return $dir;
        };

        // CONFIG
        $container['parameters-config-file'] = function ($c) {
            return $c['config-dir'] . '/parameters.yml';
        };

        $container['parameters-config'] = function ($c) {
            return EnvLoader::parse(Yaml::parse(file_get_contents($c['parameters-config-file'])));
        };

        $container['config'] = function ($c) {
            $config = $c['parameters-config']['parameters'];
            $processor = new Processor();
            $configuration = new Parameters();

            return $processor->processConfiguration($configuration, ['parameters' => $config]);
        };

        $container['temp-dir'] = function ($c) {
            $dir = $c['base-dir'] . '/temp';
            return $dir;
        };

        // DB CONFIG
        $container['db-config'] = function ($c) {
            return $c['config']['db'];
        };

        /**
         * @return PDO
         */
        $container['db'] = function ($c) {
            return new DB($c['db-config']);
        };

        /**
         * @return LibScannerController
         */
        $container['lib-scanner-controller'] = function ($c) {
            return new LibScannerController(
            );
        };

        static::$container = $container;
        return static::$container;
    }

    /**
     * Carga el archivo .env si existe
     */
    public static function loadDotEnvs()
    {
        $file = self::getContainer()['env-file'];
        $dir = self::getContainer()['base-dir'];
        if (file_exists($dir.'/'.$file)) {
            $dotenv = Dotenv::create($dir, $file);
            $dotenv->load();
        }
    }
}
