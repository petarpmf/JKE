<?php
namespace Jke\Jobs\Interfaces;

interface ExperienceInterface
{
    public function all();
    public function create(array $data);
    public function getById($userId);
    public function getExperienceById($userId, $experienceId);
    public function update($data);
    public function delete($userId, $experienceId);
}