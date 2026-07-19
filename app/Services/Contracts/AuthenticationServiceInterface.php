<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\User;

interface AuthenticationServiceInterface
{
    /**
     * Authenticate user login credentials.
     *
     * @param array<string, string> $credentials
     * @param bool $remember
     * @return bool
     */
    public function login(array $credentials, bool $remember = false): bool;

    /**
     * Terminate user session.
     *
     * @return void
     */
    public function logout(): void;

    /**
     * Register a new user.
     *
     * @param array<string, string> $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * Change password for the current user.
     *
     * @param int $userId
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool;
}
