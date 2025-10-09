<?php

require_once '../common/enums.php';
require_once '../data/repository_factory.php';

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
        session_unset('login');
        session_unset('pwd');
        session_destroy();
    }

    public function register(int $login, string $firstName, string $lastName, string $email, 
                            string $password, string $rodnecislo, string $birthDate, string $address): bool
    {
        if ($this->repository->exists('Uzivatel', ['email' => $email])) {
            return false; // User already exists
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'login' => $login,
            'heslo' => $hashedPassword,
            'rodne_cislo' => $rodnecislo,
            'jmeno' => $firstName,
            'prijmeni' => $lastName,
            'datum_narozeni' => $birthDate,
            'adresa' => $address,
            'email' => $email,
            'role' => PermissionLevel::STUDENT->value
        ];

        return $this->repository->insert('Uzivatel', $data);
    }
}