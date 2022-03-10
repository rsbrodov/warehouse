@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование справочника {{$edit_dictionary->name}}</h1>
        <form action="{{route('dictionary.update', [$edit_dictionary])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Наименование</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя"
                       value="{{$edit_dictionary->name}}">
                <label for="description">Описание</label>
                <textarea class="form-control" id="description" name="description"
                          placeholder="Введите описание">{{$edit_dictionary->description}}</textarea>
                <label for="code">Код</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Введите код"
                       value="{{$edit_dictionary->code}}">
                <label for="archive">Архив</label>
                <select id="archive" type="text" class="form-control" name="archive">
                    <option {{$edit_dictionary->archive?'':'selected'}} value="0">Нет</option>
                    <option {{$edit_dictionary->archive?'selected':''}} value="1">Да</option>
                </select>
                <br>
                <button type="submit" class="btn btn-success">Редактировать</button>
            </div>
        </form>
    </div>
@endsection
