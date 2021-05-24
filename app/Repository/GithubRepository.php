<?php

namespace App\Repository;

/**
 * Класс отвечающий за работу с Github.API
 *
 * Class GithubRepository
 * @package App\Repository
 */
class GithubRepository
{
    /**
     * Возвращает весь список репозиториев пользователей в
     * отсортированном порядке по полю updated_at
     *
     * @param array $users
     * @return mixed
     * @throws \Exception
     */
    public function getUsersRepos(array $users)
    {
        $res = [];

        foreach ($users as $user) {
            $response = $this->getReposByUserLogin($user);
            $userRepos = $this->getUserRepos($response);

            $res = array_merge($res, $userRepos);
        }

        $res = $this->sortRepos($res);

        return $res;
    }

    /**
     * Возвращет репозитории пользователя
     * api.github.com/users/{githubLogin}
     *
     * @param string $githubLogin
     * @return mixed
     * @throws \Exception
     */
    private function getReposByUserLogin(string $githubLogin)
    {
        $curl = \curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.github.com/users/${githubLogin}/repos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0',
                'Accept: application/vnd.github.v3+json',
            ]
        ));

        $response = curl_exec($curl);

        $response = json_decode($response, true);

        if (!empty($response['message'])) {
            throw new \Exception('Произошла ошибка');
        }

        curl_close($curl);

        return $response;
    }

    /**
     * Возвращает нужные поля из ответа Github API
     *
     * @param array $response
     * @return array|array[]
     */
    private function getUserRepos(array $response)
    {
        return array_map(function ($repo) {
            return [
                'name' => $repo['name'],
                'description' => $repo['description'],
                'updated_at' => $repo['updated_at']
            ];
        }, $response);
    }

    /**
     * Сортирует репозитории по полю updated_at
     *
     * @param $repos
     * @return mixed
     */
    public function sortRepos($repos)
    {
        usort($repos, function ($repo1, $repo2) {
            return strtotime($repo1['updated_at']) < strtotime($repo2['updated_at']);
        });

        return $repos;
    }
}
