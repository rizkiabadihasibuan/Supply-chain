<?php
namespace App\Contracts;
/** Interface untuk Country Service */
interface CountryServiceInterface {
    public function getAll(): array;
    public function getByCode(string $code): array;
}
