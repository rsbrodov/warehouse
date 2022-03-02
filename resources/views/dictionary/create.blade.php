<?use \App\Models\User;?>
{{--@extends('admin.main')--}}
@extends('layouts.app')
@section('content')
    <div class="container">

        <h1>Создание справочника</h1>
        <form action="{{route('dictionary.store')}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                <div class="col-md-6">
                    <input type="text" name="name" placeholder="Введите название" id="name" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                <div class="col-md-6">
                    <textarea name="description" placeholder="Введите описание" id="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="code" class="col-md-4 col-form-label text-md-right">Код</label>
                <div class="col-md-6">
                    <input type="text" name="code" placeholder="Введите код" id="code" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="archive" class="col-md-4 col-form-label text-md-right">Архив</label>
                <div class="col-md-6">
                    <select id="archive" type="text" class="form-control" name="archive">
                            <option value="0">Нет</option>
                            <option value="1">Да</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Создать</button>

        </form>
    </div>
@endsection
