<?php
namespace App\Contracts;
/** Interface untuk Currency Exchange Service */
interface CurrencyServiceInterface {
    public function getRates(string $base = 'USD'): array;
    public function convert(float $amount, string $from, string $to): float;
}
