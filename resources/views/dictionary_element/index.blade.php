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
                        <a href="{{route('dictionary-element.show', [$dictionary_element->id])}}"
                           class="btn btn-success ">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary-element.edit', $dictionary_element->id)}}"
                           class="btn btn-primary ">
                            <i class="fa fa-edit fa-lg" aria-hidden="true"></i>
                        </a>
                        <form action="{{ route('dictionary-element.destroy', $dictionary_element->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('dictionary-element.create', $dictionary_id)}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection