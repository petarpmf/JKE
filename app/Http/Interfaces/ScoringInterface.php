<?php
namespace App\Http\Interfaces;

interface ScoringInterface
{
    public function create(array $data);
    //public function getConnection();
    //public function where($searchFor);
    //public function all();
    public function paginate($perPage, $withTrashed);
    public function getById($userId);
    public function getAutomaticById($userId, $desiredJobId);
    //public function update($id, $data);
    public function delete($userId);
    //public function restore($id);
}