<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;

class TriggerActivityTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);
        });
    }


    /** @test */
    public function updating_a_project()
    {
        
        $project = ProjectFactory::create();
        $orginalTitle = $project->title;

        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity) use ($orginalTitle) {
            $this->assertEquals('updated_project', $activity->description);


            $expected = [
                'before' => ['title' => $orginalTitle],
                'after' => ['title' => 'Changed']
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }


    /** @test */
    public function creating_a_new_task()
    {

        $project = ProjectFactory::create();
        $project->addTask('Some Task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf('App\Task', $activity->subject);
            $this->assertEquals('Some Task', $activity->subject->body);
        });
        

    }


    /** @test */
    public function completed_a_task()
    {

        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf('App\Task', $activity->subject);
        });

    }

    /** @test */
    public function incomplete_a_task()
    {

        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => false
            ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);        
        $this->assertEquals('incomplete_task', $project->activity->last()->description);
    }


    public function deleting_a_task()
    {

        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);

    }

}
