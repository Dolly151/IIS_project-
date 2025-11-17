<?php

require_once __DIR__ . '/../data/repository_factory.php';

class RoomService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    /**
     * KRÁTCE: Použij v course_create.php pro naplnění <select>.
     * Vrací jen potřebné sloupce, aby to bylo přehledné a rychlé.
     */
    public function getAll(): array
    {
        // POZOR: ve tvé DB je sloupec s velkým "ID"
        return $this->repository->getAll('Mistnost', ['ID', 'nazev', 'kapacita', 'typ']);
    }

    /**
     * Ponechávám kvůli kompatibilitě – když to máš někde použité.
     * (Můžeš ji časem odstranit a všude přejít na getAll().)
     */
    public function getAllRooms(): array
    {
        return $this->repository->getAll('Mistnost', ['ID', 'nazev', 'kapacita', 'typ']);
    }

    public function createRoom(): bool
    {
        $data = [
            'nazev'    => $_POST['nazev'],
            'kapacita' => $_POST['kapacita'],
            'typ'      => $_POST['typ'],
            'popis'    => $_POST['popis']
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
            'nazev'    => $_POST['nazev'],
            'kapacita' => $_POST['kapacita'],
            'typ'      => $_POST['typ'],
            'popis'    => $_POST['popis']
        ];

        return $this->repository->updateId('Mistnost', $id, $data);
    }

    public function getRoomDetail(int $id): array
    {
        $room = $this->repository->getOneById('Mistnost', ['nazev', 'kapacita', 'typ', 'popis'], $id);
        if (!$room) {
            return [];
        }

        $termins = $this->repository->getByCondition('Termin', [], ['mistnost_ID' => $id]);
        $room['termins'] = $termins;
        return $room;
    }

    public function isEverythingSetForNewRoom(): bool
    {
        return isset($_POST['nazev'], $_POST['typ'], $_POST['popis'], $_POST['kapacita']);    
    }

    /** Volitelné: převod typu na text (pro hezčí zobrazení v <option>) */
    public static function typeToText(int $typ): string
    {
        return match ($typ) {
            0 => 'Aula',
            1 => 'Cvičebna',
            2 => 'PC lab',
            default => 'Neznámý typ',
        };
    }
}
