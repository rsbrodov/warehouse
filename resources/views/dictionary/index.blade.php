
@extends('admin.main')
@section('content')

    <div class="">
        <div class="d-flex justify-content-center"><h1>Справочники</h1></div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-button col-2">
                    <button id="hideshow" class="btn btn-primary"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                </div>
                <div class="search-form col-8">
                    <div class="form">
                        <form action="{{route('dictionary.store')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Код справочника" id="code" class="form-control">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование справочника" id="name" class="form-control">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="archive">
                                        <option value='2'>Все</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-2">
                    <button id="clean" class="btn btn-primary"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <a href="{{route('type-content.create')}}" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg" aria-hidden="true"></span></a>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover">
            <tr>
                <th>Код справочника</th>
                <th>Наименование справочника</th>
                <th>Описание справочника</th>
                <th>Последнее изменение</th>
                <th>Статус</th>
            </tr>
            @foreach($dictionaries as $dictionary)
                <tr>
                    <td>{{$dictionary->code}}</td>
                    <td>{{$dictionary->name}}</td>
                    <td>{{$dictionary->description}}</td>
                    <?php $up_at = date_create($dictionary->updated_at); ?>
                    <td>{{date_format($up_at, 'd.m.Y H:m')}}</td>
                    @if($dictionary->archive)
                        <td class="text-danger">Архивный</td>
                    @else
                        <td class="text-success">Действующий</td>
                   @endif
                    <td nowrap>
                        <a href="{{route('dictionary.show', ($dictionary->id))}}"
                           class="btn btn-success ">
                            <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary.edit', ($dictionary->id))}}" class="btn btn-primary">
                            <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary-element.index', $dictionary->id)}}" class="btn btn-success">
                            <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="{{route('dictionary.archive', $dictionary->id)}}" class="btn btn-secondary">
                            @if(\App\Models\Dictionary::find($dictionary->id)->archive == '1')
                                <i class="fa fa-history fa-lg" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-file-archive-o fa-lg" aria-hidden="true"></i>
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <a href="{{route('dictionary.create')}}" class="btn btn-primary form-control">Создать</a>
    </div>
@endsection
