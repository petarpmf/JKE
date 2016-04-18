<?php
namespace Jke\Jobs\Interfaces;

interface QualificationInterface
{
    public function all();
    public function create(array $data);
    public function getById($userId);
    public function update($data);
    public function delete($userId, $qualificationId);
}