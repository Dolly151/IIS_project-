<?php

require_once __DIR__ . '/../data/repository_factory.php';
require_once __DIR__ . '/permission_service.php';
require_once __DIR__ . '/../common/enums.php';

class RequestService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getRoleRelevantRequests(): array
    {
        if (!isset($_SESSION['role'])) {
            return [];
        }

        $role = $_SESSION['role'];

        if ($role == PermissionLevel::ADMIN->value) {
            $garantRequests = $this->getGarantRequests();
            $courseApprovalRequests = $this->getCourseAprovalRequests();
            return array_merge($garantRequests, $courseApprovalRequests);
        }
        else if ($role == PermissionLevel::GARANT->value) {
            return $this->getCourseRegistrationRequests();
        }
        else {
            return [];
        }
    }

    public function getMyRequests(): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [];
        }

        $userId = $_SESSION['user_id'];
        $requests = $this->repository->getByCondition('Zadost', ['ID', 'datum', 'typ', 'kurz_ID'], ['uzivatel_ID' => $userId]);
        foreach ($requests as &$request) {
            if ($request['typ'] == RequestType::COURSE_REGISTRATION->value) {
                $course = $this->repository->getOneById('Kurz', ['nazev'], $request['kurz_ID']);
                if ($course) {
                    $request['kurz_ID'] = $course;
                }
            }
        }
        return $requests;
    }

    private function getCourseAprovalRequests(): array
    {
        $requests = $this->repository->getByCondition('Zadost', ['ID', 'datum', 'typ', 'uzivatel_ID', 'kurz_ID'], ['typ' => RequestType::COURSE_APPROVAL->value]);
        foreach ($requests as &$request) {
            $course = $this->repository->getOneById('Kurz', ['nazev'], $request['kurz_ID']);
            if ($course) {
                $request['kurz_ID'] = $course;
            }

            $user = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $request['uzivatel_ID']);
            if ($user) {
                $request['uzivatel_ID'] = $user;
            }
        }
        return $requests;
    }

    private function getGarantRequests(): array
    {
        $requests = $this->repository->getByCondition('Zadost', ['ID', 'datum', 'typ', 'uzivatel_ID'], ['typ' => RequestType::GARANT_REQUEST->value]);
        foreach ($requests as &$request) {
            $user = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $request['uzivatel_ID']);
            if ($user) {
                $request['uzivatel_ID'] = $user;
            }
        }
        return $requests;
    }

    public function createGarantRequest(string $popis = ''): bool
    {
        $data = [
            'datum' => date('Y-m-d'),
            'typ' => RequestType::GARANT_REQUEST->value,
            'uzivatel_ID' => $_SESSION['user_id'],
            'popis' => $popis
        ];

        return $this->repository->insert('Zadost', $data);
    }

    public function createCourseRegistrationRequest(int $courseId): bool
    {
        $data = [
            'datum' => date('Y-m-d'),
            'typ' => RequestType::COURSE_REGISTRATION->value,
            'uzivatel_ID' => $_SESSION['user_id'],
            'kurz_ID' => $courseId
        ];

        return $this->repository->insert('Zadost', $data);
    }

    public function createCourseApprovalRequest(int $courseId, string $popis = ''): bool
    // you need to create the kurz first
    {
        $data = [
            'datum' => date('Y-m-d'),
            'typ' => RequestType::COURSE_APPROVAL->value,
            'uzivatel_ID' => $_SESSION['user_id'],
            'kurz_ID' => $courseId,
            'popis' => $popis
        ];

        return $this->repository->insert('Zadost', $data);
    }

    private function getCourseRegistrationRequests(): array
    {
        $requests = $this->repository->getByCondition('Zadost', ['ID', 'datum', 'typ', 'uzivatel_ID', 'kurz_ID'], ['typ' => RequestType::COURSE_REGISTRATION->value]);
        foreach ($requests as &$request) {
            $course = $this->repository->getOneById('Kurz', ['nazev'], $request['kurz_ID']);
            if ($course) {
                $request['kurz_ID'] = $course;
            }

            $user = $this->repository->getOneById('Uzivatel', ['jmeno', 'prijmeni'], $request['uzivatel_ID']);
            if ($user) {
                $request['uzivatel_ID'] = $user;
            }
        }
        return $requests;
    }

    public function approveRequest(int $requestId): bool
    {
        $request = $this->repository->getOneById('Zadost', ['typ'], $requestId);
        if (!$request || !isset($request['typ'])) {
            return false;
        }

        if ($request['typ'] == RequestType::GARANT_REQUEST->value) {
            
            return $this->approveGarantRequest($requestId);
        }
        else if ($request['typ'] == RequestType::COURSE_REGISTRATION->value) {
            
            return $this->approveCourseRegistrationRequest($requestId);
        }
        else if ($request['typ'] == RequestType::COURSE_APPROVAL->value) {
            return $this->approveCourseApprovalRequest($requestId);
        }
        else {
            return false;
        }
    }

    private function approveGarantRequest(int $requestId): bool
    {
        PermissionService::requireRole(PermissionLevel::ADMIN);

        $request = $this->repository->getOneById('Zadost', ['uzivatel_ID'], $requestId);
        if ($request && isset($request['uzivatel_ID'])) {
            $ret = $this->repository->updateId('Uzivatel', $request['uzivatel_ID'], ['role' => PermissionLevel::GARANT->value]);
            if ($ret) {
                return $this->removeRequestById($requestId);
            }
        }
        return false;
    }

    private function approveCourseRegistrationRequest(int $requestId): bool
    {
        PermissionService::requireRole(PermissionLevel::GARANT);

        $request = $this->repository->getOneById('Zadost', ['uzivatel_ID', 'kurz_ID'], $requestId);
        if (!$request || !isset($request['uzivatel_ID']) || !isset($request['kurz_ID'])) {
            return false;
        }

        $data = [
            'uzivatel_ID' => $request['uzivatel_ID'],
            'kurz_ID' => $request['kurz_ID'],
        ];

        $user = $this->repository->getOneById('Uzivatel', ['role'], $request['uzivatel_ID']);
        if ($user['role'] == PermissionLevel::GUEST->value) {
            $permissionService = new PermissionService();
            $permissionService->setUserPermissionLevel($request['uzivatel_ID'], PermissionLevel::STUDENT);
        }

        $ret = $this->repository->insert('student_navstevuje_kurz', $data);
        if ($ret) {
            return $this->removeRequestById($requestId);
        }

        return false;
    }

    private function approveCourseApprovalRequest(int $requestId): bool
    {
        PermissionService::requireRole(PermissionLevel::ADMIN);

        $request = $this->repository->getOneById('Zadost', ['kurz_ID'], $requestId);
        if (!$request || !isset($request['kurz_ID'])) {
            return false;
        }

        $ret = $this->repository->updateId('Kurz', $request['kurz_ID'], ['status' => 1]);
        if ($ret) {
            return $this->removeRequestById($requestId);
        }

        return false;
    }

    public function denyRequest(int $requestId): bool
    {
        $request = $this->repository->getOneById('Zadost', ['typ'], $requestId);

        if (isset($request['typ'])) {
            if ($request['typ'] == RequestType::COURSE_APPROVAL->value) {
                PermissionService::requireRole(PermissionLevel::ADMIN);
            }
            else if ($request['typ'] == RequestType::GARANT_REQUEST->value) {
                PermissionService::requireRole(PermissionLevel::ADMIN);
            }
            else if ($request['typ'] == RequestType::COURSE_REGISTRATION->value) {
                PermissionService::requireRole(PermissionLevel::GARANT);
            }
            return $this->removeRequestById($requestId);
        }

        return false;
    }

    private function removeRequestById(int $requestId): bool
    {
        return $this->repository->deleteById('Zadost', $requestId);
    }


}