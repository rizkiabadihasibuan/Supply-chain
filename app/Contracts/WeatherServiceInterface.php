<?php
namespace App\Contracts;
/** Interface untuk Weather Service */
interface WeatherServiceInterface {
    public function getByCoordinates(float $lat, float $lon): array;
    public function getByCountryCode(string $code): array;
}
