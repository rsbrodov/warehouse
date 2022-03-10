@extends('admin.main')
@section('content')
    <div class="container">

        <h1>Создание типа контента</h1>
        <form action="{{route('type_content.store')}}" method="post">
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
                    <input type="text" name="description" placeholder="Введите описание" id="description" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                <div class="col-md-6">
                    <input type="date" name="active_from" placeholder="Введите активен с" id="active_from" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                <div class="col-md-6">
                    <input type="date" name="active_after" placeholder="Введите активен по" id="active_after" class="form-control">
                </div>
            </div>
            <?php $icons_array = ['one', 'two', 'three']; ?>
            <div class="form-group row">
                <label for="icon" class="col-md-4 col-form-label text-md-right">Иконка</label>
                <div class="col-md-6">
                    <select id="icon" type="text" class="form-control" name="icon">
                        @foreach($icons_array as $icon)
                            <option value="{{$icon}}">{{$icon}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection
