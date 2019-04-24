<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table = "apis";
    protected $fillable = ['name', 'description', 'photo'];
    public $timestamps = false;
}
