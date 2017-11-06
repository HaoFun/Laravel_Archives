<?php
namespace App\Repositoies;

use App\Topics;

class TopicsRepository
{
    public function getTopicsForTag($request)
    {
        return Topics::select(['id','name'])->where('name','LIKE','%'.$request.'%')->get();
    }
}