<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div id="app">
        <Indexdictionaryelement
        :id="'{{$id}}'"
        />
    </div>
    <script src="{{mix('js/app.js')}}"></script>
@endsection
