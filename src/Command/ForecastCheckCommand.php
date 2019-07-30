<?php

namespace App\Command;

use App\Service\Forecast;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ForecastCheckCommand extends Command
{
    protected static $defaultName = 'forecast:check';

    protected function configure()
    {
        $this
            ->setDescription('Проверяет на изменения прогнозы с заданного сайта')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $forecast = new Forecast();
        $result = $forecast->compare();

        if($result)
            $io->success('Изменения прошли успешно');
         else
             $io->success('Нет изменений');


    }
}
