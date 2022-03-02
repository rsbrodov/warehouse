{{--@extends('admin.main')--}}
@extends('layouts.app')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование справочника {{$edit_element_dictionary->value}}</h1>
        <form action="{{route('dictionary_element.update', [$edit_element_dictionary->dictionary_id, $edit_element_dictionary->id])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="value">Значение</label>
                <textarea class="form-control" id="value" name="value"
                          placeholder="Введите описание">{{$edit_element_dictionary->value}}</textarea>
                <button type="submit" class="btn btn-success">Редактировать</button>
            </div>
        </form>
    </div>
@endsection
