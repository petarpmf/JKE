<?php
namespace Jke\Jobs\Interfaces;

interface ReferenceInterface
{
    public function all();
    public function create(array $data);
    public function paginate($perPage);
    public function getById($userId);
    public function getReferenceById($userId, $referenceId);
    public function update($data);
    public function delete($userId, $referenceId);

    /**
     * Used to check if the reference ID matches the email
     *
     * @param $referenceId
     * @param $email
     * @return bool
     */
    public function checkReferenceByIdAndEmail($referenceId, $email);
}