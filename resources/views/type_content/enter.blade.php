@extends('admin.main')
@section('content')
    <div class="text-center"><h3>Парсим по id: {{$type_content['id']}}</h3></div>
    <?php $body = json_decode($type_content['body']);
    //    dd($body);
    ?>
<!--    --><?php //dd($body);exit; ?>
    @foreach($rows as $row)
        <?print_r($row);?>
{{--        @foreach($rowList as $columns)--}}
<!--            --><?php //print_r($columns); ?>
{{--            @foreach($columns as $column)--}}

                {{--                    @foreach($columns as $fields)--}}
                {{--                        --}}
                {{--                    @endforeach--}}
{{--            @endforeach--}}
{{--        @endforeach--}}
{{--    @endforeach--}}
{{--    <label for="{{$name}}" class="col-md-4 col-form-label text-md-right">{{$label}}</label>--}}
{{--    <div class="col-md-6">--}}
{{--        <input type="{{$input_type}}" class="form-control @error($name) is-invalid @enderror" id="{{$name}}"--}}
{{--               name="{{$name}}" placeholder="{{$placeholder}}" value="old({{$name}})">--}}
{{--        @error($name) <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror--}}
{{--    </div>--}}
@endsection
