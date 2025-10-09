<?php

require_once __DIR__ . '/../data/repository_factory.php';
require_once __DIR__ . '/../common/enums.php';

class PermissionService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public static function requireRole(PermissionLevel $level): void
    {
        
        if (!isset($_SESSION['role'])) {
            echo "<h1>Access forbidden via PermissionService</h1>";
            exit();
        }
        
        if ($level === PermissionLevel::ANY) {
            return;
        }
        
        if ($_SESSION['role'] !== $level->value) {
            echo "<h1>Access forbidden via PermissionService</h1>";
            exit();
        }
    }

    private static function validatePermissionLevel(PermissionLevel $level): bool
    {
        if (!isset($_SESSION['role'])) {
            return false;
        }

        return $_SESSION['role'] === $level->value;
    }

    public static function isUserLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    public static function isUserAdmin(): bool
    {
        return self::validatePermissionLevel(PermissionLevel::ADMIN);
    }

    public static function isUserStudent(): bool
    {
        return self::validatePermissionLevel(PermissionLevel::STUDENT);
    }

    public static function isUserLector(): bool
    {
        return self::validatePermissionLevel(PermissionLevel::LECTOR);
    }

    public static function isUserGarant(): bool
    {
        return self::validatePermissionLevel(PermissionLevel::GARANT);
    }

    public static function getUserPermissionFromSession(): PermissionLevel
    {
        if (!isset($_SESSION['role'])) {
            return PermissionLevel::GUEST;
        }

        return self::intToPermissionLevel($_SESSION['role']);
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

    public static function intToPermissionLevel(int $level): PermissionLevel
    {
        return match ($level) {
            PermissionLevel::ADMIN->value => PermissionLevel::ADMIN,
            PermissionLevel::STUDENT->value => PermissionLevel::STUDENT,
            PermissionLevel::LECTOR->value => PermissionLevel::LECTOR,
            PermissionLevel::GARANT->value => PermissionLevel::GARANT,
            default => PermissionLevel::GUEST,
        };
    }

    public static function permissionLevelToString(PermissionLevel $level): string
    {
        return match ($level) {
            PermissionLevel::GUEST => 'Guest',
            PermissionLevel::ADMIN => 'Admin',
            PermissionLevel::STUDENT => 'Student',
            PermissionLevel::LECTOR => 'Lektor',
            PermissionLevel::GARANT => 'Garant',
            PermissionLevel::ANY => 'Any',
        };
    }
}