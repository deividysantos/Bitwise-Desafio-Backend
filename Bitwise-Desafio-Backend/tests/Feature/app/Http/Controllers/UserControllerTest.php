<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_user_should_be_create()
    {
        $payload = [
            'userName' => 'test',
            'name' => 'test',
            'lastName' => 'test',
            'profileImageUrl' => 'https://avatars.githubusercontent.com/u/56976743?v=4',
            'bio' => 'testando o test',
            'email' => 'test@example.com',
            'gender' => null
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(201);
        $response->assertExactJson([
            'message' => 'User created successfully!'
        ]);
    }

    public function test_user_should_be_create_without_fields_required()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@example.com',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(201);
        $response->assertExactJson([
            'message' => 'User created successfully!'
        ]);
    }

    public function test_user_should_not_be_created_when_not_send_name()
    {
        $payload = [
            'email' => 'test@example.com',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'Missing required field: name'
        ]);
    }

    public function test_user_should_not_be_created_when_not_send_email()
    {
        $payload = [
            'name' => 'test',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'Missing required field: email'
        ]);
    }

    public function test_email_should_be_a_valid_email()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'The field email not is valid'
        ]);
    }

    public function test_name_should_not_be_greater_than_30_characters()
    {
        $payload = [
            'name' => 'umaPalavraMuitoGrandeQuePrecisaSerMaiorQue30Caracteres',
            'email' => 'test',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'The name field is great!'
        ]);
    }

    public function test_email_should_be_unique()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@example.com',
        ];

        $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post('http://127.0.0.1:8000/api/user', $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'Email already used!'
        ]);
    }
}
