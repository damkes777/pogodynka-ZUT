<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\WeatherHistory;

class WeatherTest extends TestCase
{
    /**
     * @dataProvider dataGetFahrenheit
     * */
    public function testGetFahrenheit($celsius, $expectedFahrenheit)
    {
        $weather = new WeatherHistory();

        $weather->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit, $weather->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0.5', 32.9],
            ['-40', -40],
            ['37', 98.6],
            ['-273', -459.4],
            ['20', 68],
            ['50', 122],
            ['-50', -58],
        ];
    }
}
