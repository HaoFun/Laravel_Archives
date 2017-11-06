<?php

namespace App\Http\Controllers;

use App\Repositoies\UserRepository;
use Illuminate\Http\Request;
use Auth;

class FollowersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $user = $this->userRepository->UserByID(request('user'));
        $followers = $user->followersUser()->pluck('follower_id')->toArray();  //查找要關注的這個用戶，他的所有被關注ID
        if(in_array(Auth::guard('api')->user()->id,$followers))
        {
            return response()->json(['followed' => true,'archives_count' => $user->archives_count,'answers_count' => $user->answers_count,'followers_count' => $user->followers_count]);
        }
        return response()->json(['followed' => false,'archives_count' => $user->archives_count,'answers_count' => $user->answers_count,'followers_count' => $user->followers_count]);
    }

    public function follow(Request $request)
    {
        //取得該文章作者ID
        $userToFollow = $this->userRepository->UserByID(request('user'));
        //判斷使用者與文章作者關係 (關注或未關注)
        $followed = Auth::guard('api')->user()->followThisUser($userToFollow->id);
        if(count($followed['attached']) > 0)
        {
            //被關注者 用戶followers_count欄位遞增
            $userToFollow->increment('followers_count');
            //關注者 用戶followings_count欄位遞增
            Auth::guard('api')->user()->increment('followings_count');
            return response()->json(['followed' => true,'archives_count' => $userToFollow->archives_count,'answers_count' => $userToFollow->answers_count,'followers_count' => $userToFollow->followers_count]);
        }
        //被關注者 用戶followers_count欄位遞減
        $userToFollow->decrement('followers_count');
        //關注者 用戶followings_count欄位遞減
        Auth::guard('api')->user()->decrement('followings_count');
        return response()->json(['followed' => false,'archives_count' => $userToFollow->archives_count,'answers_count' => $userToFollow->answers_count,'followers_count' => $userToFollow->followers_count]);
    }
}
