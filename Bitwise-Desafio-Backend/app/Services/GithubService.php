<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;

class GithubService
{
    public function getByUserName(array $payload): array
    {
        $data = $this->getData($payload['userName']);

        return $this->formatData($data, $payload);
    }

    public function userNameExists(string $userName): bool
    {
        $data = $this->getData($userName);

        return isset($data['login']);
    }

    private function getData(string $username): array
    {
        $url = config('github.baseUrl') . '/users/'. strtolower($username);

        $response = Http::acceptJson()->get($url);

        return $response->json();
    }

    private function formatData(array $data, array $payload): array
    {
        $fullName = explode(' ',$data['name']);

        $name = $fullName[0];
        $lastName = $fullName[count($fullName) - 1];

        return [
            'userName' => $data['login'],
            'name' => $name,
            'lastName' =>  $lastName,
            'profileImageUrl' => $data['avatar_url'],
            'bio' => $data['bio'],
            'email' => $payload['email'],
            'gender' => $payload['gender'],
        ];
    }
}
