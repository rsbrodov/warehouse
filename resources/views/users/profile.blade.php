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
@extends('admin.main')
@section('content')
    <div class="container" xmlns:white-space="http://www.w3.org/1999/xhtml">
        <a class="btn btn-info" href="{{ route('home') }}"> Домой</a>  
            <div class="row">
                <div class="col-3">
                    {{-- @if($user->photo)
                    <img class="img-fluid" src="{{asset('/storage/' . $user->photo)}}">
                    @else --}}
                    <img class="img-fluid" src="{{asset('/storage/profile.png')}}">
                    {{-- @endif --}}
                </div>
                <div class="col-9">
                    <form action="{{route('users.profile-update', [$user->id])}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" placeholder="Введите имя" value="{{$user->name}}">
                                    @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                </div>
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Введите email" value="{{$user->email}}">
                                    @error('email') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                        <button type="submit" class="btn btn-success">Редактировать</button>
                    </form>
                </div>
            </div>
                
                 <div class="flex-cont">
            <div class="flex-elem"><b> </div>
            <div class="flex-elem"><b>
                
            </div>
        </div>
    </div>
@endsection
