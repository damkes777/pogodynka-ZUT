<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\WeatherHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather1/{city}/{country?}', name: 'app_weather', requirements: [
        'city' => '[a-zA-Z\s]+',
        'country' => '[A-Z]{2}',
    ])]
    public function index(
        string $city,
        ?string $country,
        WeatherHistoryRepository $weatherRepository,
        LocationRepository $locationRepository
    ): Response {
        if ($country) {
            $location = $locationRepository->findOneBy(['country' => $country, 'city' => $city]);
        } else {
            $location = $locationRepository->findOneBy(['city' => $city]);
        }

        $weatherHistory = $weatherRepository->findByLocation($location);

        return $this->render('weather/index.html.twig', [
            'location' => $location,
            'weatherHistory' => $weatherHistory,
        ]);
    }
}
