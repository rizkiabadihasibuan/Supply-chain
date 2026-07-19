<?php

declare(strict_types=1);

namespace App\DTO\Mappers;

use App\DTO\CountryDTO;
use App\Models\Country;

class CountryMapper
{
    /**
     * Convert Country Eloquent model to CountryDTO.
     *
     * @param Country $model
     * @return CountryDTO
     */
    public static function toDTO(Country $model): CountryDTO
    {
        return CountryDTO::fromArray([
            'id' => $model->id,
            'region_id' => $model->region_id,
            'currency_id' => $model->currency_id,
            'code' => $model->code,
            'name' => $model->name,
            'subregion' => $model->subregion,
            'population' => $model->population,
            'area' => $model->area,
            'timezone' => $model->timezone,
        ]);
    }

    /**
     * Map CountryDTO back to model attributes array.
     *
     * @param CountryDTO $dto
     * @return array<string, mixed>
     */
    public static function toModelArray(CountryDTO $dto): array
    {
        return [
            'region_id' => $dto->regionId,
            'currency_id' => $dto->currencyId,
            'code' => $dto->code,
            'name' => $dto->name,
            'subregion' => $dto->subregion,
            'population' => $dto->population,
            'area' => $dto->area,
            'timezone' => $dto->timezone,
        ];
    }
}
