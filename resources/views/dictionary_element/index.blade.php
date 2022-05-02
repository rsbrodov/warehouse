<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div id="app">
        <Indexdictionaryelement/>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
@endsection
