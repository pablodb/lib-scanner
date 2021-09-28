<?php

namespace Ampliffy\LibScanner\Util;

class Directory
{

    /**
     * Chequea si es un directorio valido
     *
     * @param  string $path
     * @return bool
     * @throws Exception si el directorio no es valido
     */
    public static function isValid(string $path): bool
    {
        if (!is_dir($path)) {
            throw new \Exception("El directorio '$path' no es válido.");
        }

        if (empty(array_diff(scandir($path), ['..', '.']))) {
            throw new \Exception("El directorio '$path' está vacío.");
        }

        return true;
    }


    /**
     * Recupera directorios de proyectos válidos.
     * Tiene que ser un directorio que tenga un archivo composer.lock y exista el directorio vendor
     *
     * @param  string $path
     * @return array
     */
    public static function getValidProjects(string $path): array
    {
        $dirs = array_diff(scandir($path), ['..', '.']);

        $valid_projects = [];
        foreach ($dirs as $dir) {
            if (self::isValidProject($path . '/' . $dir)) {
                $valid_projects[] = $dir;
            }
        }

        return $valid_projects;
    }

    /**
     * Chequea si el directorio es un proyecto valido
     *
     * @param  string $dir
     * @return bool
     */
    public static function isValidProject(string $dir): bool
    {
        if (!is_dir($dir))
            return false;

        if (!file_exists($dir . '/composer.lock'))
            return false;

        if (!file_exists($dir . '/vendor/autoload.php'))
            return false;

        return true;
    }

    public static function saveToFile(string $name, string $content)
    {
        $temp_dir = \Ampliffy\LibScanner\Factory::getContainer()['temp-dir'];
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir);
        }
        try {
            $file = $temp_dir . '/' . $name;
            file_put_contents($file, $content);
        } catch (\Exception $ex) {
            return false;
        }

        return $file;
    }
}
