<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
            }
        </style>
        @include('top')
    </head>
    <body>
        <div id="app">
            @if(session('token'))
                <app text="Get employees"
                     redirect="{{ route('getEmployees') }}"
                     csrf="{{csrf_token()}}"
                     delete_tokens="1"
                >

                </app>
            @else
                <app
                    text="Get token"
                    redirect="{{ route('redirect') }}"
                    csrf="{{csrf_token()}}"
                    deleteTokens="0"
                >

                </app>
            @endif
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="employees-container">
            @if (isset($data) && count($data) > 0)
                @foreach($data as $employee)
                    <div class="employee">
                        <p>Name: {{$employee['title']}} {{$employee['first_name']}} {{$employee['last_name'] }}</p>
                        <hr>
                        <p>Date of birth: {{$employee['date_of_birth']}}</p>
                        <hr>
                        <p>Email: {{$employee['email']}}</p>
                        <hr>
                        <p>Country: {{$employee['country']}}</p>
                        <hr>
                        <p>Address: {{$employee['address']}}</p>
                        <hr>
                        <p>{{$employee['bio']}}</p>
                    </div>
                @endforeach
            @endif
        </div>
        <script src="/js/app.js"></script>
    </body>
    @include('bottom')
</html>
