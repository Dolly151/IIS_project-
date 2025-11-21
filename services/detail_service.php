<?php 

require_once __DIR__ . '/../data/repository_factory.php';

class DetailService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getCourseDetail($id): array 
    {
        $course = $this->repository->getOneById('Kurz', ['nazev', 'popis', 'garant_ID', 'cena', 'limit', 'den', 'vyuka_od', 'vyuka_do', 'status'], $id);
        if (!$course) 
        {
            return [];
        }

        $garant = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $course['garant_ID']);
        $course['garant'] = $garant;
        
        return $course;
    }

    public function isUserAllreadyRegisteredToCourse(int $courseId, int $userId): bool
    {
        return $this->repository->exists('student_navstevuje_kurz', ['kurz_ID' => $courseId, 'uzivatel_ID' => $userId]);
    }

}

?>