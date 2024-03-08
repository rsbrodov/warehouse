<template>
    <div>
        <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal Create -->
        <div class="modal fade" id="typeContentCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <create
                    @close-modal="closeModal('typeContentCreate')"
                    @get-type-content-by-url="getTypeContentByUrl()"
                />
            </div>
        </div>

        <!-- Modal edit -->
        <div class="modal fade" id="typeContentEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    v-model="type_content"
                    @close-modal="closeModal('typeContentEdit')"
                />
            </div>
        </div>

        <!-- <div class="text-center"><h3>Контентная модель</h3></div> -->
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-form col-6">
                    <div class="form">
                        <form>
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование типа" id="name" class="form-control" v-model="filter_form.name">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="status" v-model="filter_form.status">
                                        <option value='Draft,Published,Archive'>Все</option>
                                        <option value='Draft'>Черновик</option>
                                        <option value='Published'>Опубликовано</option>
                                        <option value='Archive'>В архиве</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select id="owner" class="form-control" name="owner" v-model="filter_form.owner">
                                        <option disabled selected value> -- Выберите пользователя -- </option>
                                        <option value=''>Все</option>
                                        <option v-for="(user, index) in users" :key="index" :value="user.id">
                                            <i aria-hidden="true">{{user.name}}</i>
                                        </option>
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
                                                 placeholder="Период действия по"
                                                 :format="'DD.MM.YYYY'"
                                                 :valueType="'format'"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-6 text-right">
                    <button id="search-btn" class="btn btn-outline-primary btn-unbordered" @click="toggleSearch()"><span class="fa fa-search fa-lg"></span></button>
                    <button class="btn btn-outline-primary btn-unbordered" id="clear-btn" style="display: none;" @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-outline-primary btn-unbordered" @click="openModal('typeContentCreate')"><span class="fa fa-plus-circle fa-lg"></span></button>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th style="white-space: nowrap">Наименование</th>
                <th>ИНН</th>
                <th>Статус</th>
                <th>Тариф</th>
                <th style="white-space: nowrap">Оплата</th>
                <th>Баланс</th>
                <th>Платеж</th>
                <th>Дата оплаты</th>
                <th>Действия</th>
            </tr>
            <tr v-if="(!typeContents.data || typeContents.data.length === 0) && getLoading === false">
                <td v-if="typeContentErrors === null" class="text-center text-danger" colspan="7"><b>Данные не найдены!</b></td>
                <td v-else class="text-center text-danger" colspan="7"><b style="color: red!important">{{typeContentErrors.code}}</b></td>
            </tr>
<!--            <tr v-else-if="getLoading === true" style="border:none">-->
<!--                <td class="text-center text-danger" colspan="6"><Loader/></td>-->
<!--            </tr>-->
            <tr v-else v-for="(type_content, index) in typeContents.data" :key="index" >
                <td style="white-space: nowrap">{{type_content.clientName}}</td>
                <td style="white-space: nowrap">{{type_content.innClient}}</td>
                <td :class="type_content.status | statusColor"><b>{{ type_content.status | status }}</b></td>
                <td style="white-space: nowrap">{{type_content.tariff}}</td>
                <td style="white-space: nowrap">Оплачено</td>
                <td style="white-space: nowrap">7000</td>
                <td style="white-space: nowrap">12000</td>
                <td>{{ type_content | date }}</td>
                <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('typeContentEdit', type_content)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/type-content/view-new/'+type_content.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
        <pagination
            v-if="typeContents.pages > 1"
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
    import Create from "./Create";
    import Edit from './Edit';
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    import Pagination from "../helpers/Pagination.vue";
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';
    import {getUrlParams, updateUrl} from "../../helpers/tools";
    export default{
        components:{Pagination, Create, Edit, Loader, DatePicker},
        data:function(){
            return {
                filter_form:{
                    status:null,
                    name:null,
                    owner:null,
                    active_from:null,
                    active_after:null,
                },
                type_content:{
                    id:null,
                    owner:null,
                    api_url:null,
                    icon:null,
                    name:null,
                    status:null,
                    active_after:null,
                    active_from:null,
                    description:null
                },
                params:{
                    status:null,
                    name:null,
                    owner:null,
                    active_from:null,
                    active_after:null,
                    page: null,
                },
                users:null,
                page: 1,
                pages: 89, // всего страниц
                perPage: 15, // кол-во результатов на странице
                lang: {
                    formatLocale: {
                        firstDayOfWeek: 1,
                    },
                    monthBeforeYear: false,
                },
            }
        },

        computed:{
            ...mapGetters(['typeContents', 'getLoading', 'typeContentErrors']),
        },

        methods: {
            ...mapActions(['getTypeContents']),
            closeModal(id){
                $("#"+id).modal("hide");
                if(id == 'typeContentEdit') {
                    this.getTypeContentByUrl();
                }

            },
            openModal(id, type_content){
                $('#'+id).modal('show');

                if(id == 'typeContentEdit') {
                    this.type_content = type_content;
                }
            },
            toggleSearch(){
                $('.form').toggle('show');
                $('#clear-btn').toggle('show');
                $('#search-btn').toggleClass('btn-primary btn-secondary');
                if( $('#search-btn').hasClass('btn-primary')){
                    $('#search-btn').html('<span class="fa fa-search fa-lg"></span>');
                } else if( $('#search-btn').hasClass('btn-secondary')){
                    $('#search-btn').html('Свернуть');
                }
            },
            cleanSearch(){
                $( 'form' ).each(function(){
                    this.reset();
                });
                this.params = this.filter_form;
                this.getTypeContentByUrl();
            },
            getUsers(){
                axios.get('/users-list')
                    .then(response => {
                        this.users = response.data;
                    });
            },
            async getTypeContentByUrl() {
                await this.getTypeContents({params: this.params})
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
                this.pages = this.typeContents.pages;
            },
            onPageChange(page) {
                this.page = page;
                this.params.page = this.page;
                this.getTypeContentByUrl();
            },
        },
        watch: {
            'filter_form': {
                handler: function (newValue, oldValue) {
                    this.params = newValue;
                    this.getTypeContentByUrl();
                },
                deep: true
            },
            'typeContentErrors': {
                handler: function (newValue, oldValue) {
                    if(newValue !== null && newValue !== oldValue){
                        this.flashMessage.error({
                            message: newValue.status + ' ' + newValue.code,
                            time: 3000,
                        });
                    }
                },
                deep: true
            },
        },
        filters: {
            date: function (type_content) {
                return type_content.updatedDate;
            },

            status: function (status) {
                let status_array = {Draft: 'Черновик', Active: 'Действующий', Archive:'В архиве'};
                if(status){
                    return status_array[status];
                }else{
                    return status_array['Draft'];
                }
            },
            statusColor: function (status) {
                let status_array = {Draft: 'text-dark', Active: 'text-success', Archive:'text-warning'};
                if(status){
                    return status_array[status];
                }else{
                    return status_array['Draft'];
                }
            }
        },

        async mounted(){
            $('.form').hide();
            this.getUsers();
            this.getTypeContentByUrl();
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
