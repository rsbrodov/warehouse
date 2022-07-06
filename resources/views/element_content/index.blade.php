<? use \App\Models\User; ?>
@extends('admin.main')
@section('content')
    <div class="">


        <div class="text-center"><h3>Элементы контента</h3></div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-form col-6">
                    <div class="form">
                        <form>
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="label" placeholder="Наименование элемента" id="label" class="form-control" v-model="filter_form.label">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="status" v-model="filter_form.status">
                                        <option value=''>Все</option>
                                        <option value='Draft'>Черновик</option>
                                        <option value='Published'>Опубликовано</option>
                                        <option value='Archive'>В архиве</option>
                                        <option value='Destroy'>На удаление</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select id="owner" class="form-control" name="owner">
                                        <option value='0'>Владелец</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="active_from" v-model="filter_form.active_from" placeholder="Период действия с" id="active_from" class="form-control datepicker-here">
                                </div>
                                <div class="col-4">
                                    <input  autocomplete="off" type="text" name="active_after" v-model="filter_form.active_after" placeholder="Период действия по" id="active_after" class="form-control datepicker-here">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-6 text-right">
                    <button id="search-btn" class="btn btn-primary" @click="toggleSearch()"><span class="fa fa-search fa-lg"></span></button>
                    <button class="btn btn-primary" id="clear-btn" style="display: none;" @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg"></span> Очистить</button>
                    <!-- Button trigger modal -->
{{--                    <button type="button" class="btn-create btn btn-primary" @click="openModal()"><span class="fa fa-plus-circle fa-lg"></span></button>--}}
                    <a href="{{route('element-content.create', request()->route('type_content_id'))}}" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg"></span></a>
                </div>
            </div>
        </div>


        <table class="table table-bordered mt-2 table-hover">
            <tr>
                <th>Заголовок</th>
                <th>Версия</th>
                <th>Статус</th>
                <th>Создал</th>
                <th>Автор последнего редактирования</th>
                <th>Дата последнего редактирования</th>
                <th>Действия</th>
            </tr>
            @forelse($element_contents as $element_content)
                <tr>
                    <td>{{$element_content->label}}</td>
                    <td>{{$element_content->version_major}}.{{$element_content->version_minor}}</td>
                    @if($element_content->status == 'Draft')
                        <td class="text-dark">Черновик</td>
                    @elseif($element_content->status == 'Published')
                        <td class="text-success">Опубликовано</td>
                    @elseif($element_content->status == 'Archive')
                        <td class="text-warning">В архиве</td>
                    @elseif($element_content->status == 'Destroy')
                        <td class="text-danger">На удаление</td>
                    @endif
                    <td>@if(!empty($element_content->created_authors)) {{$element_content->created_authors->name}}@endif </td>
                    <td>@if(!empty($element_content->updated_authors)) {{$element_content->updated_authors->name}}@endif </td>
                    <td>{{$element_content->updated_at}}</td>
                    <td style="display: flex">
                        <div class="col-4">
                            <a href="{{route('element-content.edit', $element_content->id)}}" class="btn btn-primary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
                        </div>
                        <div class="col-4">
                            <a href="{{route('element-content.get-all-version', $element_content->id_global)}}" class="btn btn-primary"><i class="fa fa-cubes fa-lg" aria-hidden="true"></i></a>
                        </div>
                        <div class="col-4">
                            @if($element_content->status == 'Draft' or $element_content->status == 'Archive')
                                <a href="{{route('element-content.destroy', $element_content->id)}}" class="btn btn-danger"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a>
                            @elseif($element_content->status == 'Destroy')
                                <a href="{{route('element-content.destroy', $element_content->id)}}" class="btn btn-success"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">Нет данных</td></tr>
            @endforelse
        </table>

    </div>
@endsection
