<?php
namespace App\Contracts;
/** Interface untuk Risk Scoring Service */
interface RiskServiceInterface {
    public function calculate(string $countryCode): float;
    public function getLevel(float $score): string;
}
