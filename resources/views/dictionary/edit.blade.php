@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование справочника {{$edit_dictionary->name}}</h1>
        <form action="{{route('dictionary.update', [$edit_dictionary])}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Наименование</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Введите имя"
                       value="{{ old('name', $edit_dictionary->name) }}">
                @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <label for="description">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                          placeholder="Введите описание">{{ old('description',$edit_dictionary->description) }}</textarea>
                @error('description')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <label for="code">Код</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Введите код"
                       value="{{ old('code',$edit_dictionary->code) }}">
                @error('code')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <label for="archive">Архив</label>
                <select id="archive" type="text" class="form-control" name="archive">
                    <option {{ old('archive', $edit_dictionary->archive) ? '' :'selected' }} value="0">Нет</option>
                    <option {{ old('archive', $edit_dictionary->archive) ? 'selected' : '' }} value="1">Да</option>
                </select>
                @error('archive')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                <br>
                <button type="submit" class="btn btn-success">Редактировать</button>
            </div>
        </form>
    </div>
@endsection
