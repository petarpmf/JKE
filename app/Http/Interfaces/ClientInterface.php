<?php
namespace App\Http\Interfaces;

interface ClientInterface
{
    public function create(array $data);
    public function searchClients(array $queryString, $perPage);
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
}