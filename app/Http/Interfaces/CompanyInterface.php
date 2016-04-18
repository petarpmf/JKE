<?php
namespace App\Http\Interfaces;

interface CompanyInterface
{
    public function create(array $data);
    public function all();
    public function paginate($perPage);
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
    public function searchCompanies(array $queryString, $perPage);
}