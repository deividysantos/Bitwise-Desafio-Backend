<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;

class GithubService
{
    public function getByUserName(array $payload): array
    {
        $data = $this->getData($payload['userName']);

        return $this->formatDataToDataBase($data, $payload);
    }

    public function existsByUserName(string $userName): bool
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

    private function formatDataToDataBase(array $dataByGitHub, array $payloadRequest): array
    {
        $fullName = explode(' ',$dataByGitHub['name']);

        $name = $fullName[0];
        $lastName = $fullName[count($fullName) - 1];

        return [
            'userName' => $dataByGitHub['login'],
            'name' => $name,
            'lastName' =>  $lastName,
            'profileImageUrl' => $dataByGitHub['avatar_url'],
            'bio' => $dataByGitHub['bio'],
            'email' => $payloadRequest['email'],
            'gender' => $payloadRequest['gender'],
        ];
    }

    public function getAdditionalInformationsByUserName(string $userName)
    {
        $data = $this->getData($userName);

        return [
            'followers' => $data['followers'],
            'following' => $data['following'],
            'public_repos' => $data['public_repos'],
            'url' => $data['url'],
        ];
    }
}
