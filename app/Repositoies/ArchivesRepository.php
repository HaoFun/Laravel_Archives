<?php
namespace App\Repositoies;

use App\Archives;

class ArchivesRepository
{
    public function create(array $data)
    {
        return Archives::create($data);
    }

    public function ByID($id)
    {
        return Archives::findOrFail($id);
    }
}