<?php

namespace App\Http\Controllers;

use App\Repositoies\TopicsRepository;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    protected $topicsRepository;
    public function __construct(TopicsRepository $topicsRepository)
    {
        $this->topicsRepository = $topicsRepository;
    }

    public function getTopics(Request $request)
    {
        return $this->topicsRepository->getTopicsForTag($request->query('q'));
    }
}
