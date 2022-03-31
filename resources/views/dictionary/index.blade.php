
@extends('admin.main')
@section('content')

    <div class="container">
        <p></p>
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
                           class="btn btn-success ">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary.edit', ($dictionary->id))}}" class="btn btn-primary">
                            <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary-element.index', $dictionary->id)}}" class="btn btn-success">
                            <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary.archive', $dictionary->id)}}" class="btn btn-secondary">
                            @if(\App\Models\Dictionary::find($dictionary->id)->archive == '1')
                                <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-file-archive-o fa-lg" aria-hidden="true"></i>
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('dictionary.create')}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
