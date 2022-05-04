@extends('admin.main')
@section('content')
    <div class="container" xmlns:color="http://www.w3.org/1999/xhtml">
        <div class="row justify-content-center">
            <h1><span style="color:red"> {{$str}}</span></h1>
            <a class="btn btn-success" href="{{ route('home-test') }}">Обновить</a>
        </div>
    </div>

@endsection
