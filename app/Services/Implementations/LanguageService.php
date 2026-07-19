<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use App\Services\Contracts\LanguageServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class LanguageService implements LanguageServiceInterface
{
    /**
     * @var LanguageRepositoryInterface
     */
    protected LanguageRepositoryInterface $languageRepo;

    /**
     * LanguageService constructor.
     *
     * @param LanguageRepositoryInterface $languageRepo
     */
    public function __construct(LanguageRepositoryInterface $languageRepo)
    {
        $this->languageRepo = $languageRepo;
    }

    /**
     * @inheritDoc
     */
    public function getLanguages(): Collection
    {
        return $this->languageRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getLanguageByCode(string $code): ?Language
    {
        return $this->languageRepo->findByCode($code);
    }
}
