@extends('admin.main')
@section('content')
    <?use Illuminate\Support\Facades\Auth;?>
    <div class="container">
        <h1>Редактирование типа контента {{$type_content->name}}</h1>
        <form action="{{route('type-content.update', [$type_content])}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Наименование</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Введите имя" value="{{$type_content->name}}">
                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="api_url" class="col-md-4 col-form-label text-md-right">API URL</label>
                    <div class="col-md-6">
                        <input type="text" name="api_url" placeholder="Automatic..." id="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{$type_content->api_url}}">
                        @error('api_url') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                    <div class="col-md-6">
                        <textarea name="description" placeholder="Введите описание" id="description" class="form-control @error('api_url') is-invalid @enderror">{{$type_content->description}}</textarea>
                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                    <div class="col-md-6">

                        <input type="text" name="active_from" id="active_from" class="form-control datepicker-here @error('api_url') is-invalid @enderror" value="{{date('Y-m-d\TH:i', strtotime($type_content->active_from))}}">

                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                    <div class="col-md-6">

                        <input type="text" name="active_after" id="active_after" class="form-control datepicker-here @error('api_url') is-invalid @enderror" value="{{date('Y-m-d\TH:i', strtotime($type_content->active_after))}}">

                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <?php $status_array = ['Draft', 'Published', 'Archive']; ?>
                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-md-right">Статус</label>
                    <div class="col-md-6">
                        <select id="status" type="text" class="form-control @error('api_url') is-invalid @enderror" name="status">
                            @foreach($status_array as $status)
                                <option @if($type_content->status === $status) selected
                                        @endif value="{{$status}}">{{$status}}</option>
                            @endforeach
                        </select>
                        @error('name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <input type="hidden" id="icon_selected" value="{{$type_content->icon}}">
                <div class="form-group row">
                    <label for="icon" class="col-md-4 col-form-label text-md-right">Иконка</label>
                    <div class="col-md-6">

                        <select id="icon" class="form-control @error('icon') is-invalid @enderror" name="icon">
                            @foreach($icons as $icon)
                                <option value='{{$icon->code}}'>&#x{{$icon->unicode}}; {{$icon->name}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="body" class="col-md-4 col-form-label text-md-right">Тело</label>
                    <div class="col-md-6">
                        <textarea name="body" placeholder="Введите..." id="body"
                                  class="form-control">{{$type_content->body}}</textarea>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Редактировать</button>
                    <a class="btn btn-primary ml-3" href="{{ route('type-content.create-new-version', [$type_content->id_global, 'major']) }}">Версия первого порядка</a>
                    <a class="btn btn-primary ml-3" href="{{ route('type-content.create-new-version', [$type_content->id_global, 'minor']) }}">Версия второго порядка</a>
                </div>

            </div>
            <style>
                select{
                    font-family: fontAwesome
                }
            </style>
        </form>
    </div>


@endsection
