<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
         <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal Dictionary create -->
        <div class="modal fade" id="ElementContentCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create
                    @close-modal="closeModal('ElementContentCreate', 1)"
                    @get-element-content-by-url="getElementContentByUrl()"
                >
                </Create>
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
                                    <date-picker v-model="filter_form.active_from"
                                                 :lang="lang"
                                                 placeholder="Период действия с"
                                                 :format="'DD.MM.YYYY'"
                                                 :valueType="'format'"/>
                                </div>
                                <div class="col-4">
                                    <date-picker v-model="filter_form.active_after"
                                                 :lang="lang"
                                                 placeholder="Период действия с"
                                                 :format="'DD.MM.YYYY'"
                                                 :valueType="'format'"/>
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
            <tr v-if="(!ElementContent.data || ElementContent.data.length === 0) && getLoading === false">
                <td class="text-center text-danger" colspan="6"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(element, index) in ElementContent.data" :key="index">
                <td>{{element.label}}</td>
                <td><p style="display:flex; justify-content: space-between; margin:0; padding:0">{{element.apiUrl}} <button class="btn btn-outline-primary btn-unbordered" @click="copyUrl(element)"><span class="fa fa-files-o fa-lg" aria-hidden="true"></span></button></p></td>
                <td :class="element.status | statusColor"><b>{{ element.status | status }}</b></td>
                <td>{{ element | date }}</td>
                <td>{{element.updatedAuthor.name}}</td>
                <td>{{ element.updatedDate }}</td>
                <!-- :href нужно убрать когда будут добавлены компоненты -->
                <td nowrap>
<!--                    <a :href="'/element-content/'+ element.id +'/edit'" class="btn btn-outline-primary btn-unbordered" @click="openModal('typeContentEdit', type_content)"><i class="fa fa-pencil fa-lg"></i></a>-->
<!--                    <a :href="'/element-content/enter/'+element.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>-->
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('elementContentEdit', element)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/element/enter-vue/'+element.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
        <pagination
            v-if="ElementContent.pages > 1"
            :total-pages="pages"
            :total="pages"
            :per-page="perPage"
            :max-visible-buttons="5"
            :page="page"
            @pagechanged="onPageChange"
        />
    </div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import Loader from "../helpers/Loader";
    import Pagination from "../helpers/Pagination";
    import moment from 'moment'
    import Create from './create'
    import Edit from "./Edit";
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';
    import {getUrlParams, updateUrl} from "../../helpers/tools";
    export default{
        components:{Loader, moment, Create, Edit, Pagination, DatePicker},
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
                    page: null,
                },
                page: 1,
                pages: 89, // всего страниц
                perPage: 15, // кол-во результатов на странице\
                lang: {
                    formatLocale: {
                        firstDayOfWeek: 1,
                    },
                    monthBeforeYear: false,
                },
            }
        },

        computed:{
            ...mapGetters(['ElementContent', 'getLoading']),
        },

        methods: {
            ...mapActions(['getElementContent']),
            closeModal(id){
                $("#"+id).modal("hide");
                if(id == 'elementContentEdit') {
                    this.getElementContentByUrl();
                }
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
                    navigator.clipboard.writeText('/api/v1/element-content/' +element.type_contents.api_url+'/'+element.type_contents.version_major+'/'+element.type_contents.version_minor+'/'+ element.api_url+'/'+element.version_major+'/'+element.version_minor)
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
            },
            async getElementContentByUrl() {
                await this.getElementContent({id: this.id, params: this.params})
                // очищаем урл строку чтобы пересобрать её заново
                let urlObj = getUrlParams();
                for (var key in urlObj) {
                    updateUrl('delete', [key]);
                }

                for (var key of Object.keys(this.params)) {
                    if(this.params[key] !== null) {
                        updateUrl('set', key, this.params[key]);
                    }
                }
                this.pages = this.ElementContent.pages;
            },
            onPageChange(page) {
                this.page = page;
                this.params.page = this.page;
                this.getElementContentByUrl();
            },
        },
        watch: {
            'filter_form': {
                handler: function (newValue, oldValue) {
                    this.params = newValue;
                    this.getElementContentByUrl();
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
                if(element.activeFrom === null && element.activeAfter === null){
                    return "Не задан";
                }else if(element.activeFrom === null && element.activeAfter != null){
                    return "До "+ element.activeAfter;
                } else if(element.activeFrom !== null && element.activeAfter === null){
                    return element.activeFrom + " - бессрочно";
                }else{
                    return element.activeFrom + " - " + element.activeAfter;
                }
            },
        },

        async created(){
            this.getElementContentByUrl();
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

