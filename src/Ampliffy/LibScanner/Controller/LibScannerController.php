<?php

namespace Ampliffy\LibScanner\Controller;

use Ampliffy\LibScanner\Model\Project;
use Ampliffy\LibScanner\Util\{
    Directory,
    Formatter,
    Command
};

class LibScannerController
{
    /**
     * Genera un arbol de dependencias entre librerías
     *
     * @param  string $dir
     * @return void
     */
    public function processTree(string $dir, bool $export)
    {
        $projects_dirs = Directory::getValidProjects($dir);
        $result_projects = [];

        foreach ($projects_dirs as $project) {
            $project_dir = $dir . '/' . $project;
            $cmd_output = Command::composerTree($project_dir);

            if ($export) {
                if ($file = Directory::saveToFile($project, $cmd_output)) {
                    $result_projects[$project] = 'Se creó el archivo ' . $file;
                } else {
                    $result_projects[$project] = 'No se pudo crear el archivo.';
                }
            } else {
                $result_projects[$project] = $cmd_output;
            }
        }

        return $result_projects;
    }

    /**
     * Busca los proyectos/librerías que dependen de una librería
     *
     * @param  string $dir
     * @param  string $library_name
     * @return array Array de resultado de la busqueda por proyecto
     */
    public function searchLib(string $dir, string $library_name, ?string $branch): array
    {
        $projects_dirs = Directory::getValidProjects($dir);
        $result_projects = [];

        foreach ($projects_dirs as $project) {
            $project_dir = $dir . '/' . $project;
            $cmd_output = Command::composerDepends($project_dir, $library_name);
            $result_projects[$project] = Formatter::formatSearchLibOutput($cmd_output, $branch);
        }

        return $result_projects;
    }

    /**
     * Busca los proyectos/librerías que dependen de una librería desde la DB
     *
     * @param  string $dir
     * @param  string $library_name
     * @return array Array de resultado de la busqueda por proyecto
     */
    public function searchLibFromDB(string $dir, string $library_name): array
    {
        $projects_dirs = Directory::getValidProjects($dir);
        $result_projects = [];

        foreach ($projects_dirs as $project_name) {
            $project_dir = $dir . '/' . $project_name;
            $cmd_output = Command::composerTree($project_dir, true);
            $libraries = Formatter::formatJsonTree($cmd_output);

            $project = new Project($project_name);
            $project->setLibraries($libraries);
            $project->save();

            $result_projects[$project_name] = Formatter::formatOutputDBTree($project->getLibraryTree($library_name));
        }

        return $result_projects;
    }
}
