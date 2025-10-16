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
        return isset($_POST['zkratka']) && isset($_POST['nazev']) && isset($_POST['popis']) && isset($_POST['limit']) && isset($_POST['cena']);
    }

    public function createCourse(): int
    {
        // PermissionService::requireRole(PermissionLevel::ADMIN);

        $id = $_SESSION['user_id'];

        $data = [
            'zkratka' => $_POST['zkratka'],
            'nazev' => $_POST['nazev'],
            'popis' => $_POST['popis'],
            '`limit`' => $_POST['limit'],
            'cena' => $_POST['cena'],
            'garant_ID' => $id
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
