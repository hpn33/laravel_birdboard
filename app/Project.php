<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Task;
use \Illuminate\Support\Arr;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'notes'];

    public $old = [];


    public function path($extend = '')
    {

        $path = '/projects/' . $this->id;

        if ($extend != '')
        {
            $path .= '/' . $extend;
        }

    	return $path;

    }


    public function owner()
    {

    	return $this->belongsTo(User::class);

    }


    public function tasks()
    {

    	return $this->hasMany(Task::class);

    }


	public function addTask($body)
    {

    	return $this->tasks()->create(compact('body'));

    }


    public function activity()
    {

        return $this->hasMany(Activity::class)->latest();

    }



    public function recordActivity($description)
    {

        $this->activity()->create([

            'description' => $description,
            'changes' => $this->activityChanges($description)

        ]);

    }


    protected function activityChanges($description)
    {

        if ($description == 'updated') {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
        }
    } 


}
