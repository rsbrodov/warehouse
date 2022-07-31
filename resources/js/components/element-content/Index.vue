<template>
    <div>
        <!-- <div class="d-flex justify-content-center"><h1>Справочники</h1></div> -->
        <!-- <FlashMessage :position="'right bottom'"></FlashMessage> -->
        <!-- Modal Dictionary create -->
        <!-- <div class="modal fade" id="dictionaryCreate" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Create @close-modal="closeModal('dictionaryCreate', 1)"></Create>
            </div>
        </div> -->

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
                <!-- <div class="search-form col-6">
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
                </div> -->
                <!-- <div class="create col-6 text-right">
                    <button id="hideshow" class="btn btn-outline-primary btn-unbordered" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                    <button id="clean" class="btn btn-outline-primary btn-unbordered" style="display: none;"  @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <button type="button" class="btn-create btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryCreate')"><span class="fa fa-plus-circle fa-lg"></span></button>
                </div>-->
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

                <!-- <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryEdit', dictionary)"><i
                        class="fa fa-pencil fa-lg"></i></a>
                    <a class="btn btn-outline-primary btn-unbordered" @click="archDictionary(dictionary.id)"><i
                        class="fa fa-exchange fa-lg"></i></a>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('dictionaryElementCreate', dictionary)"><i
                        class="fa fa-plus fa-lg"></i></a>
                    <a :href="'/dictionary/'+dictionary.id+'/dictionary-element/'" class="btn btn-outline-primary btn-unbordered"><i
                        class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td> --> 
            </tr>
        </table>
    </div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    export default{
        components:{Loader, moment},
        data:function(){
            return {
                id: window.location.href.split('/').slice(-1)[0],
            }
        },

        computed:{
            ...mapGetters(['ElementContent']),
        },

        methods: {
            ...mapActions(['getElementContent', 'getLoading']),
            closeModal(id){
                if(id == 'dictionaryElementCreate'){
                    $("#dictionaryElementCreate").modal("hide");
                }

            },
            /*removeDictionaryElement(id){
                this.deleteDictionaryElement(id);
            },*/
            openModal(id){
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
</script>

