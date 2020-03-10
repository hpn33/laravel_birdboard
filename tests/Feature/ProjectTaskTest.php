<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{

    use RefreshDatabase;


    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {

        $project = factory('App\Project')->create();

        $this->post($project->path('tasks'))->assertRedirect('login');
    }


    /** @test */
    public function adding_a_task_if_you_are_not_the_project_owner()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $body = ['body' => 'some text is here.'];

        $this->post($project->path('tasks'), $body)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $body);

    }


    /** @test */
    public function a_project_can_have_tasks()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );
        $body = 'some text is here.';

        $this->post($project->path('tasks'), ['body' => $body]);

        $this->get($project->path())
            ->assertSee($body);
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
