@extends('admin.main')
@section('content')
    <div class="text-center"><h3>Парсим по id</h3></div>
    <form action="" method="post">
        <div class="form-group container">
            @foreach ($rows as $row)
                @foreach ($row['col'] as $column)
                    @foreach ($column['element'] as $field)
                        <div class="block mt-4">
                            <label for="{{$field['name']}}" class="">{{$field['title']}}</label>
                            @if($field['type'] === 'text')
                                <input autocomplete="off" type="{{$field['type']}}" class="form-control @error($field['name']) is-invalid @enderror" id="{{$field['name']}}" name="{{$field['name']}}">
                            @elseif($field['type'] === 'checkbox' or $field['type'] === 'radio')
{{--                                @foreach($field['parameters'] as $params)--}}
{{--                                    <div>--}}
{{--                                        <input type="{{$field['type']}}" id="{{$field['name']}}" name="{{$field['name']}}" value="{{$params['value']}}">--}}
{{--                                        <label for="{{$params['value']}}">{{$params['label']}}</label>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
                                @foreach($dictionary_elems = \App\Models\DictionaryElement::where('dictionary_id', $field['parameters'])->get() as $dictionary_elem)
                                    <div>
                                        <input type="{{$field['type']}}" id="{{$field['name']}}" name="{{$field['name']}}" value="{{$dictionary_elem['id']}}">
                                        <label for="{{$dictionary_elem['value']}}">{{$dictionary_elem['value']}}</label>
                                    </div>
                                @endforeach
                            @elseif($field['type'] === 'textarea')
                                <textarea name="{{$field['name']}}" id="{{$field['name']}}" class="form-control"></textarea>
                            @elseif($field['type'] === 'select')
{{--                                <select id="{{$field['name']}}" type="text" class="form-control" name="{{$field['name']}}">--}}
{{--                                    @foreach($field['parameters'] as $params)--}}
{{--                                        <option value="{{$params['value']}}">{{$params['label']}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                                <select id="{{$field['name']}}" class="form-control" name="{{$field['name']}}">
                                    @foreach($dictionary_elems = \App\Models\DictionaryElement::where('dictionary_id', $field['parameters'])->get() as $dictionary_elem)
                                        <option value="{{$dictionary_elem['id']}}">{{$dictionary_elem['value']}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error($field['name']) <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                    @endforeach
                @endforeach
            @endforeach
            <button type="submit" class="btn btn-success form-control mt-4">Сохранить</button>
        </div>
    </form>
@endsection
