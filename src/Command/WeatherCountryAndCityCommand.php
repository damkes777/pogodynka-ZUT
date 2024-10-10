<?php

namespace App\Command;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'weather:countryAndCity', description: 'Add a short description for your command',)]
class WeatherCountryAndCityCommand extends Command
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        parent::__construct();

        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this->addArgument('country', InputArgument::REQUIRED, 'Location country code')
             ->addArgument('city', InputArgument::REQUIRED, 'Location city');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $country = $input->getArgument('country');
        $city    = $input->getArgument('city');

        $weatherHistory = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        foreach ($weatherHistory as $weather) {
            $io->writeln(sprintf("\t%s: %s", $weather->getCreatedAt()
                                                     ->format('Y-m-d'), $weather->getTemperature()));
        }

        return Command::SUCCESS;
    }
}
