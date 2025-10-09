<?php

require_once __DIR__ . '/../data/repository_factory.php';

class ProfileService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getUserDetail(): array 
    {
        $user = $this->repository->getOneById('Uzivatel', ['login', 'jmeno', 'prijmeni', 'email', 'rodne_cislo', 'datum_narozeni', 'adresa', 'role'], $_SESSION['user_id']);        
        return $user;
    }

    public function updateUserProfile(): bool
    {
        $data = [
            'login' => $_POST['login'],
            'jmeno' => $_POST['firstName'],
            'prijmeni' => $_POST['lastName'],
            'email' => $_POST['email'],
            'heslo' => $_POST['pwd'],
            'rodne_cislo' => $_POST['rodneCislo'],
            'datum_narozeni' => $_POST['birthDate'],
            'adresa' => $_POST['address']
        ];

        return $this->repository->updateId('Uzivatel', $_SESSION['user_id'], $data);
    }
}
