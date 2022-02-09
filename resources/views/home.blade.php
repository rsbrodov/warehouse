@extends('admin.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <a class="btn btn-info" href="{{ route('users.user-create-view') }}"> Регистрация пользователей</a>
            <div class="col-md-8">
            </div>
{{--                        <a class="btn btn-warning" href="{{ route('link.handler', 'fuckinglinkingnotless50synBOLsehehelol') }}"> Тест ссылки 1</a>--}}
{{--                        <a class="btn btn-primary" href="{{ route('link.handler', 'thislinkismysEcondtestlink') }}"> Тест ссылки 2</a>--}}


                    @can('read-component')
                        <a class="btn btn-success" href="{{ route('tech.create') }}"> Create New Role</a>
                    @endcan
                    @auth
                           <p class="text-center">123 </p>
                    @endauth
                </div>
            </div>
    </div>
@endsection
