<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    protected $fillable = ['name','bio','archives_count','followers_count'];

    public function archives()
    {
        //withTimestamps 操作時自動填充created_at & updated_at
        //第二參數為table name
        return $this->belongsToMany(Archives::class,'archives_topics')->withTimestamps();
    }
}
