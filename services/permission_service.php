<?php

require_once '../data/repository_factory.php';
require_once '../common/enums.php';

class PermissionService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    private function validatePermissionLevel(PermissionLevel $level): bool
    {
        if (!isset($_SESSION['role'])) {
            return false;
        }

        return $_SESSION['role'] === $level->value;
    }

    public function isUserAdmin(): bool
    {
        return $this->validatePermissionLevel(PermissionLevel::ADMIN);
    }

    public function isUserStudent(): bool
    {
        return $this->validatePermissionLevel(PermissionLevel::STUDENT);
    }

    public function isUserLector(): bool
    {
        return $this->validatePermissionLevel(PermissionLevel::LECTOR);
    }

    public function isUserGarant(): bool
    {
        return $this->validatePermissionLevel(PermissionLevel::GARANT);
    }

    public function getUserPermissionFromSession(): PermissionLevel
    {
        if (!isset($_SESSION['role'])) {
            return PermissionLevel::GUEST;
        }

        return $this->intToPermissionLevel($_SESSION['role']);
    }

    public function getUserPermissionLevelDB(int $userId): PermissionLevel
    {
        $user = $this->repository->getOneById('Uzivatel', ['role'], $userId);
        if (!$user) {
            return PermissionLevel::GUEST;
        }

        return $this->intToPermissionLevel((int)$user['role']);
    }

    public function setUserPermissionLevel(int $userId, PermissionLevel $level): bool
    {
        $ret = $this->repository->updateId('Uzivatel', $userId, ['role' => $level->value]);
        if ($ret && isset($_SESSION['user_id']) && $_SESSION['user_id'] === $userId) {
            $_SESSION['role'] = $level->value;
        }
        return $ret;
    }

    public function intToPermissionLevel(int $level): PermissionLevel
    {
        return match ($level) {
            PermissionLevel::ADMIN->value => PermissionLevel::ADMIN,
            PermissionLevel::STUDENT->value => PermissionLevel::STUDENT,
            PermissionLevel::LECTOR->value => PermissionLevel::LECTOR,
            PermissionLevel::GARANT->value => PermissionLevel::GARANT,
            default => PermissionLevel::GUEST,
        };
    }
}