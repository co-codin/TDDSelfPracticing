<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
      $this->withExceptionHandling()
          ->get('/threads/create')
          ->assertRedirect('/login');

      $this->post('/threads')
          ->assertRedirect('/login');
    }



    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        // dd($response->headers->get('Location')); the Redirect Path

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
      $this->publishThread(['title' => null])
          ->assertSessionHasErrors('title');
      // $this->withExceptionHandling()->signIn();
      //
      // $thread = make('App\Thread', ['title' => null]);
      //
      // $this->post('/threads', $thread->toArray())
      //     ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
      $this->publishThread(['body' => null])
          ->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
      factory('App\Channel', 2)->create();

      $this->publishThread(['channel_id' => null])
          ->assertSessionHasErrors('channel_id');

          $this->publishThread(['channel_id' => 999])
              ->assertSessionHasErrors('channel_id');

    }

    protected function publishThread($overrites = [])
    {
      $this->withExceptionHandling()->signIn();

      $thread = make('App\Thread', $overrites);

      return $this->post('/threads', $thread->toArray());
    }
}
