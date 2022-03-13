<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container">
        <h1> {{ $dictionary_element->name }}</h1>
        <div class="jumbotron text-center">
            <h2>{{ $dictionary_element->name }}</h2>

            <strong>ID:</strong> {{ $dictionary_element->id }}<br>
            <strong>Имя:</strong> {{ $dictionary_element->value }}<br>
            <strong>Родитель:</strong> {{ $dictionary_element->dictionary_id }}<br>
            <strong>Кем создан:</strong> {{ User::where('id', $dictionary_element->created_author)->first()->name }}<br>
            <strong>Время создания:</strong> {{ $dictionary_element->created_at }}<br>
            <strong>Кем обновлен:</strong> {{ User::where('id', $dictionary_element->updated_author)->first()->name }}<br>
            <strong>Время обновления:</strong> {{ $dictionary_element->updated_at }}<br>

            <a href="{{route('dictionary-element.index', $dictionary_element->dictionary_id)}}" class="btn btn-primary form-control">Вернуться</a>
        </div>
    </div>
@endsection
