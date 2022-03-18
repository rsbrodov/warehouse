@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование элемента справочника {{$edit_element_dictionary->value}}</h1>
        <form action="{{route('dictionary-element.update', [$edit_element_dictionary])}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="value">Значение</label>
                <textarea class="form-control" id="value" name="value" placeholder="Введите описание">{{$edit_element_dictionary->value}}</textarea>
{{--                <input type="text" class="form-control" id="value" name="value" placeholder="Введите value" value="{{$edit_element_dictionary->value}}">--}}
{{--                {{dd($edit_element_dictionary)}}--}}
                <button type="submit" class="btn btn-success">Редактировать</button>
            </div>
        </form>
    </div>
@endsection
