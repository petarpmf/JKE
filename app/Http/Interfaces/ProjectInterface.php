<?php
namespace App\Http\Interfaces;

interface ProjectInterface
{
    public function create(array $data);
    public function all();
    public function paginate($perPage);
    public function paginateActive($perPage);
    public function getById($projectId);
    public function update($projectId, $data);
    public function delete($projectId);
    public function storeAdditional($data);
    public function getByCompanyId($companyId, $queryString, $perPage);
    public function searchProjects(array $queryString, $perPage);
}