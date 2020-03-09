<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $this->post($project->path() . '/tasks', ['body' => 'some text is here.']);

        $this->get($project->path())
            ->assertSee('some text is here.');
    }


    /** @test */
    public function a_task_requires_a_body()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $attributes = factory('App\Task')->raw([
            // 'project_id' => $project->id,
            'body' => ''
        ]);

        $this->post($project->path('tasks'), $attributes)
            ->assertSessionHasErrors('body');
    }


}
