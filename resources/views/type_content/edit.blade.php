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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               name="name" placeholder="Введите имя" value="{{$type_content->name}}">
                        @error('name') <span class="invalid-feedback"
                                             role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="api_url" class="col-md-4 col-form-label text-md-right">API URL</label>
                    <div class="col-md-6">
                        <input type="text" name="api_url" placeholder="Automatic..." id="api_url"
                               class="form-control @error('api_url') is-invalid @enderror"
                               value="{{$type_content->api_url}}">
                        @error('api_url') <span class="invalid-feedback"
                                                role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                    <div class="col-md-6">
                        <textarea name="description" placeholder="Введите описание" id="description"
                                  class="form-control @error('description') is-invalid @enderror">{{$type_content->description}}</textarea>
                        @error('description') <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                    <div class="col-md-6">

                        <input type="text" name="active_from" id="active_from"
                               class="form-control datepicker-here @error('active_from') is-invalid @enderror"
                               value="{{date('d.m.Y', strtotime($type_content->active_from))}}">

                        @error('active_from') <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                    <div class="col-md-6">

                        <input type="text" name="active_after" id="active_after"
                               class="form-control datepicker-here @error('active_after') is-invalid @enderror"
                               value="{{date('d.m.Y', strtotime($type_content->active_after))}}">

                        @error('active_after') <span class="invalid-feedback"
                                                     role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                @if($type_content->status === 'Draft')
                    <?php $status_array = ['Draft', 'Published'];?>
                @elseif($type_content->status === 'Published' or $type_content->status === 'Archive')
                    <?php $status_array = ['Published', 'Archive'];?>
                @endif
                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-md-right">Статус</label>
                    <div class="col-md-6">
                        <select id="status" type="text" class="form-control @error('status') is-invalid @enderror"
                                name="status">
                            @foreach($status_array as $status)
                                <option @if($type_content->status === $status) selected
                                        @endif value="{{$status}}">{{$status}}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="invalid-feedback"
                                               role="alert"><strong>{{ $message }}</strong></span>@enderror
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
                    @if($type_content->status !== 'Draft')
                        <a class="btn btn-primary ml-3" href="{{ route('type-content.create-new-version', [$type_content->id_global, 'major']) }}">Версия первого порядка</a>
                        <a class="btn btn-primary ml-3" href="{{ route('type-content.create-new-version', [$type_content->id_global, 'minor']) }}">Версия второго порядка</a>
                    @endif
                </div>

            </div>
            <style>
                select {
                    font-family: fontAwesome
                }
            </style>
        </form>
    </div>
    <script>
        function urlLit(w, v) {
            let tr = 'a b v g d e ["zh","j"] z i y k l m n o p r s t u f h c ch sh ["shh","shch"] ~ y ~ e yu ya ~ ["jo","e"]'.split(' ');
            let ww = '';
            w = w.toLowerCase();
            for (i = 0; i < w.length; ++i) {
                cc = w.charCodeAt(i);
                ch = (cc >= 1072 ? tr[cc - 1072] : w[i]);
                if (ch.length < 3) ww += ch; else ww += eval(ch)[v];
            }
            return (ww.replace(/[^a-zA-Z0-9\-]/g, '-').replace(/[-]{2,}/gim, '-').replace(/^\-+/g, '').replace(/\-+$/g, ''));
        }

        $(document).ready(function () {
            $('#name').bind('change keyup input click', function () {
                $('#api_url').val(urlLit($('#name').val(), 0))
            });
        });
    </script>

@endsection
