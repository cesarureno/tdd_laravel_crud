<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_of_posts_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        Post::factory()->count(3)->create();

        $response = $this->get('posts');

        $response->assertOk();

        $posts = Post::all();

        $response->assertViewIs('posts.index');
        $response->assertViewHas('posts', $posts);
    }

    /** @test */
    public function a_post_can_be_created()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('posts', [
            'title' => 'Post title',
            'content' => 'Content',
        ]);

        $this->assertCount(1, Post::all());

        $post = Post::first();

        $this->assertEquals($post->title, 'Post title');
        $this->assertEquals($post->content, 'Content');

        $response->assertRedirect('posts/' . $post->id);
    }

    /** @test */
    public function post_title_is_required()
    {
        $response = $this->post('posts', [
            'title' => '',
            'content' => 'Content',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function post_content_is_required()
    {
        $response = $this->post('posts', [
            'title' => 'Title',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function a_post_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $response = $this->get( 'posts/' . $post->id);

        $response->assertOk();

        $response->assertViewIs('posts.show');
        $response->assertViewHas('post', $post);
    }

    /** @test */
    public function a_post_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $response = $this->put('posts/' . $post->id, [
            'title' => 'Post title',
            'content' => 'Content',
        ]);

        $this->assertCount(1, Post::all());

        $post = $post->fresh();

        $this->assertEquals($post->title, 'Post title');
        $this->assertEquals($post->content, 'Content');

        $response->assertRedirect('posts/' . $post->id);
    }

    /** @test */
    public function a_post_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $response = $this->delete('posts/' . $post->id);

        $this->assertCount(0, Post::all());

        $response->assertRedirect('posts');
    }
}
