<?php 

require_once '../data/repository_factory.php';

class DetailService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getCourseDetail($id): array 
    {
        $course = $this->repository->getOneById('Kurz', ['nazev', 'popis', 'garant_ID', 'cena'], $id);
        if (!$course) 
        {
            return [];
        }

        $garant = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $course['garant_ID']);
        $course['garant'] = $garant;
        
        return $course;
    }
}

?>