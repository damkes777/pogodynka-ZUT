<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/getWeather/{country}/{city}', name: 'app_weather', requirements: [
        'city' => '[a-zA-Z\s]+',
        'country' => '[A-Z]{2}',
    ])]
    public function index(
        #[MapEntity(mapping: ['country' => 'country', 'city' => 'city'])]
        Location $location,
        WeatherUtil $weatherUtil,
    ): Response {

        $weatherHistory = $weatherUtil->getWeatherForLocation($location);

        return $this->render('weather/index.html.twig', [
            'location' => $location,
            'weatherHistory' => $weatherHistory,
        ]);
    }
}
