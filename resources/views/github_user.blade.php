<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 50px 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .repo-item {
                position: relative;
            }

            .repo-item button {
                position: absolute;
                right: 0;
            }

        </style>

        <!-- vue js -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
{{--        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>--}}

        <!-- axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    </head>
    <body>
        <div class="flex-center position-ref mt-1">
            @verbatim
                <div id="app">
                    <div class="form-group">
                        <form @submit.prevent>
                            <input type="text" class="form-control" id="repoName" placeholder="Введите логин" v-model="userLogin">
                            <button type="submit" @click="addUser">Добавить</button>
                        </form>
                    </div>
                    <ul class="list-group" v-for="(user, index) in users">
                        <li class="list-group-item repo-item">
                            <span>{{ user }}</span>
                            <button @click="remove(index)" class="btn btn-primary float-right">Удалить</button>
                        </li>
                    </ul>
                    <button class="btn btn-success" @click="loadRepos">Получить репозитории</button>
                    <table class="table" v-if="repos.length">
                        <thead>
                        <tr>
                            <th scope="col">Название</th>
                            <th scope="col">Дата последнего изменения</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="repo in repos">
                            <td>{{repo.name}}</td>
                            <td>{{repo.updated_at}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endverbatim
        </div>
    </body>

    <script>
        var app = new Vue({
            el: '#app',
            data: {
                userLogin: '',
                users: ['NurbekAbilev', 'Laravel'],
                repos: [],
            },
            methods: {
                remove(index) {
                    if (index > -1) {
                        this.users.splice(index, 1);
                    }
                },
                addUser() {
                    if(this.userLogin){
                        this.users.push(this.userLogin);
                        this.userLogin = '';
                    }
                },
                loadRepos() {
                    axios
                        .get('/api/repos', {
                            params: {
                                users : this.users
                            }
                        })
                        .then((result) => {
                            console.log(result);
                            this.repos = result.data.repos
                        })
                }
            }
        })
    </script>
</html>
