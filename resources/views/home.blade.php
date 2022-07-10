@extends('admin.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{--            <div class="col-md-8"><a class="btn btn-info" href="{{ route('users.index') }}"><img src="{{asset('images/new_user.png')}}" width="32" height="32" alt=""></a>--}}
            {{--           <a class="btn btn-danger" href="{{ route('users.roles-create-view') }}"><img src="{{asset('images/roles.png')}}" width="32" height="32" alt=""></a>--}}
            {{--           <a class="btn btn-outline-warning" href="{{ route('dictionary.index') }}"><img src="{{asset('images/dictionary.png')}}" width="32" height="32" alt=""></a></div>--}}
            {{--                        <a class="btn btn-warning" href="{{ route('link.handler', 'fuckinglinkingnotless50synBOLsehehelol') }}"> Тест ссылки 1</a>--}}
            <a class="btn btn-primary" href="{{ route('home-gvt') }}"> Тест GridView</a>
            <a class="btn btn-danger" href="{{ route('home-test') }}">Создать 10000 типов контента</a>
            <a class="btn btn-outline-primary" href="{{ route('home-delete-test10000TC') }}">Удалить 10000 типов контента</a>
            <a class="btn btn-warning" href="{{ route('home-test2') }}">Создать 10000 справочников</a>
            <a class="btn btn-outline-primary" href="{{ route('home-delete-test10000D') }}">Удалить 10000 справочников</a>

           <a class="btn btn-primary" href="{{ route('image.show') }}"> Показать все картинки</a>
{{--            <a class="btn btn-outline-warning" href="{{ route('mail.send') }}"> Отправить письмо себе на hsxcms@gmail.com</a>--}}

{{--            <table>--}}
{{--                @for($i = 0; $i < 100; $i++)--}}
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <?php--}}
{{--                            $random_number = '33';--}}
{{--                            for($j = 0; $j < 5; $j++){--}}
{{--                                $random_number .= rand(0,9);--}}
{{--                            }--}}
{{--                            echo $random_number;--}}
{{--                            $random_number = '';--}}
{{--                            ?>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endfor--}}
{{--            </table>--}}

            @can('read-component')
                <a class="btn btn-success" href="{{ route('tech.create') }}"> Create New Role</a>
            @endcan
            @auth
                <p class="text-center">Auth </p>
            @endauth
        </div>
    </div>

@endsection
