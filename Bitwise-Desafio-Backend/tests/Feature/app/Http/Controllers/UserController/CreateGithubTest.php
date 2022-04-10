<?php

namespace Tests\Feature\app\Http\Controllers\UserController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function route;

class CreateGithubTest extends TestCase
{

    use RefreshDatabase;

    public function test_should_be_able_create_user_by_github()
    {
        $response = $this->post(Route('user.createByGithub'), [
            'userName' => 'deividysantos',
            'name' => 'Deividy',
            'email' => 'deividy@gmail.com',
            'gender' => 'male'
        ]);
        //todo: mock the getData return by GithubService

        $response->assertStatus(201);
        $response->assertExactJson([
            'message' => 'User created successfully!',
            'data' => [
                'userName' => 'deividysantos',
                'name' => 'Deividy',
                'lastName' => '',
                'profileImageUrl' => 'https://avatars.githubusercontent.com/u/56976743?v=4',
                'bio' => null,
                'email' => 'deividy@gmail.com',
                'gender' => 'male'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'userName' => 'deividysantos',
            'name' => 'Deividy',
            'lastName' => '',
            'profileImageUrl' => 'https://avatars.githubusercontent.com/u/56976743?v=4',
            'bio' => null,
            'email' => 'deividy@gmail.com',
            'gender' => 'male'
        ]);
    }

    public function test_should_not_be_able_create_user_when_username_not_exists()
    {
        $response = $this->post(Route('user.createByGithub'), [
            'userName' => 'test4044', //this user not exists
            'name' => "test",
            'email' => 'deividy@gmail.com',
            'gender' => 'male'
        ]);//todo: mock the getData return by GithubService

        $response->assertStatus(404);
        $response->assertExactJson([
            'message' => 'Username not found in github.'
        ]);

        $this->assertDatabaseCount('users', 0);
    }
}
