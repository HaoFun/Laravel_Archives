<?php
namespace App\Repositoies;

use App\User;

class EmailRepository
{
    public function getTokenByEmail($token)
    {
        return User::where('confirmation_token',$token)->first();
    }
}