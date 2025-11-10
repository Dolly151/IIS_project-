<?php

require_once __DIR__ . '/../common/enums.php';
require_once __DIR__ . '/../data/repository_factory.php';
require_once __DIR__ . '/permission_service.php';
require_once __DIR__ . '/request_service.php';

class CourseCreationService
{
    private Repository $repository;
    private RequestService $requestService;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
        $this->requestService = new RequestService();
    }

    public function isEverythingSetForCreate(): bool
{
    return isset(
        $_POST['zkratka'], $_POST['nazev'], $_POST['popis'],
        $_POST['limit'], $_POST['cena'],
        $_POST['den'], $_POST['vyuka_od'], $_POST['vyuka_do'],
        $_POST['room_id']              
    );
}


    public function createCourse(): int
    {
        // povol admina i garanta
        $role = $_SESSION['role'] ?? null;
        if ($role !== PermissionLevel::ADMIN->value && $role !== PermissionLevel::GARANT->value) {
            http_response_code(403);
            return -1;
        }

        $id = $_SESSION['user_id'];

        $data = [
            'zkratka' => $_POST['zkratka'],
            'nazev' => $_POST['nazev'],
            'popis' => $_POST['popis'],
            '`limit`' => $_POST['limit'],
            'cena' => $_POST['cena'],
            'den' => $_POST['den'],
            'vyuka_od' => $_POST['vyuka_od'],
            'vyuka_do' => $_POST['vyuka_do'],
            'garant_ID' => $id,
            'mistnost_ID' => (int)$_POST['room_id']
        ];

        $ret = $this->repository->insert('Kurz', $data);

        if ($ret) {
            $lastId = $this->repository->getLastInsertId();
            if ($_SESSION['role'] == PermissionLevel::ADMIN->value) {

                // do nothing

            } else if ($_SESSION['role'] != PermissionLevel::GARANT->value) {
                $this->requestService->createGarantRequest();
            }
            $this->requestService->createCourseApprovalRequest($lastId, 'Prosim o schválení kurzu');
        } else {
            $lastId = -1;
        }

        return $lastId;
    }

}
