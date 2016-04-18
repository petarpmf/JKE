<?php
namespace App\Http\Interfaces;

interface PersonalDetailInterface
{
    public function create(array $data);
    public function getById($userId);
    public function createAdditional(array $data);
}