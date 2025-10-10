<?php

require_once __DIR__ . '/../common/enums.php';
require_once __DIR__ . '/../data/repository_factory.php';

class LoginService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function authenticate() : bool
    {
        $login = $_POST['login'];
        $password = $_POST['pwd'];

        return $this->authenticateParams($login, $password);
    }


    private function authenticateParams(string $login, string $password): bool
    {
        // ensure we also retrieve the role so we can set it in the session
        $user = $this->repository->getByCondition('Uzivatel', ['ID', 'heslo', 'role'], ['login' => $login]);
        if ($user && password_verify($password, $user[0]['heslo'])) {
            $_SESSION['user_id'] = $user[0]['ID'];
            // role must exist in the selected columns
            $_SESSION['role'] = $user[0]['role'];
            return true;
        }
        return false;
    }

    public function deleteAccount(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $userId = $_SESSION['user_id'];
        return $this->deleteUserById($userId);
    }

    public function deleteUserById(int $id): bool
    {
        return $this->repository->deleteById('Uzivatel', $id);
    }

    public function logout(): void
    {
        // remove only the specific session keys set by this app
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        session_destroy();
    }

    public function isEverythingSetForRegister(): bool
    {
        return isset($_POST['login']) && isset($_POST['firstName']) && isset($_POST['lastName']) &&
               isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['rodneCislo']) &&
               isset($_POST['birthDate']) && isset($_POST['address']);
    }

    public function register(): bool
    {
        $login = $_POST['login'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        $rodneCislo = $_POST['rodneCislo'];
        $birthDate = $_POST['birthDate'];
        $address = $_POST['address'];

        return $this->registerParam($login, $firstName, $lastName, $email, $password, $rodneCislo, $birthDate, $address);
    }

    private function registerParam(string $login, string $firstName, string $lastName, string $email, 
                            string $password, string $rodneCislo, string $birthDate, string $address): bool
    {
        if ($this->repository->exists('Uzivatel', ['login' => $login])) {
            return false; // User already exists
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'login' => $login,
            'heslo' => $hashedPassword,
            'rodne_cislo' => $rodneCislo,
            'jmeno' => $firstName,
            'prijmeni' => $lastName,
            'datum_narozeni' => $birthDate,
            'adresa' => $address,
            'email' => $email,
            'role' => PermissionLevel::GUEST->value
        ];

        return $this->repository->insert('Uzivatel', $data);
    }

}