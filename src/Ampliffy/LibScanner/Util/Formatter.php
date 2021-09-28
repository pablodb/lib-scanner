<?php

namespace Ampliffy\LibScanner\Util;

class Formatter
{

    /**
     * Formatea la salida del comando de busqueda de librerías
     *
     * @param  string $output
     * @param  string $branch
     * @return string
     */
    public static function formatSearchLibOutput(?string $output, ?string $branch): string
    {
        //No encontro dependencia
        if (empty($output))
            return 'no depende de la librería buscada.';

        //Si encontro dependencia y no se busca por branch
        if (empty($branch))
            return '<info>' . $output . '</info>';

        //Sigo procesando solo si tengo un branch
        $separator = "\n";
        $line = strtok($output, $separator);
        $result = '';

        while ($line !== false) {
            $arr = explode(' ', trim($line));
            $branch_encontrado = trim(array_pop($arr), '()'); //podría ser versión, pero se procesa solo branch por ahora

            //TODO: Mejorar el output
            if ($branch === str_replace('dev-', '', $branch_encontrado)) {
                $result .= '<info>' . $line . '</info>';
            } else {
                $result .= $line;
            }
            $line = strtok($separator);
        }
        return $result;
    }

    public static function formatJsonTree(string $output): array
    {
        $array_output = json_decode($output, true);
        //TODO: Controlar el output antes de devolver
        $new_array = [];
        $new_array['requires'] = $array_output['installed'];
        return $new_array;
    }

    public static function formatOutputDBTree(array $librerias): string
    {
        $rutas = array_column($librerias, 'ruta');
        $rutas = implode(",\n", $rutas);

        return $rutas;
    }
}
