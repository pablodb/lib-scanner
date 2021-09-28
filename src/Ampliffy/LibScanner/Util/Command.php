<?php

namespace Ampliffy\LibScanner\Util;

class Command
{
    /**
     * Ejecuta el comando composer depends
     *
     * @param  string $path Ruta donde ejecutar el comando
     * @param  string $library_name Libreria a buscar
     * @return string output del comando
     */
    public static function composerDepends(string $path, string $library_name): string
    {
        $library_name = escapeshellarg($library_name);
        $cmd = "cd $path && composer depends $library_name 2>/dev/null";
        $output = shell_exec($cmd);

        return $output ?? '';
    }

    public static function composerTree(string $path, bool $json = false)
    {
        $format = $json ? ' --format=json ' : '';
        $cmd = "cd $path && composer show --tree $format 2>/dev/null";
        $output = shell_exec($cmd);

        return $output;
    }
}
