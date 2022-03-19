@extends('admin.main')
@section('content')

    <div class="container">

        <h1>Создание типа контента</h1>
        <form action="{{route('type-content.store')}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                <div class="col-md-6">
                    <input autofocus type="text" name="name" placeholder="Введите название" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="api_url" class="col-md-4 col-form-label text-md-right">API URL</label>
                <div class="col-md-6">
                    <input type="text" name="api_url" placeholder="Automatic..." id="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{ old('api_url') }}">
                    @error('api_url') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                <div class="col-md-6">
                    <textarea name="description" placeholder="Введите описание" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                <div class="col-md-6">
                    <input type="datetime-local" name="active_from" placeholder="Введите активен с" id="active_from" class="form-control @error('active_from') is-invalid @enderror" value="{{ old('active_from') }}">
                    @error('active_from') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                <div class="col-md-6">
                    <input type="datetime-local" name="active_after" placeholder="Введите активен по" id="active_after" class="form-control @error('active_after') is-invalid @enderror" value="{{ old('active_after') }}">
                    @error('active_after') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="icon" class="col-md-4 col-form-label text-md-right">Иконка</label>
                <div class="col-md-6">
                    <select id="icon" class="form-control @error('icon') is-invalid @enderror" name="icon">
                        @foreach($icons as $icon)
                            <option value='{{$icon->code}}'>&#x{{$icon->unicode}}; {{$icon->name}}</option>
                        @endforeach
                    </select>
                    @error('icon') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <style>
                select{
                    font-family: fontAwesome
                }
            </style>

            <button id="btn1" type="submit" class="btn btn-success">Создать</button>
        </form>
    </div>
@endsection


