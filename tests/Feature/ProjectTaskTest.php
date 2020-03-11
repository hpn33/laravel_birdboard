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
    public function only_the_owner_of_a_project_may_add_tasks()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $body = ['body' => 'some text is here.'];

        $this->post($project->path('tasks'), $body)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $body);

    }


    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {

        $this->signIn();

        $project = factory('App\Project')->create();
        $task = $project->addTask('task Task');

        $newBody = ['body' => 'some text is here.'];

        $this->patch($task->path(), $newBody)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $newBody);

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
            'body' => ''
        ]);

        $this->post($project->path('tasks'), $attributes)
            ->assertSessionHasErrors('body');
    }


    /** @test */
    public function a_task_can_be_updated()
    {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );


        $newBody = [
            'body' => 'changed',
            'completed' => true
        ];


        $task = $project->addTask('some text is here.');

        $this->patch($task->path(), $newBody);

        $this->assertDatabaseHas('tasks', $newBody);

    }

}
