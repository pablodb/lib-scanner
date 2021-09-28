<?php

namespace Ampliffy\LibScanner\Dao;

use PDO;

class DB
{
    protected $conexion;

    public function __construct($db_config)
    {
        try {
            $db = $db_config['dbname'];
            $host = $db_config['host'];
            $port = $db_config['port'];
            $user = $db_config['username'];
            $pass = $db_config['password'];

            $dsn = "mysql:host=$host;port=$port;dbname=$db;";

            $this->conexion = new \PDO($dsn, $user, $pass);

        } catch (\PDOException $e) {
            $msg = 'ERROR conectandose al motor - '.$e->getMessage();
            $msg .= "\n\nPor favor, verifique sus parámetros de conexión"; 
            $ex = new \Exception('DB CONNECTION ERROR: '.$msg);
            throw $ex;
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }

}
