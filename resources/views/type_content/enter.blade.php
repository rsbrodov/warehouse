@extends('admin.main')
@section('content')
    <div class="text-center"><h3>Парсим по id</h3></div>
    <form action="" method="post">
    @foreach ($rows as $row)
        @foreach ($row['col'] as $column)
            @foreach ($column['element'] as $field)
                <div class="form-group row">
                    <label for="{{$field['name']}}" class="col-md-4 col-form-label text-md-right">{{$field['title']}}</label>

                    <div class="col-md-6">
                        @if($field['type'] === 'text')
                        <input type="{{$field['type']}}" class="form-control @error($field['name']) is-invalid @enderror" id="{{$field['name']}}" name="{{$field['name']}}">
                        @elseif($field['type'] === 'checkbox')
                            @foreach($field['parameters'] as $params)
                                <div>
                                    <input type="checkbox" id="{{$params['name']}}" name="{{$params['name']}}" value="{{$params['label']}}">
                                    <label for="{{$params['name']}}">{{$params['label']}}</label>
                                </div>
                            @endforeach
                        @elseif($field['type'] === 'textarea')
                            <textarea name="{{$field['name']}}" id="{{$field['name']}}" class="form-control"></textarea>
                        @elseif($field['type'] === 'select')
                            <select id="role" type="text" class="form-control" name="role">
                                @foreach($field['parameters'] as $params)
                                    <?php //print_r($params); ?>
                                    <option value="{{$params['name']}}">{{$params['label']}}</option>
                                @endforeach
                            </select>
                            <?php //exit; ?>
                        @endif
                        @error($field['name']) <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
            @endforeach
        @endforeach
    @endforeach
            <button type="submit" class="btn btn-success form-control ">Сохранить</button>
    </form>
@endsection
