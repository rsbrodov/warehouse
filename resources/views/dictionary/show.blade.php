<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container">
        <h1> {{ $dictionary->name }}</h1>
        <div class="jumbotron text-center">
            <h2>{{ $dictionary->name }}</h2>

            <strong>ID:</strong> {{ $dictionary->id }}<br>
            <strong>Имя:</strong> {{ $dictionary->name }}<br>
            <strong>Описание:</strong> {{ $dictionary->description }}<br>
            <strong>Код:</strong> {{ $dictionary->code }}<br>
            <strong>Архив:</strong>
            <div class="p-3 mb-2
            @if($dictionary->archive == 1)
                bg-warning text-white
            @elseif($dictionary->status == 0)
                bg-primary text-white
            @else
                bg-danger text-white
            @endif
                ">{{ $dictionary->archive?'Да':'Нет' }}</div>
            <strong>Содержит элементов:</strong> {{ \App\Models\DictionaryElement::where('dictionary_id', $dictionary->id)->count() }}<br>
            <strong>Кем создан:</strong> {{ \App\Models\User::where('id', $dictionary->created_author)->first()->name }}<br>
            <strong>Время создания:</strong> {{ $dictionary->created_at }}<br>
            <strong>Кем обновлен:</strong> {{ \App\Models\User::where('id', $dictionary->updated_author)->first()->name }}<br>
            <strong>Время обновления:</strong> {{ $dictionary->updated_at }}<br>

            <a href="{{route('dictionary.index')}}" class="btn btn-primary form-control">Вернуться</a>
        </div>
    </div>
@endsection
