@extends('admin.main')
@section('content')
<div class="container-fluid">
    <!-- Stack the columns on mobile by making one full-width and the other half-width -->
    <div class="row">
        <div class="col"><b><h2>{{$element_content->label}}</h2></b></div>
        <div class="col"></div>
    </div>
    <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
    <div class="row">
        <div class="col-9">
            <div class="flex-cont">
                <div class="flex-elem"><b>API URL: </b>{{$element_content->url}}</div>
                <div class="flex-elem"><b>Статус: </b>{{$element_content->status}}</div>
                <div class="flex-elem"><b>Версия: </b>{{$element_content->version_major}}.{{$element_content->version_minor}}</div>
                <div class="flex-elem">
                    <b>Период действия: </b>
                </div>
                
            </div>
        </div>
        <div class="col-3 text-right"><a href="#" class="btn btn-outline-secondary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></div>
    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-structure-tab" data-bs-toggle="tab" data-bs-target="#nav-structure" type="button" role="tab" aria-controls="nav-structure" aria-selected="true">Состав полей</button>
            <button class="nav-link" id="nav-access-tab" data-bs-toggle="tab" data-bs-target="#nav-access" type="button" role="tab" aria-controls="nav-access" aria-selected="false">Доступ</button>
            <button class="nav-link" id="nav-story-tab" data-bs-toggle="tab" data-bs-target="#nav-story" type="button" role="tab" aria-controls="nav-story" aria-selected="false">История изменений</button>
        </div>
    </nav>


    <div class="row ">
        <div class="col-9 left-block">
            <form action="" method="post">
                <div class="form-group container">
                    @foreach ($body as $row)
                        @foreach ($row as $column)
                                <div class="block mt-4">
                                    <label for="{{$column->name}}" class="">{{$column->title}}</label>
                                    @if($column->type === 'text')
                                        <input autocomplete="off" type="{{$column->type}}" class="form-control @error($column->name) is-invalid @enderror" id="{{$column->name}}" name="{{$column->name}}">
                                    @elseif($column->type === 'checkbox' or $column->type === 'radio')
                                    <div class="row">
                                        @foreach($dictionary_elems = \App\Models\DictionaryElement::where('dictionary_id', $column->dictionary_id)->get() as $dictionary_elem)
                                            <div class="col-3">
                                                <input type="{{$column->type}}" id="{{$column->name}}" name="{{$column->name}}" value="{{$dictionary_elem->id}}">
                                                <label for="{{$dictionary_elem->value}}">{{$dictionary_elem->value}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @elseif($column->type === 'textarea')
                                        <textarea name="{{$column->type}}" id="{{$column->type}}" class="form-control"></textarea>
                                    @elseif($column->type === 'select')
                                        <select id="{{$column->name}}" class="form-control" name="{{$column->name}}">
                                            @foreach($dictionary_elems = \App\Models\DictionaryElement::where('dictionary_id', $column->dictionary_id)->get() as $dictionary_elem)
                                                <option value="{{$dictionary_elem->id}}">{{$dictionary_elem->value}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error($column->name) <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                </div>
                        @endforeach
                    @endforeach
                    {{-- <button type="submit" class="btn btn-success form-control mt-4">Сохранить</button> --}}
                </div>
            </form>
        </div>
        <div class="col-3">
            <div class="d-flex flex-column">
                <div class="p-2"><button type="submit" class="btn btn-primary form-control text-left"><i
                    class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить черновик</button></div>
                <div class="p-2"><a href="" class="btn btn-primary form-control text-left"><i
                    class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация контента</a></div>
                <div class="p-2"><a href="" class="btn btn-primary form-control text-left">
                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить контент</a></div>
            </div>
        </div>
    </div>

    <style>
        .flex-cont{
            display:inline-flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            width:66.66%;
        }
        .flex-elem{
            margin: 5px
        }
    </style>
@endsection
