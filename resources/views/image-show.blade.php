@extends('admin.main')
@section('content')
    <div class="container">
        <h1>Загрузка новых фотографий</h1>
        <div class="row justify-content-center">
            <form action="{{route('image.upload')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input class="form-control" type="file" name="image">
                <button class="form-control btn-outline-success" type="submit">Загрузка</button>
            </form>
            @isset($path)
                <img class="img-fluid" src="{{asset('/storage/' . $path)}}">
            @endisset

            @isset($images)
                Загруженные фотографии: <br>
            @foreach($images as $image)
                <?php print_r($image); ?>
                <img class="img-fluid" src="{{asset($image)}}">
            @endforeach
            @endisset
        </div>
    </div>

@endsection
