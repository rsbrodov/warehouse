
@extends('admin.main')
@section('content')

    <div class="container">
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>
        <div class="d-flex justify-content-center"><h1>Справочники</h1></div>
        <table class="table table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Код</th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Архив</th>
                <th>Создатель</th>
                <th>Дата создания</th>
                <th>Последний редактор</th>
                <th>Дата редактирования</th>
            </tr>
            @foreach($dictionaries as $dictionary)
                <tr>
                    <td>{{$dictionary->id}}</td>
                    <td>{{$dictionary->code}}</td>
                    <td>{{$dictionary->name}}</td>
                    <td>{{$dictionary->description}}</td>
                    <td>{{$dictionary->archive?'Да':'Нет'}}</td>
                    <td {{$dictionary->created_author == Auth::guard('web')->user()->id?'class="bg-danger"':''}}>{{\App\Models\User::where('id', $dictionary->created_author)->first()->name}}</td>
                    <td>{{$dictionary->created_at}}</td>
                    <td>{{\App\Models\User::where('id', $dictionary->updated_author)->first()->name}}</td>
                    <td>{{$dictionary->updated_at}}</td>
                    <td nowrap>
                        <a href="{{route('dictionary.show', ($dictionary->id))}}"
                           class="btn btn-info ">
                            <img src="{{asset('images/show.png')}}" width="32" height="32" alt="">
                        </a>
                        <a href="{{route('dictionary.edit', ($dictionary->id))}}" class="btn btn-primary">
                            <img src="{{asset('images/edit.png')}}" width="32" height="32" alt="">
                        </a>
                        <a href="{{route('dictionary_element.index', $dictionary->id)}}" class="btn btn-success">
                            <img src="{{asset('images/add.png')}}" width="32" height="32" alt="">
                        </a>
                        <a href="{{route('dictionary.archive', $dictionary->id)}}" class="btn btn-secondary">
                            @if(\App\Models\Dictionary::find($dictionary->id)->archive == '1')
                                <img src="{{asset('images/revive.png')}}" width="32" height="32" alt="">
                            @else
                                <img src="{{asset('images/archive.png')}}" width="32" height="32" alt="">
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('dictionary.create')}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
