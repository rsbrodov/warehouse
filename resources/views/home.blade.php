@extends('admin.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            </div>
                        <a class="btn btn-warning" href="{{ route('link.handler', 'fuckinglinkingnotless50synBOLsehehelol') }}"> Тест ссылки 1</a>
                        <a class="btn btn-primary" href="{{ route('link.handler', 'thislinkismysEcondtestlink') }}"> Тест ссылки 2</a>
                        <a class="btn btn-danger" href="{{ route('yurk.user-create-view') }}"> Ссылка для регистрации пользователя</a>

                    @can('read-component')
                        <a class="btn btn-success" href="{{ route('tech.create') }}"> Create New Role</a>
                    @endcan

                    <? if(Auth::guard('api')) {?>
                           <p class="text-center">123 </p>
                    <?}?>
                </div>
            </div>
    </div>
@endsection
