@extends('admin.main')
@section('content')

    <div class="container">
        <h1>Создание элемента контента</h1>
        <form action="{{route('element-content.store', request()->route('type_content_id'))}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                <div class="col-md-6">
                    <input autofocus type="text" name="label" placeholder="Введите название" id="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}">
                    @error('label') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="url" class="col-md-4 col-form-label text-md-right">URL</label>
                <div class="col-md-6">
                    <input type="text" name="url" placeholder="Automatic..." id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}">
                    @error('url') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="active_from" class="col-md-4 col-form-label text-md-right">Активен с...</label>
                <div class="col-md-6">
                    <input type="text" name="active_from" placeholder="Введите активен с" id="active_from" class="form-control datepicker-here @error('active_from') is-invalid @enderror" value="{{ old('active_from') }}">
                    @error('active_from') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="active_after" class="col-md-4 col-form-label text-md-right">Активен по...</label>
                <div class="col-md-6">
                    <input type="test" name="active_after" placeholder="Введите активен по" id="active_after" class="form-control datepicker-here @error('active_after') is-invalid @enderror" value="{{ old('active_after') }}">
                    @error('active_after') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <button id="btn1" type="submit" class="btn btn-success">Создать</button>
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


