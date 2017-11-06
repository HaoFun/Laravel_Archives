<?php

namespace App\Http\Controllers;

use App\Repositoies\ArchivesRepository;
use Auth;
use Illuminate\Http\Request;

class ArchivesFollowController extends Controller
{
    protected $archivesRepository;
    public function __construct(ArchivesRepository $archivesRepository)
    {
        $this->middleware(['auth']);
        $this->archivesRepository = $archivesRepository;
    }

    public function follower(Request $request)
    {
        $user = Auth::guard('api')->user();
        $followed = $user->followed(request('archive'));
        $archive = $this->archivesRepository->ArchiveByID(request('archive'));
        if($followed){
            return response()->json(['followed' => true,'followers_count' => $archive->followers_count]);
        }
        return response()->json(['followed' => false,'followers_count' => $archive->followers_count]);
    }

    public function followThis(Request $request)
    {
        $user = Auth::guard('api')->user();
        $archive = $this->archivesRepository->ArchiveByID(request('archive'));
        $followed = $user->followThis(request('archive'));
        if(count($followed['detached']) > 0){
            $archive->decrement('followers_count');
            return response()->json(['followed' => false,'followers_count' => $archive->followers_count]);
        }
        $archive->increment('followers_count');
        return response()->json(['followed' => true,'followers_count' => $archive->followers_count]);
    }
}
