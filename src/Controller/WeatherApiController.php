<?php

namespace App\Controller;

use App\Entity\WeatherHistory;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class WeatherApiController extends AbstractController
{
    private const FORMATS = [
        'csv' => 'csv',
        'json' => 'json',
    ];

    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }

    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format,
        #[MapQueryParameter('twig')] bool $twig = false
    ): JsonResponse|Response {
        $weather = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if ($format === self::FORMATS['csv']) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weather' => $weather,
                ]);
            }

            if ($format === self::FORMATS['json']) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weather' => $weather,
                ]);
            }
        } else {
            if ($format === self::FORMATS['csv']) {
                $csvData   = [];
                $csvData[] = implode(',', ['city', 'country', 'date', 'temperature']);

                foreach ($weather as $item) {
                    $csvData[] = implode(',', [
                        $city,
                        $country,
                        $item->getCreatedAt()
                             ->format('Y-m-d'),
                        $item->getTemperature(),
                    ]);
                }

                $csvContent = implode("\n", $csvData);

                $response = new Response($csvContent);
                $response->headers->set('Content-Type', 'text/csv');
                $response->headers->set('Content-Disposition', 'attachment; filename="weather.csv"');

                return $response;
            }

            if ($format === self::FORMATS['json']) {
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'weather' => array_map(fn(WeatherHistory $w) => [
                        'date' => $w->getCreatedAt()
                                    ->format('Y-m-d'),
                        'temperature' => $w->getTemperature(),
                    ], $weather),
                ]);
            }
        }

        return $this->json([
            'result' => 'invalid data',
        ]);
    }
}