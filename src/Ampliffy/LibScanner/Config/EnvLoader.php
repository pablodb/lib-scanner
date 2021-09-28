<?php

namespace Ampliffy\LibScanner\Config;

class EnvLoader
{
    static function parse($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::parse($value);
                } else {
                    $data[$key] = self::load($value);
                }
            }
        } else {
            $data = self::load($data);
        }

        return $data;
    }

    static function load($env, $default = false)
    {
        $cleanEnv = self::extract($env);

        if ($cleanEnv === false) {
            return $env;
        }

        $value = getenv($cleanEnv);

        if ($value === false) {
            return $default;
        }

        return $value;
    }

    static function extract($valor)
    {
        $matches=array();
        if (substr($valor, 0, 4) == '$env' && substr($valor,-1) == '$') {
            if (preg_match('/^\$env\((.*)\)\$$/', $valor, $matches) !== false) {
                return (is_array($matches) && ! empty($matches)) ? $matches[1]: '';
            }
        }

        return false;
    }
}
