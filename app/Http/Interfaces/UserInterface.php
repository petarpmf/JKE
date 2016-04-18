<?php
namespace App\Http\Interfaces;

interface UserInterface
{
    public function create(array $data);
    public function getConnection();
    public function where($searchFor);
    public function all();
    public function paginate($perPage, $withTrashed);
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
    public function generateForgotTokenForUser($email);
    public function resetPassword($data);
    public function restore($id);
    public function searchUser(array $queryString, $perPage);
    public function setUserId($user);
    public function validForgotToken($data);
    public function getFromRigzone($rigzoneId);
    public function changePassword(array $data);
}