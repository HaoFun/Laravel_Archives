<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $fillable = ['user_id','archive_id','body'];

    //與users 一對一關係
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    //與archives 一對一關係
    public function archives()
    {
        return $this->belongsTo(Archives::class,'archive_id');
    }
}
