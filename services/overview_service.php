<?php

require_once '../data/repository_factory.php';

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
        $courses = $this->repository->getAll('Kurz');
        foreach ($courses as &$course) {
            $garantId = $course['garant_ID'];
            $garant = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $garantId);
            if ($garant) {
                $course['garant_ID'] = $garant;
            }
            else {
                $course['garant_ID'] = null;
            }
        }
        unset($course);
        return $courses;
    }
}