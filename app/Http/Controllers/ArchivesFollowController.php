<?php

namespace App\Http\Controllers;

use Auth;

class ArchivesFollowController extends Controller
{
    //用戶點擊執行follow方法
    public function follow($archive)
    {
        Auth::user()->followThis($archive);
        return back();
    }
}
