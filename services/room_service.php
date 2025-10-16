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
            'nazev' => $_GET['nazev'],
            'kapacita' => $_GET['kapacita'],
            'typ' => $_GET['typ'],
            'popis' => $_GET['popis']
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
            'nazev' => $_GET['nazev'],
            'kapacita' => $_GET['kapacita'],
            'typ' => $_GET['typ'],
            'popis' => $_GET['popis']
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

    
}