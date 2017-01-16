<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Form extends Eloquent
{


    protected $table    = 'forms';
    protected $fillable = ['name','slug','active', 'purpose'];



}
