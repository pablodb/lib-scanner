<?php

namespace Ampliffy\LibScanner\Dao;

class ProjectDao
{

    public static function saveLib(string $name, int $parent = 0)
    {

        $db = \Ampliffy\LibScanner\Factory::getContainer()['db']->getConexion();

        $sql = 'INSERT INTO libraries(name, parent) VALUES(:name, :parent)';

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':name'     => $name,
            ':parent'   => $parent
        ]);;

        $lib_id = $db->lastInsertId();

        return $lib_id;
    }

    public static function eliminarLibrerias(): void
    {
        $db = \Ampliffy\LibScanner\Factory::getContainer()['db']->getConexion();

        $sql = 'TRUNCATE TABLE libraries';
        $stmt = $db->prepare($sql);
        $stmt->execute();;
    }


    public static function getLibraryTree($library): array
    {
        $db = \Ampliffy\LibScanner\Factory::getContainer()['db']->getConexion();

        $sql = "
                WITH RECURSIVE cte (id, name, parent, ruta) as (
                    SELECT  id,
                            name,
                            parent,
                            CAST(name AS CHAR(10000)) AS ruta
                    FROM    libraries
                    WHERE   parent = 0
                    UNION ALL
                    SELECT  l.id,
                            l.name,
                            l.parent,
                            CONCAT(ruta, ' --> ', l.name) as ruta
                    FROM    libraries l
                    INNER JOIN cte ON l.parent = cte.id
                )
                SELECT * FROM cte WHERE name = :library;
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':library'  => $library
        ]);;

        $result = $stmt->fetchAll();

        return $result;
    }
}
