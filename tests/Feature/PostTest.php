<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase, WithFaker;
    protected $user;
    protected $post;
    protected function setUp(): void{
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->user = User::create([
            'name' => $this->faker->name,
            'password' => $this->faker->password,
        ]);
        $this->post = Post::create([
            'user_id' => $this->user->id,
            'content' => 'ini yang pertama',
        ]);
    }
    /** @test */
    public function testPost(){
        $payload = [
            'content' => 'ini yang pertama',
            'user_id' => $this->user->id,
        ];
        $response = $this->actingAs($this->user)->post(route('posts.store'), $payload);

        $this->assertDatabaseHas('posts', [
            'content' => 'ini yang pertama',
            'user_id' => $this->user->id,
        ]);
    }
}
