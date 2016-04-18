<?php
namespace Jke\Jobs\Interfaces;

interface CertificateInterface
{
    public function all();
    public function create(array $data);
    public function getById($userId);
    public function getCertificateById($userId, $certificateId);
    public function update($data);
    public function delete($userId, $certificateId);
}