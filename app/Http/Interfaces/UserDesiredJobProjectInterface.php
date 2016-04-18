<?php
namespace App\Http\Interfaces;

interface UserDesiredJobProjectInterface
{
    public function create(array $data);
    public function getById($stuffId);
    public function delete($staffId, $userId);
    public function searchAllCandidatesInProject($userRoleId, $projectId, array $queryString, $perPage);
}