<?php
namespace App\Repositoies;

use App\User;

class UserRepository
{
    public function UserByID($id)
    {
        return User::findOrFail($id);
    }
}