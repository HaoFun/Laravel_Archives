<?php
namespace App\Repositoies;

use App\Answers;

class AnswersRepository
{
    public function AnswerCreate(array $data)
    {
        return Answers::create($data);
    }
}