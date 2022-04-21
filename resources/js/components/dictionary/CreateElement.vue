<template>
    <div>
<!--        <?use \App\Models\User;?>-->
<!--        @extends('admin.main')-->
<!--        @section('content')-->
            <h1>Создание элемента справочника
<!--                {{\App\Models\Dictionary::find($dictionary_id)->name}}-->
            </h1>
            <form action="" method="post"> <!--<form action="{{route('dictionary-element.store', $dictionary_id)}}" method="post">-->
<!--                @csrf-->
                <div class="form-group row">
                    <label for="value" class="col-md-4 col-form-label text-md-right">Значение</label>
                    <div class="col-md-6">
                        <input autofocus type="text" name="value" placeholder="Введите значение" id="value" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Создать</button>
            </form>
<!--        @endsection-->

    </div>
</template>

<script>
    export default {
        name: "CreateElement"
    }
</script>

<style scoped>

</style>
