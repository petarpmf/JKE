<?php
namespace App\Http\Interfaces;

interface TokenInterface
{
    public function generate();
    public function save($token,$user);
    public function verify($token);
    public function destroy($token);
    public function getUserId($token);
}