<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_is_accessible_by_everyone()
    {
        $response = $this->get(route('job.index'));
        $response->assertStatus(200);
    }

    public function test_post_not_accessible_without_logging_in()
    {
        $response = $this->post(route('job.store'), Job::factory()->create()->toArray());
        $response->assertStatus(302);
    }

    public function test_post_accessible_to_admin()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['admin']
        );

        $response = $this->post(route('job.store'), Job::factory()->create()->toArray());
        $response->assertStatus(200);
    }

    public function test_post_accessible_to_recruiter()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['recruiter']
        );

        $response = $this->post(route('job.store'), Job::factory()->create()->toArray());
        $response->assertStatus(200);
    }

    public function test_post_not_accessible_to_interviewer()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            []
        );

        $response = $this->post(route('job.store'), Job::factory()->create()->toArray());
        $response->assertStatus(403);
    }


    public function test_get_one_is_shown_to_everyone()
    {
        $job = Job::factory()->create();
        $response = $this->get(route('job.show', $job->id));
        $response->assertStatus(200);
    }

    public function test_put_not_accessible_without_logging_in()
    {
        $job = Job::factory()->create()->toArray();
        $response = $this->put(route('job.update', $job['id']), $job);
        $response->assertStatus(302);
    }

    public function test_put_accessible_to_admin()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['admin']
        );
        $oldJob = Job::factory()->create()->toArray();
        $response = $this->put(route('job.update', $oldJob['id']), $oldJob);
        $response->assertStatus(200);
    }

    public function test_put_accessible_to_recruiter()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['recruiter']
        );
        $oldJob = Job::factory()->create()->toArray();
        $response = $this->put(route('job.update', $oldJob['id']), $oldJob);
        $response->assertStatus(200);
    }

    public function test_put_not_accessible_to_interviewer()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            []
        );
        $oldJob = Job::factory()->create()->toArray();
        $response = $this->put(route('job.update', $oldJob['id']), $oldJob);
        $response->assertStatus(403);
    }

    public function test_delete_not_accessible_without_logging_in()
    {
        $job = Job::factory()->create()->toArray();
        $response = $this->delete(route('job.destroy', $job['id']));
        $response->assertStatus(302);
    }

    public function test_delete_accessible_by_admin()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['admin']
        );
        $job = Job::factory()->create()->toArray();
        $response = $this->delete(route('job.destroy', $job['id']));
        $response->assertStatus(200);
    }

    public function test_delete_accessible_by_recruiter()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['recruiter']
        );
        $job = Job::factory()->create()->toArray();
        $response = $this->delete(route('job.destroy', $job['id']));
        $response->assertStatus(200);
    }

    public function test_delete_not_accessible_by_interviewer()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['interviewer']
        );
        $job = Job::factory()->create()->toArray();
        $response = $this->delete(route('job.destroy', $job['id']));
        $response->assertStatus(403);
    }
}
