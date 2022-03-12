<?use \App\Models\User;?>
@extends('admin.main')
@section('content')
    <div class="container">
        <h1>Создание элемента справочника {{\App\Models\Dictionary::find($dictionary_id)->name}}</h1>
        <form action="{{route('dictionary-element.store', $dictionary_id)}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="value" class="col-md-4 col-form-label text-md-right">Значение</label>
                <div class="col-md-6">
                    <input type="text" name="value" placeholder="Введите значение" id="value" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Создать</button>

        </form>
    </div>
@endsection
