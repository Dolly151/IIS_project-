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
        $user = $this->repository->getByCondition('Uzivatel', ['ID', 'heslo'], ['login' => $login]);
        if ($user && password_verify($password, $user[0]['heslo'])) {
            $_SESSION['user_id'] = $user[0]['ID'];
            $_SESSION['role'] = $user[0]['role'];
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        session_unset('user_id');
        session_unset('role');
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
        if ($this->repository->exists('Uzivatel', ['email' => $email])) {
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