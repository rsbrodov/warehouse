<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="container">
        <a href="{{route('dictionary.index')}}" class="btn btn-outline-info form-control">Вернуться</a>
        <div class="d-flex justify-content-center"><h1>Элементы справочника {{\App\Models\Dictionary::where('id', $dictionary_id)->first()->name}}</h1></div>
        <table class="table table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Значение</th>
                <th>Создатель</th>
                <th>Дата создания</th>
                <th>Последний редактор</th>
                <th>Дата редактирования</th>
            </tr>
            @foreach($dictionary_elements as $dictionary_element)
                <tr>
                    <td>{{$dictionary_element->id}}</td>
                    <td>{{$dictionary_element->value}}</td>
                    <td>{{User::where('id', $dictionary_element->created_author)->first()->name}}</td>
                    <td>{{$dictionary_element->created_at}}</td>
                    <td>{{User::where('id', $dictionary_element->updated_author)->first()->name}}</td>
                    <td>{{$dictionary_element->updated_at}}</td>
                    <td>
                        <a href="{{route('dictionary_element.show', [$dictionary_element->id])}}"
                           class="btn btn-success ">
                            <img src="{{asset('images/show.png')}}" width="32" height="32" alt="">
                        </a>
                        <a href="{{route('dictionary_element.edit', $dictionary_element->id)}}"
                           class="btn btn-primary ">
                            <img src="{{asset('images/edit.png')}}" width="32" height="32" alt="">
                        </a>
                        <form action="{{ route('dictionary_element.destroy', $dictionary_element->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger"><img src="{{asset('images/delete.png')}}" width="32" height="32" alt=""></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('dictionary_element.create', $dictionary_id)}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
