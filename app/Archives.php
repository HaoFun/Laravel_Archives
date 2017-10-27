<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    protected $fillable = ['title','body','user_id'];

    public function topics()
    {
        //withTimestamps 操作時自動填充created_at & updated_at
        //第二參數為table name
        return $this->belongsToMany(Topics::class,'archives_topics')->withTimestamps();
    }
}
