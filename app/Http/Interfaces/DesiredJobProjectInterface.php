<?php
namespace App\Http\Interfaces;

interface DesiredJobProjectInterface
{
    public function create(array $data);
    public function getById($projectId);
    public function update($projectId, $data);
    public function delete($projectId, $staffId);

}