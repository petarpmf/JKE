<?php

namespace App\Http\Interfaces;

interface NotifyInterface
{
    public function from($data);
    public function to(array $data);
    public function subject($data);
    public function message($data);
    public function send();

}