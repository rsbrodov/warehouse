<template>
    <div>
<!--        <?use \App\Models\User;?>-->
<!--        @extends('admin.main')-->
<!--        @section('content')-->
            <h1>Создание справочника</h1>
            <form action="" method="post"> <!-- <form action="{{route('dictionary.store')}}" method="post">-->
<!--                @csrf-->
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                    <div class="col-md-6">
                        <input autofocus type="text" name="name" value="" placeholder="Введите название" id="name" class="form-control"> <!-- <input autofocus type="text" name="name" value="{{ old('name') }}" placeholder="Введите название" id="name" class="form-control @error('name') is-invalid @enderror">-->
<!--                        @error('name')-->
                        <span class="invalid-feedback" role="alert"><strong>
<!--                            {{ $message }}-->
                        </strong></span>
<!--                        @enderror-->
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right" >Описание</label>
                    <div class="col-md-6">
                        <textarea name="description" placeholder="Введите описание" id="description" class="form-control"></textarea> <!-- <textarea name="description" placeholder="Введите описание" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>-->
<!--                        @error('description')-->
                        <span class="invalid-feedback" role="alert"><strong> </strong></span> <!-- <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
<!--                        @enderror-->
                    </div>
                </div>
                <div class="form-group row">
                    <label for="code" class="col-md-4 col-form-label text-md-right">Код</label>
                    <div class="col-md-6">
                        <input type="text" name="code" value="" placeholder="Введите код" id="code" class="form-control"><!-- <input type="text" name="code" value="{{ old('code') }}" placeholder="Введите код" id="code" class="form-control @error('code') is-invalid @enderror">-->
<!--                        @error('code')-->
                        <span class="invalid-feedback" role="alert"><strong></strong></span> <!-- <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
<!--                        @enderror-->
                    </div>
                </div>
                <div class="form-group row">
                    <label for="archive" class="col-md-4 col-form-label text-md-right">Архив</label>
                    <div class="col-md-6">
                        <select id="archive" type="text" class="form-control" name="archive"> <!-- <select id="archive" type="text" class="form-control @error('archive') is-invalid @enderror" name="archive">-->
                            <option value="0">Нет</option> <!-- <option {{ old('archive')== '0' ? 'selected' : '' }} value="0">Нет</option>-->
                            <option value="1">Да</option> <!-- <option {{ old('archive')== '1' ? 'selected' : '' }} value="1">Да</option>-->
                        </select>
<!--                        @error('archive')-->
                        <span class="invalid-feedback" role="alert"><strong> </strong></span> <!-- <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
<!--                        @enderror-->
                    </div>
                </div>
                <button type="submit" class="btn btn-success ">Создать</button>
            </form>
<!--        @endsection-->
    </div>
</template>

<script>
    export default {
        name: "Create"
    }
</script>

<style scoped>

</style>
