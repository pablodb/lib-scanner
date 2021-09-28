<?php

namespace Ampliffy\LibScanner\Model;

use Ampliffy\LibScanner\Dao\ProjectDao;

class Project
{

    protected $name;
    protected $libraries;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * setName
     *
     * @param  string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * setLibraries
     *
     * @param  array $libraries
     */
    public function setLibraries($libraries): void
    {
        $this->libraries = $libraries;
    }

    public function save($libraries = null, $name = null, $parent = 0)
    {
        $libraries = $libraries ?? $this->libraries['requires'];
        $name = $name ?? $this->name;
        $parent = $parent ?? 0;

        if ($parent == 0) {
            ProjectDao::eliminarLibrerias();
            $parent = ProjectDao::saveLib($name, $parent);
        }

        foreach ($libraries as $lib) {
            $parent = ProjectDao::saveLib($lib['name'], $parent);
            if (isset($lib['requires'])) {
                $this->save($lib['requires'], $lib['name'], $parent);
            }
        }
    }

    public function getLibraryTree($library_name): array
    {
        return ProjectDao::getLibraryTree($library_name);
    }
}
