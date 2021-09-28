<?php

namespace Ampliffy\LibScanner\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ampliffy\LibScanner\Console\Command;
use Ampliffy\LibScanner\Util\Directory;

class SearchLibFromDBCommand extends Command
{

    public function configure()
    {
        $this->setName('lib:search-from-db')
            ->setDescription('Busca los proyectos/librerías que dependen de una librería desde la DB.')
            ->addArgument('directory', InputArgument::REQUIRED, 'Directorio donde se encuentran los proyectos a escanear')
            ->addArgument('library_name', InputArgument::REQUIRED, 'Nombre de la librería a buscar');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $library_name = $input->getArgument('library_name');

        try {
            Directory::isValid($directory);
            $result_projects = \Ampliffy\LibScanner\Factory::getContainer()['lib-scanner-controller']->searchLibFromDB($directory, $library_name,);

            foreach ($result_projects as $project => $result) {
                $output->writeln("<comment>Proyecto $project </comment>");
                $output->writeln($result);
            }
        } catch (\Exception $ex) {

            $output->writeln('<error>Error</error>');
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
