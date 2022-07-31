<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
        <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create @close-modal="closeModal('dictionaryCreate', 1)"></Create>
            </div>
        </div>

        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryElementCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <CreateElement :dictionary_id="dictionary.id"
                               @close-modal="closeModal('dictionaryElementCreate')"></CreateElement>
            </div>
        </div>

        <!-- Modal Dictionary edit -->
        <div class="modal fade" id="dictionaryEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    :dictionary_id="dictionary.id"
                    :dictionary_name="dictionary.name"
                    :dictionary_code="dictionary.code"
                    :dictionary_description="dictionary.description"
                    @close-modal="closeModal('dictionaryEdit')"
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
                                        <option value=''>Все</option>
                                        <option value='0'>Действующий</option>
                                        <option value='1'>Архивный</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-6 text-right">
                    <button id="hideshow" class="btn btn-outline-primary btn-unbordered" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                    <button id="clean" class="btn btn-outline-primary btn-unbordered" style="display: none;"  @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryCreate')"><span class="fa fa-plus-circle fa-lg"></span></button>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>Код справочника</th>
                <th>Наименование справочника</th>
                <th>Описание справочника</th>
                <th>Последнее изменение</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            <tr v-if="filteredDictionary.length === 0 && getLoading === false">
                <td class="text-center text-danger" colspan="6"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(dictionary, index) in pageOfItems" :key="index">
                <td>{{dictionary.code}}</td>
                <td>{{dictionary.name}}</td>
                <td>{{dictionary.description}}</td>
                <td>{{ dictionary | dateUpdated }}</td>
                <td :class="dictionary.archive | statusColor"><b>{{ dictionary.archive | status }}</b></td>

                <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryEdit', dictionary)"><i
                        class="fa fa-pencil fa-lg"></i></a>
<!--                    <button class="btn btn-danger del" @click="removeDictionary(dictionary.id)"><i-->
<!--                        class="fa fa-trash fa-lg"></i></button>-->
                    <a class="btn btn-outline-primary btn-unbordered" @click="archDictionary(dictionary.id)"><i
                        class="fa fa-exchange fa-lg"></i></a>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryElementCreate', dictionary)"><i
                        class="fa fa-plus fa-lg"></i></a>
                    <a :href="'/dictionary/'+dictionary.id+'/dictionary-element/'" class="btn btn-outline-primary btn-unbordered"><i
                        class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>

        <div class="card-footer pb-0 pt-3">
            <jw-pagination :items="filteredDictionary" @changePage="onChangePage"></jw-pagination>
        </div>

    </div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import Create from './create'
    import CreateElement from "../dictionary-element/CreateElement";
    import Edit from "./Edit";
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    export default{
        components:{Loader, Create, CreateElement, Edit},
        data:function(){
            return {
                dictionary:{
                    id:null,
                    name:null,
                    code:null,
                    description:null,
                },
                filter_form:{
                    archive:'',
                    name:'',
                    code:'',
                },
                pageOfItems: []
            }
        },

        computed:{
            ...mapGetters(['Dictionary', 'getLoading']),
            filteredDictionary: function () {
                return this.Dictionary.filter((dictionary) => {
                    return dictionary.name.toLowerCase().match(this.filter_form.name)
                }).filter((dictionary) => {
                    return dictionary.code.toLowerCase().match(this.filter_form.code);
                })/*.filter((dictionary) => {
                    return dictionary.archive.match(this.filter_form.archive);
                })*/;
            }
        },

        methods: {
            ...mapActions(['getDictionary', 'deleteDictionary', 'archiveDictionary']),
            closeModal(id){
                if(id == 'dictionaryCreate'){
                    $("#dictionaryCreate").modal("hide");
                }
                if(id == 'dictionaryElementCreate'){
                    $("#dictionaryElementCreate").modal("hide");
                }
                if(id == 'dictionaryEdit'){
                    $("#dictionaryEdit").modal("hide");
                }

            },
            openModal(id, dictionary){
                if(id == 'dictionaryCreate') {
                    $('#dictionaryCreate').modal('show');
                }
                if(id == 'dictionaryElementCreate') {
                    $('#dictionaryElementCreate').modal('show');
                    this.dictionary_id = dictionary.id;
                }
                if(id == 'dictionaryEdit') {
                    $('#dictionaryEdit').modal('show');
                    this.dictionary.id = dictionary.id;
                    this.dictionary.name = dictionary.name;
                    this.dictionary.code = dictionary.code;
                    this.dictionary.description = dictionary.description;
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
                    });
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
            onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        }
        },

        filters: {
            dateUpdated: function (dictionary) {
                return moment(dictionary.updated_at).format('DD.MM.YYYY HH:II');
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

        },

        async mounted(){
            $('.form').hide();
            this.getDictionary();
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
