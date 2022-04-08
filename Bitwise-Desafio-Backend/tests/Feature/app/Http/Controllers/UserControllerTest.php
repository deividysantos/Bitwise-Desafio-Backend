<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_be_create()
    {
        $payload = [
            'userName' => 'user name',
            'name' => 'test',
            'lastName' => 'test',
            'profileImageUrl' => 'https://avatars.githubusercontent.com/u/56976743?v=4',
            'bio' => 'I am a test',
            'email' => 'test@example.com',
            'gender' => 'male'
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);


        $response->assertExactJson([
            'message' => 'User created successfully!'
        ]);
    }

    public function test_user_should_be_create_without_fields_required()
    {
        $payload = [
            'name' => 'teste',
            'email' => 'test@example.com',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

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
            ->post(Route('user.create'), $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'details' => [
                'name' =>[
                    'The name field is required.'
                ]
        ],
            'message' => 'Invalid data send'
        ]);
    }

    public function test_user_should_not_be_created_when_not_send_email()
    {
        $payload = [
            'name' => 'teste',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

        $response->assertStatus(400);
        $response->assertExactJson([

    'details' => [
        'email' => [
            'The email field is required.'
        ]
    ],
    'message' => 'Invalid data send'
        ]);
    }

    public function test_email_should_be_a_valid_email()
    {
        $payload = [
            'name' => 'teste',
            'email' => 'test',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

        $response->assertStatus(400);
        $response->assertExactJson([

            'details' => [
                'email' => [
                    'The email must be a valid email address.'
                ]
            ],
            'message' => 'Invalid data send'
        ]);
    }

    public function test_name_should_not_be_greater_than_30_characters()
    {
        $payload = [
            'name' => 'umaPalavraMuitoGrandeQuePrecisaSerMaiorQue30Caracteres',
            'email' => 'test',
        ];

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

        $response->assertStatus(400);
        $response->assertExactJson([
            'details' => [
                'email' => [
                    'The email must be a valid email address.'
                ],
                'name' => [
                    'The name must not be greater than 30 characters.'
                ]
            ],
            'message' => 'Invalid data send'
        ]);
    }

    public function test_email_should_be_unique()
    {
        $payload = [
            'name' => 'teste',
            'email' => 'test@example.com',
        ];

        $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

        $response = $this->withHeaders(['Accept', 'application/json'])
            ->post(Route('user.create'), $payload);

        $response->assertStatus(400);
        $response->assertExactJson([

            'details' => [
                'email' => [
                    'The email has already been taken.'
                ]
            ],
            'message' => 'Invalid data send'

        ]);

    }
}
