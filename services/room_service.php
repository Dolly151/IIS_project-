<?php

require_once __DIR__ . '/../data/repository_factory.php';

class RoomService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getAllRooms(): array
    {
        return $this->repository->getAll('Mistnost');
    }

    public function createRoom(): bool
    {
        
        $data = [
            'nazev' => $_POST['nazev'],
            'kapacita' => $_POST['kapacita'],
            'typ' => $_POST['typ'],
            'popis' => $_POST['popis']
        ];

        return $this->repository->insert('Mistnost', $data);
    }

    public function deleteRoom(int $id): bool
    {
        return $this->repository->deleteById('Mistnost', $id);
    }

    public function updateRoom(int $id): bool
    {
        $data = [
            'nazev' => $_POST['nazev'],
            'kapacita' => $_POST['kapacita'],
            'typ' => $_POST['typ'],
            'popis' => $_POST['popis']
        ];

        return $this->repository->updateId('Mistnost', $id, $data);
    }

    public function getRoomDetail(int $id): array
    {
        $room = $this->repository->getOneById('Mistnost', ['nazev', 'kapacita', 'typ', 'popis'], $id);
        if (!$room) {
            return [];
        }

        $termins = $this->repository->getByCondition('termin', [], ['mistnost_ID' => $id]);
        $room['termins'] = $termins;
        return $room;
    }

    public function isEverythingSetForNewRoom(): bool
    {
        return isset($_POST['nazev']) && isset($_POST['typ']) && isset($_POST['popis']) && isset($_POST['kapacita']);    
    }
    
}