<?php

namespace Ampliffy\LibScanner\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ampliffy\LibScanner\Console\Command;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;

/**
 * Comando para crear la DB
 *
 */
class DBCommand extends Command
{
    public function configure()
    {
        $this->setName('db:inicializar')
           ->setDescription('Crea la estructura de la base de datos');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $app = new PhinxApplication();
            $consoleTextWrapper = new TextWrapper($app, [
                'configuration' => __DIR__ . '/../../../../phinx.php',
                'parser' => 'php'
            ]);

            $status = $consoleTextWrapper->getMigrate();
            $code = $consoleTextWrapper->getExitCode();

            if ($code == 1) {
                $output->writeln('Hubo un error creando la db');
                $output->writeln('Error: ' . $status);
                return Command::FAILURE;
            } else {
                $output->writeln('Se creó la DB correctamente');
                $output->writeln($status);
                return Command::SUCCESS;
            }
        } catch (\Exception $ex) {
            $output->writeln('Hubo un error creando la db');
            $output->writeln('Error: ' . $ex->getMessage());
            return Command::FAILURE;
        }
    }
}
