<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
        <!-- <FlashMessage :position="'right bottom'"></FlashMessage> -->
        <!-- Modal Dictionary create -->
        <div class="modal fade" id="ElementContentCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create @close-modal="closeModal('ElementContentCreate', 1)"></Create>
            </div>
        </div>

        <!-- Modal Dictionary edit -->
        <!-- <div class="modal fade" id="dictionaryEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    :dictionary_id="dictionary.id"
                    :dictionary_name="dictionary.name"
                    :dictionary_code="dictionary.code"
                    :dictionary_description="dictionary.description"
                    @close-modal="closeModal('dictionaryEdit')"
                />
            </div>
        </div> -->
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-form col-6">
                    <div class="form">
                        <form action="" method="post">
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="label" placeholder="Заголовок"
                                        id="code" class="form-control" v-model="filter_form.label">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="url"
                                        placeholder="URL контента" id="name" class="form-control"
                                        v-model="filter_form.url">
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
                    <button id="hideshow" class="btn btn-outline-primary btn-unbordered" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                    <button id="clean" class="btn btn-outline-primary btn-unbordered" style="display: none;"  @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-outline-primary btn-unbordered" @click="openModal('ElementContentCreate')"><span class="fa fa-plus-circle fa-lg"></span></button>
                    <!-- <a href="{{route('element-content.create', request()->route('type_content_id'))}}" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg"></span></a> -->
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>Заголовок</th>
                <th>URL контента</th>
                <th>Статус</th>
                <th>Период действия</th>
                <th>Автор последнего редактирования</th>
                <th>Дата последнего редактирования</th>
            </tr>
            <tr v-if="ElementContent.length === 0 && getLoading === false">
                <td class="text-center text-danger" colspan="6"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(element, index) in ElementContent" :key="index">
                <td>{{element.label}}</td>
                <td>{{element.url}}</td>
                <td :class="element.status | statusColor"><b>{{ element.status | status }}</b></td>
                <td>{{ element | date }}</td>
                <td>{{element.updated_authors.name}}</td>
                <td>{{ element | dateUpdated }}</td>
                <!-- :href нужно убрать когда будут добавлены компоненты -->
                <td nowrap>
                    <a :href="'/element-content/'+ element.id +'/edit'" class="btn btn-outline-primary btn-unbordered" @click="openModal('typeContentEdit', type_content)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/all-version-element-content/'+element.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
    </div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    import Create from './create'
    export default{
        components:{Loader, moment, Create},
        data:function(){
            return {
                id: window.location.href.split('/').slice(-1)[0],
                filter_form:{
                    label:null,
                    url:null,
                    status:null,
                    active_from:null,
                    active_after:null,
                },
            }
        },

        computed:{
            ...mapGetters(['ElementContent']),
        },

        methods: {
            ...mapActions(['getElementContent', 'getLoading']),
            closeModal(id){
                if(id == 'ElementContentCreate'){
                    $("#ElementContentCreate").modal("hide");
                }

            },
            openModal(id){
                if(id == 'ElementContentCreate') {
                    $('#ElementContentCreate').modal('show');
                }
            },
            toggleSearch(){
                $('.form').toggle('show');
                $('#clean').toggle('show');
                $('#hideshow').toggleClass('btn-primary btn-secondary');
                if( $('#hideshow').hasClass('btn-primary')){
                    $('#hideshow').html('<span class="fa fa-search fa-lg"></span>');
                } else if( $('#hideshow').hasClass('btn-secondary')){
                    $('#hideshow').html('Свернуть');
                }
            },
            cleanSearch(){
                $( 'form' ).each(function(){
                    this.reset();
                });
            }
        },

        filters: {
            status: function (status) {
                let status_array = {Draft: 'Черновик', Published: 'Опубликовано', Archive:'В архиве'};
                if(status){
                    return status_array[status];
                }else{
                    return status_array['Draft'];
                }
            },
            statusColor: function (status) {
                let status_array = {Draft: 'text-dark', Published: 'text-success', Archive:'text-warning'};
                if(status){
                    return status_array[status];
                }else{
                    return status_array['Draft'];
                }
            },
            date: function (element) {
                if(!element.active_from && !element.active_after){
                    return "Не задан";
                }else if(!element.active_from && element.active_after){
                    return "До "+ moment(type_content.active_after).format('DD.MM.YYYY');
                } else if(element.active_from && !element.active_after){
                    return moment(element.active_from).format('DD.MM.YYYY') + " - бессрочно";
                }else{
                    return moment(element.active_from).format('DD.MM.YYYY') + " - " + moment(element.active_after).format('DD.MM.YYYY');
                }
            },
            dateUpdated: function (element) {
                return moment(element.updated_at).format('DD.MM.YYYY HH:II');
            },
        },

        async created(){
            this.getElementContent(this.id);
        },

        async mounted(){
            $('.form').hide();
        }
    }
</script>

