<template>
<div>

        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create @close-modal="closeModal('dictionaryCreate')"></Create>
            </div>
        </div>

        <!-- Modal Dictionary create -->
        <div class="modal fade" id="dictionaryElementCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <CreateElement @close-modal="closeModal('dictionaryElementCreate')"></CreateElement>
            </div>
        </div>
        <div class="d-flex justify-content-center"><h1>Справочники</h1></div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-button col-2">
                    <button id="hideshow" class="btn btn-primary" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                </div>
                <div class="search-form col-8">
                    <div class="form">
                        <form action="" method="post">
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Код справочника" id="code" class="form-control" v-model="filter_form.code">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование справочника" id="name" class="form-control" v-model="filter_form.name">
                                </div>
                                <div class="col-4">
                                    <select id="archive" class="form-control" name="archive" v-model="filter_form.archive">
                                        <option value=''>Все</option>
                                        <option value='0'>Действующий</option>
                                        <option value='1'>Архивный</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-2">
                    <button id="clean" class="btn btn-primary" @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-primary" @click="openModal('dictionaryCreate')">
                        <span class="fa fa-plus-circle fa-lg"></span>
                    </button>
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
            <tr v-for="(dictionary, index) in filteredDictionary" :key="index" >
                <td>{{dictionary.code}}</td>
                <td>{{dictionary.name}}</td>
                <td>{{dictionary.description}}</td>
                <td>{{ dictionary | dateUpdated }}</td>
                <td :class="dictionary.archive | statusColor"><b>{{ dictionary.archive | status }}</b></td>

                <td nowrap>
                <button class="btn btn-danger del" @click="removeDictionary(dictionary.id)"><i class="fa fa-trash fa-lg"></i></button>
                <button class="btn btn-warning plus" @click="openModal('dictionaryElementCreate')"><i class="fa fa-plus fa-lg" style="color:white"></i></button>
                <a :href="'/dictionary/'+dictionary.id+'/dictionary-element/'" class="btn btn-success eye"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
</div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import Create from './create'
    import CreateElement from "./CreateElement";
    import moment from 'moment'
    export default{
        components:{Create, CreateElement},
        data:function(){
            return {
                filter_form:{
                    archive:'',
                    name:'',
                    code:'',
                },
            }
        },

        computed:{
            ...mapGetters(['Dictionary']),
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
            ...mapActions(['getDictionary', 'deleteDictionary']),
            closeModal(id){
                if(id == 'dictionaryCreate'){
                    $("#dictionaryCreate").modal("hide");
                }
                if(id == 'dictionaryElementCreate'){
                    $("#dictionaryElementCreate").modal("hide");
                }

            },
            removeDictionary(id){
                this.deleteDictionary(id);
            },
            openModal(id){
                if(id == 'dictionaryCreate') {
                    $('#dictionaryCreate').modal('show');
                }
                if(id == 'dictionaryElementCreate') {
                    $('#dictionaryElementCreate').modal('show');
                }
            },
            toggleSearch(){
                $('.form').toggle('show');
            },
            cleanSearch(){
                $( 'form' ).each(function(){
                    this.reset();
                });
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
            this.getDictionary();
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
    .del {
        background-color: #dc3545!important;
    }
    .plus {
        background-color: #ffc107!important;
    }
    .eye {
        background-color: #28a745!important;
    }
</style>
