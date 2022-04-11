<template>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <create/>
            </div>
        </div>

<!--        <b-modal id="modal-1" class="mb-4" size="lg" title="Создание типа контента" ></b-modal>-->
        <div class="text-center"><h3>Контентная модель</h3></div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-button col-2">
                    <button id="hideshow" class="btn btn-primary"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                </div>
                <div class="search-form col-8">
                    <div class="form">
                        <form>
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование типа" id="name" class="form-control" v-model="filter_form.name">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="status" v-model="filter_form.status">
                                        <option value=''>Все</option>
                                        <option value='Draft'>Черновик</option>
                                        <option value='Published'>Опубликовано</option>
                                        <option value='Archive'>В архиве</option>
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
                <div class="create col-2">
                    <button id="clean" class="btn btn-primary"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
<!--                    <b-button v-b-modal.modal-1 variant="success" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg" aria-hidden="true"></span></b-button>-->
                    <!-- Button trigger modal -->
                    <button type="button" class="btn-create btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        <span class="fa fa-plus-circle fa-lg" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

        </div>

        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th style="white-space: nowrap">Тип контента</th>
                <th>Описание</th>
                <th>Версия</th>
                <th>Статус</th>
                <th style="white-space: nowrap">Период действия</th>
                <th>Дата последнего<br> редактирования</th>
                <th>Действия</th>
            </tr>
            <tr v-for="(type_content, index) in filteredTypeContents " :key="index" >
                <td style="white-space: nowrap"><i :class="'fa ' + type_content.icon+ ' fa-lg'" aria-hidden="true"></i> {{type_content.name}}</td>
                <td>{{type_content.description}}</td>
                <td>{{type_content.version_major +'.'+ type_content.version_minor}}</td>
                <td :class="type_content.status | statusColor"><b>{{ type_content.status | status }}</b></td>
                <td>{{ type_content | date }}</td>
                <td>{{ type_content | dateUpdated }}</td>
                <td class="pencil" nowrap>
                    <a :href="'/all-version-type-content/'+type_content.id_global" class="btn btn-primary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a>
                    <a :href="'/all-version-type-content/'+type_content.id_global" class="btn btn-primary ml-3"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
    </div>
</template>

<style scoped>
    table > :not(:first-child) {
        border-top: 1px solid currentColor !important;
    }
    .header-block{
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
    }
    .pencil > a {
        background-color: #007bff!important;
    }

    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal-content {
        margin: 2px auto;
        z-index: 1100 !important;
    }
</style>

<script>
    import Create from "./Create";
    import moment from 'moment'
    export default{
        components:{Create},
        data:function(){
            return {
                type_contents:[],
                filter_form:{
                    status:'',
                    name:'',
                    active_from:'',
                    active_after:'',
                },
            }
        },


        computed:{
            filteredTypeContents: function () {
                return this.type_contents.filter((type_content) => {
                    return type_content.name.toLowerCase().match(this.filter_form.name);
                }).filter((type_content) => {
                    return type_content.status.match(this.filter_form.status);
                });
            }
        },
        methods: {
            async getTypeContent() {
                axios.get('http://127.0.0.1:8000/type-content/getListTypeContent')
                    .then(response => {
                        //console.log(response.data)
                        this.type_contents = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        filters: {
            date: function (type_content) {
                if(!type_content.active_from && !type_content.active_after){
                    return "Не задан";
                }else if(!type_content.active_from && type_content.active_after){
                    return "До "+ moment(type_content.active_after).format('DD.MM.YYYY');
                } else if(type_content.active_from && !type_content.active_after){
                    return moment(type_content.active_from).format('DD.MM.YYYY') + " - бессрочно";
                }else{
                    return moment(type_content.active_from).format('DD.MM.YYYY') + " - " + moment(type_content.active_after).format('DD.MM.YYYY');
                }

            },
            dateUpdated: function (type_content) {
                return moment(type_content.updated_at).format('DD.MM.YYYY HH:II');
            },
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
            }
        },

        async created(){
            this.getTypeContent();
            $('.form').hide();
        },
    }


    $('#hideshow').on('click', function(event) {
        $('.form').toggle('show');
    });
    $("#clean").click(function() {
        $( 'form' ).each(function(){
            this.reset();
        });
    });
</script>

<style scoped>

</style>
