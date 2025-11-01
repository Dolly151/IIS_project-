<?php
require_once __DIR__ . '/../data/repository_factory.php';
require_once __DIR__ . '/permission_service.php';

class MyCoursesService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    /**
     * Vrátí kurzy, na které je student zapsán.
     * @return array[] Každá položka: ['id','nazev','cena','limit','garant' => ['jmeno','prijmeni']]
     */
    public function getStudentCourses(int $studentId): array
    {
        // 1) vezmi všechny zápisy pro uživatele
        $rows = $this->repository->getByCondition(
            'student_navstevuje_kurz',
            ['kurz_ID'],
            ['uzivatel_ID' => $studentId]
        );

        if (empty($rows)) return [];

        $out = [];
        foreach ($rows as $r) {
            $cid = (int)$r['kurz_ID'];
            if ($cid <= 0) continue;

            // 2) dotáhni kurz
            $course = $this->repository->getOneById('Kurz', ['ID','nazev','popis','garant_ID','cena','limit'], $cid);
            if (!$course) continue;

            // 3) dotáhni garanta (na vizitku)
            $garant = null;
            if (!empty($course['garant_ID'])) {
                $garant = $this->repository->getOneById('Uzivatel', ['jmeno','prijmeni'], (int)$course['garant_ID']);
            }

            $out[] = [
                'id'     => (int)$course['ID'],
                'nazev'  => $course['nazev'] ?? '',
                'popis'  => $course['popis'] ?? '',
                'cena'   => $course['cena'] ?? null,
                'limit'  => $course['limit'] ?? null,
                'garant' => $garant ?: null,
            ];
        }

        return $out;
    }

    public function isStudentInCourse($studentId, $courseId):bool 
    {
        $studentCourses = $this->getStudentCourses($studentId);
        foreach($studentCourses as $course)
        {
            if ((int)$course['id'] === (int)$courseId) 
            {
                return true;
            }
        }
        return false;
    }
}
