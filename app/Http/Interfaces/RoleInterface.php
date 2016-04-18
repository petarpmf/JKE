<?php
namespace App\Http\Interfaces;

interface RoleInterface
{
    public function create(array $data);
    public function getConnection();
    public function where($searchFor);
    public function all();
    public function paginate($perPage, $withTrashed);
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
    public function restore($id);
}