<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    protected $fillable = ['title','body','user_id'];
}
