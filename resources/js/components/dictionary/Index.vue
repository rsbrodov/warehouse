<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
        <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create
                    @close-modal="closeModal('dictionaryCreate', 1)"
                    @get-dictionary-by-url="getDictionaryByUrl()"></Create>
            </div>
        </div>

        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryElementCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <CreateElement :dictionary_id="dictionary.id"
                               :load-list="false"
                               @close-modal="closeModal('dictionaryElementCreate')">
                </CreateElement>
            </div>
        </div>

        <!-- Modal Dictionary edit -->
        <div class="modal fade" id="dictionaryEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    :dictionary="dictionary"
                    @close-modal="closeModal('dictionaryEdit')"
                />
            </div>
        </div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-form col-6">
                    <div class="form" style="display: none;">
                        <form action="" method="post">
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Код справочника"
                                           id="code" class="form-control" v-model="filter_form.code">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name"
                                           placeholder="Наименование справочника" id="name" class="form-control"
                                           v-model="filter_form.name">
                                </div>
                                <div class="col-4">
                                    <select id="archive" class="form-control" name="archive"
                                            v-model="filter_form.archive">
                                        <option value='0,1'>Все</option>
                                        <option value='0'>Действующий</option>
                                        <option value='1'>Архивный</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-6 text-right">
                    <button id="search-btn" class="btn btn-outline-primary btn-unbordered" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                    <button id="clear-btn" class="btn btn-outline-primary btn-unbordered" style="display: none;"  @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryCreate')"><span class="fa fa-plus-circle fa-lg"></span></button>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>Наименование</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            <tr v-if="(!Dictionary.data || Dictionary.data.length === 0) && getLoading === false">
                <td v-if="dictionaryErrors === null" class="text-center text-danger" colspan="6"><b>Данные не найдены!</b></td>
                <td v-else class="text-center text-danger" colspan="6"><b style="color: red!important">{{dictionaryErrors.code}}</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(dictionary, index) in Dictionary.data" :key="index">
                <td>{{dictionary.name}}</td>
                <td>{{dictionary.description}}</td>
                <td>{{ dictionary.updatedDate }}</td>
                <td :class="dictionary.archive | statusColor"><b>{{ dictionary.archive | status }}</b></td>

                <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryEdit', dictionary)"><i
                        class="fa fa-pencil fa-lg"></i></a>
                    <a class="btn btn-outline-primary btn-unbordered" @click="archDictionary(dictionary.id)"><i
                        class="fa fa-exchange fa-lg"></i></a>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryElementCreate', dictionary)"><i
                        class="fa fa-plus fa-lg"></i></a>
                    <a :href="'/dictionary/'+dictionary.id+'/dictionary-element/'" class="btn btn-outline-primary btn-unbordered"><i
                        class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>

        <pagination
            v-if="Dictionary.pages > 1"
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
    import Create from './create'
    import CreateElement from "../dictionary-element/CreateElement";
    import Edit from "./Edit";
    import Loader from "../helpers/Loader";
    import Pagination from "../helpers/Pagination";
    import moment from 'moment';
    import {getUrlParams, updateUrl} from "../../helpers/tools";

    export default{
        components:{Loader, Create, CreateElement, Edit, Pagination},
        data:function(){
            return {
                dictionary:{},
                filter_form:{
                    archive:null,
                    name:null,
                    code:null,
                },
                params:{
                    name:null,
                    code:null,
                    archive:null,
                    page: null,
                },
                page: 1,
                pages: 89, // всего страниц
                perPage: 15, // кол-во результатов на странице
            }
        },
        computed:{
            ...mapGetters(['Dictionary', 'getLoading', 'dictionaryErrors']),
        },

        methods: {
            ...mapActions(['getDictionary', 'deleteDictionary', 'archiveDictionary']),
            closeModal(id){
                if(id == 'dictionaryCreate'){
                    console.log('CLOSE MODAL')
                    $("#dictionaryCreate").modal("hide");
                }
                if(id == 'dictionaryElementCreate'){
                    $("#dictionaryElementCreate").modal("hide");
                }
                if(id == 'dictionaryEdit'){
                    $("#dictionaryEdit").modal("hide");
                    this.getDictionaryByUrl();
                }

            },
            openModal(id, dictionary){
                if(id == 'dictionaryCreate') {
                    $('#dictionaryCreate').modal('show');
                }
                if(id == 'dictionaryElementCreate') {
                    $('#dictionaryElementCreate').modal('show');
                    this.dictionary = dictionary;
                }
                if(id == 'dictionaryEdit') {
                    $('#dictionaryEdit').modal('show');
                    this.dictionary = dictionary;
                }
            },
            removeDictionary(id){
                this.deleteDictionary(id)
                    .then(response => {
                        this.flashMessage.success({
                            message: 'Справочник успешно удален',
                            time: 3000,
                        });
                    });
            },
            archDictionary(id){
                this.archiveDictionary(id)
                    .then(response => {
                        this.flashMessage.success({
                            message: 'Статус справочника изменен',
                            time: 3000,
                        });
                        this.getDictionaryByUrl();
                    });
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
            },
            async getDictionaryByUrl(){
                await this.getDictionary({params: this.params})
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
                this.pages = this.Dictionary.pages;
            },
            onPageChange(page) {
                this.page = page;
                this.params.page = this.page;
                this.getDictionaryByUrl();
            },
        },
        watch: {
            'filter_form': {
                handler: function (newValue, oldValue) {
                    this.params = newValue;
                    this.getDictionaryByUrl();
                    console.log()
                    //this.updateUrl('set', 'page', 1);
                },
                deep: true
            },
            'dictionaryErrors': {
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
            dateUpdated: function (dictionary) {
                return moment(dictionary.updatedDate).format('DD.MM.YYYY HH:II');
            },
            status: function (status) {
                if(status == 1){
                    return "Архивный";
                }else{
                    return "Действующий";
                }
            },
            statusColor: function (status) {
                if(status == 1){
                    return "text-danger";
                }else{
                    return "text-success";
                }
            }
        },

        async created(){
            let urlObj = getUrlParams();
            for (var key in urlObj) {
                this.filter_form[key] = urlObj[key];
            }
            $('.form').hide();
            await this.getDictionaryByUrl();
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

.pagination{
    display: flex;
}
.pagination li {
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
    color: white;
    background-color: white;
    font-size: 1em;
  }
  .pagination li.pagination-active {
    background-color: green;
  }

.pagination  li:hover:not(.active) {background-color: yellow;}
</style>
