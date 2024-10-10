<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'weather:location', description: 'Add a short description for your command',)]
class WeatherLocationCommand extends Command
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        parent::__construct();

        $this->weatherUtil        = $weatherUtil;
        $this->locationRepository = $locationRepository;
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $locationId = $input->getArgument('id');
        $location   = $this->locationRepository->findOneBy(['id' => $locationId]);

        $weatherHistory = $this->weatherUtil->getWeatherForLocation($location);

        foreach ($weatherHistory as $weather) {
            $io->writeln(sprintf("\t%s: %s", $weather->getCreatedAt()
                                                     ->format('Y-m-d'), $weather->getTemperature()));
        }

        return Command::SUCCESS;
    }
}
