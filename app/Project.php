<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;


class Project extends Model
{
    protected $fillable = ['title', 'description'];


    public function path()
    {

    	return '/projects/' . $this->id;

    }


    public function owner()
    {

    	return $this->belongsTo(User::class);

    }


}
