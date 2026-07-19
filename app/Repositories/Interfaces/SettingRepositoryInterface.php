<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

interface SettingRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search settings by key or group.
     *
     * @param string $term
     * @return Collection<int, Setting>
     */
    public function search(string $term): Collection;

    /**
     * Filter settings.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Setting>
     */
    public function filter(array $filters): Collection;

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return void
     */
    public function set(string $key, mixed $value, string $group = 'system'): void;

    /**
     * Get settings that should be autoloaded.
     *
     * @return Collection<int, Setting>
     */
    public function getAutoloadSettings(): Collection;
}
