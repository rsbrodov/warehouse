<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
         <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal Dictionary create -->
        <div class="modal fade" id="ElementContentCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create @close-modal="closeModal('ElementContentCreate', 1)"></Create>
            </div>
        </div>

        <!-- Modal edit -->
        <div class="modal fade" id="elementContentEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    v-model="element_content"
                    :type_content_id="id"
                    @close-modal="closeModal('elementContentEdit')"
                />
            </div>
        </div>
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
                                        <option value='Draft,Published,Archive,Destroy'>Все</option>
                                        <option value='Draft'>Черновик</option>
                                        <option value='Published'>Опубликовано</option>
                                        <option value='Archive'>В архиве</option>
                                        <option value='Destroy'>На удаление</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="date" name="active_from" v-model="filter_form.active_from" placeholder="Период действия с" id="active_from" class="form-control">
                                </div>
                                <div class="col-4">
                                    <input  autocomplete="off" type="date" name="active_after" v-model="filter_form.active_after" placeholder="Период действия по" id="active_after" class="form-control">
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
            <tr v-if="(!ElementContent || ElementContent.length === 0) && getLoading === false">
                <td class="text-center text-danger" colspan="6"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(element, index) in ElementContent" :key="index">
                <td>{{element.label}}</td>
                <td><p style="display:flex; justify-content: space-between; margin:0; padding:0">{{element.apiUrl}} <button class="btn btn-outline-primary btn-unbordered" @click="copyUrl(element)"><span class="fa fa-files-o fa-lg" aria-hidden="true"></span></button></p></td>
                <td :class="element.status | statusColor"><b>{{ element.status | status }}</b></td>
                <td>{{ element | date }}</td>
                <td>{{element.updatedAuthor.name}}</td>
                <td>{{ element | dateUpdated }}</td>
                <!-- :href нужно убрать когда будут добавлены компоненты -->
                <td nowrap>
<!--                    <a :href="'/element-content/'+ element.id +'/edit'" class="btn btn-outline-primary btn-unbordered" @click="openModal('typeContentEdit', type_content)"><i class="fa fa-pencil fa-lg"></i></a>-->
<!--                    <a :href="'/element-content/enter/'+element.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>-->
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('elementContentEdit', element)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/element/enter-vue/'+element.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
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
    import Edit from "./Edit";
    export default{
        components:{Loader, moment, Create, Edit},
        data:function(){
            return {
                id: window.location.href.split('/').slice(-1)[0],
                filter_form:{
                    label:null,
                    api_url:null,
                    status:null,
                    active_from:null,
                    active_after:null,
                },
                element_content:{
                    id:null,
                    apiUrl:null,
                    label:null,
                    status:null,
                    activeAfter:null,
                    activeFrom:null,
                    description:null
                },
                params:{
                    label:null,
                    api_url:null,
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
                $("#"+id).modal("hide");

            },
            openModal(id, element_content){
                if(id == 'ElementContentCreate') {
                    $('#ElementContentCreate').modal('show');
                }
                if(id == 'elementContentEdit') {
                    this.element_content = element_content;
                    $('#elementContentEdit').modal('show');
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
            },
            copyUrl(element){
                try {
                    navigator.clipboard.writeText('http://127.0.0.1:8000/api/v1/element-content/' +element.type_contents.api_url+'/'+element.type_contents.version_major+'/'+element.type_contents.version_minor+'/'+ element.api_url+'/'+element.version_major+'/'+element.version_minor)
                    this.flashMessage.success({
                        message: 'Скопировано в буфер обмена',
                        time: 3000,
                    });
                } catch (e) {
                    this.flashMessage.error({
                        message: 'Ошибка копирования',
                        time: 3000,
                    });
                }
            }
        },
        watch: {
            'filter_form': {
                handler: function (newValue, oldValue) {
                    this.params = newValue;
                    this.getElementContent({id: this.id, params: this.params});
                },
                deep: true
            },
        },
        filters: {
            status: function (status) {
                let status_array = {Draft: 'Черновик', Published: 'Опубликовано', Archive:'В архиве', Destroy:'Помечен на удаление'};
                if(status){
                    return status_array[status];
                }else{
                    return status_array['Draft'];
                }
            },
            statusColor: function (status) {
                let status_array = {Draft: 'text-dark', Published: 'text-success', Archive:'text-warning', Destroy:'text-danger'};
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
                return moment(element.update_date).format('DD.MM.YYYY HH:II');
            },
        },

        async created(){
            this.getElementContent({id: this.id, params: this.params});
        },

        async mounted(){
            $('.form').hide();
        }
    }
</script>
<style scoped>
    table > :not(:first-child) {
        border-top: 1px solid currentColor !important;
    }
    .header-block{
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
    }
    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal-content {
        margin: 2px auto;
        z-index: 1100 !important;
    }
</style>

