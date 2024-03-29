<?use \App\Models\User;?>
@extends('admin.main')
@section('content')
<div class="mt-5 ml-2 col-6">
    <form action="{{route('clients.store')}}" method="post">
        @csrf
        <label for="name"><b class="text-danger">*</b><b>Название</b></label>
        <input autocomplete="off" id="name" name="name" placeholder="Введите название компании" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="inn"><b class="text-danger">*</b><b>ИНН</b></label>
        <input autocomplete="off" id="inn" name="inn" placeholder="Введите ИНН компании" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="host"><b class="text-danger">*</b><b>Хост</b></label>
        <input autocomplete="off" id="host" name="host" placeholder="***.rider-cms" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="email" class="mt-3"><b class="text-danger mt-3">*</b><b>Email</b></label>
        <input autocomplete="off" id="email" name="email" placeholder="Введите email" class="form-control" type="text" @error('email') is-invalid @enderror value="{{ old('email') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="number"><b class="text-danger">*</b><b>Номер и дата договора</b></label>
        <input autocomplete="off" id="number" name="number" placeholder="№11111 от 01.01.2000" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}">
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

        <label for="description"><b class="text-danger">*</b><b>Описание</b></label>
        <textarea autocomplete="off" id="description" name="description" class="form-control" type="text" @error('name') is-invalid @enderror value="{{ old('name') }}"></textarea>
        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror


        <button type="submit" class="btn btn-primary mt-3" style="width: 100%">Создать</button>

    </form>
</div>
@endsection
