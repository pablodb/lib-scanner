<?php

namespace Ampliffy\LibScanner\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ampliffy\LibScanner\Console\Command;
use Ampliffy\LibScanner\Util\Directory;

class TreeCommand extends Command
{

    public function configure()
    {
        $this->setName('lib:tree')
            ->setDescription('Genera un árbol de dependencias entre librerías.');

        $this->addArgument(
            'directory',
            InputArgument::REQUIRED,
            'Directorio donde se encuentran los proyectos a escanear'
        );

        $this->addOption(
            'export',
            'e',
            InputOption::VALUE_NONE,
            'Exporta el resultado a un archivo de texto'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $export = $input->getOption('export');

        try {
            Directory::isValid($directory);
            $result_projects = \Ampliffy\LibScanner\Factory::getContainer()['lib-scanner-controller']->processTree($directory, $export);

            foreach ($result_projects as $project => $result) {
                $output->writeln("<comment>Proyecto $project </comment>");
                $output->writeln($result);
            }
        } catch (\Exception $ex) {

            $output->writeln('<error>Error</error>');
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        // $out = shell_exec('cd /home/pablo/workspace/ampliffy/test/lib-scanner && composer show --tree --format=json');
        // $t = json_decode($out, true);
        // var_dump($t['installed'][1]['name']);
        // $output->write('out');

        $output->write($directory);
        return Command::SUCCESS;
    }
}
