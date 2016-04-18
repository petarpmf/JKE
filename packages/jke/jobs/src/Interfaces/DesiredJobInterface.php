<?php
namespace Jke\Jobs\Interfaces;

interface DesiredJobInterface
{
    public function all();
    public function create(array $data);
    public function getById($userId);
    public function delete($userId, $desiredJobId);

    /**
     * Get desired job by ID
     *
     * @param $desiredJobId
     * @return mixed
     */
    public function getDesiredJobById($desiredJobId);
}