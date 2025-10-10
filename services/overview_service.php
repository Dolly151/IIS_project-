<?php

require_once __DIR__ . '/../data/repository_factory.php';

Class OverviewService
{
    private Repository $repository;

    
    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getAllCourses(): array
    {
        return $this->repository->getAll('Kurz');
    }

    public function getAllCoursesJoinGarant(): array
    {
        $courses = $this->repository->getByCondition('Kurz', [], ['status' => 1]);
        foreach ($courses as &$course) {
            $garantId = $course['garant_ID'];
            if ($garantId === null) {
                $course['garant_ID'] = $this->getDummyGarant();
                continue;
            }
            $garant = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $garantId);
            if ($garant) {
                $course['garant_ID'] = $garant;
            }
            else {
                $course['garant_ID'] = $this->getDummyGarant();
            }
        }
        
        if (empty($courses)) {
            $courses[] = $this->getDummyCourse();
        }
        return $courses;
    }
    
    private function getDummyGarant() : array {
        return ['jmeno' => 'Nezadán', 'prijmeni' => ''];
    }
    
    private function getDummyCourse() : array {
        return ['zkratka' => '', 'nazev' => 'Žádný kurz ještě nebyl vytvořen nebo schválen', 'popis' => '', 'garant_ID' => $this->getDummyGarant(), 'cena' => 0, 'limit' => 0];
    }
}