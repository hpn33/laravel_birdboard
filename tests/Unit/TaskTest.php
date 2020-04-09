<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{

    use RefreshDatabase;

	/** @test */
    public function is_belongs_to_a_project()
    {

    	$task = factory('App\Task')->create();
    	
        $this->assertInstanceOf('App\Project', $task->project);

    }


    /** @test */
    public function is_has_a_path()
    {

        $task = factory('App\Task')->create();
        

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());

    }


    /** @test */
    public function is_can_be_completed()
    {

        $task = factory('App\Task')->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);

    }

}
