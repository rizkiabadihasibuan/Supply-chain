<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Port;
use App\Repositories\Interfaces\PortRepositoryInterface;
use App\Services\Contracts\PortServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class PortService implements PortServiceInterface
{
    /**
     * @var PortRepositoryInterface
     */
    protected PortRepositoryInterface $portRepo;

    /**
     * PortService constructor.
     *
     * @param PortRepositoryInterface $portRepo
     */
    public function __construct(PortRepositoryInterface $portRepo)
    {
        $this->portRepo = $portRepo;
    }

    /**
     * @inheritDoc
     */
    public function getPorts(): Collection
    {
        return $this->portRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getPortById(int $id): ?Port
    {
        return $this->portRepo->findById($id);
    }

    /**
     * @inheritDoc
     */
    public function getPortByCode(string $code): ?Port
    {
        return $this->portRepo->findByCode($code);
    }

    /**
     * @inheritDoc
     */
    public function searchPorts(string $term): Collection
    {
        return $this->portRepo->search($term);
    }

    /**
     * @inheritDoc
     */
    public function filterPorts(array $filters): Collection
    {
        return $this->portRepo->filter($filters);
    }
}
