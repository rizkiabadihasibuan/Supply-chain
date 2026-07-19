<?php
namespace App\Traits;
/**
 * HasPagination – Trait untuk standarisasi pagination
 * TODO (Backend Phase): Gunakan pada Repository layer
 */
trait HasPagination {
    protected int $defaultPerPage = 15;
    protected function getPerPage(?int $perPage = null): int {
        return $perPage ?? $this->defaultPerPage;
    }
}
