<template>
    <div>
        <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal edit -->
        <div class="modal fade" id="typeContentEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    :id="type_content.id"
                    :owner="type_content.owner"
                    :api_url="type_content.api_url"
                    :name="type_content.name"
                    :status="type_content.status"
                    :icon="type_content.icon"
                    :active_from="type_content.active_from"
                    :active_after="type_content.active_after"
                    :description="type_content.description"
                    @close-modal="closeModal('typeContentEdit')"
                />
            </div>
        </div>

        <Onetype/>
        <hr>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th style="white-space: nowrap">Тип контента</th>
                <!-- <th>Описание</th> -->
                <th>Версия</th>
                <th>Статус</th>
                <th>Идентификатор версии</th>
                <!-- <th style="white-space: nowrap">Период действия</th> -->
                <!-- <th>Дата последнего<br> редактирования</th> -->
                <th>Автор изменений</th>
                <th>Дата и время изменений</th>
                <th>Действия</th>
            </tr>
            <tr v-if="typeContentsAllVersion.length === 0 && getLoading === false">
                <td class="text-center text-danger" colspan="7"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(type_content, index) in typeContentsAllVersion" :key="index" >
                <td style="white-space: nowrap"><i :class="'fa ' + type_content.icon+ ' fa-lg'" aria-hidden="true"></i> {{type_content.name}}</td>
                <!-- <td>{{type_content.description}}</td> -->
                <td>{{type_content.version.major +'.'+ type_content.version.minor}}</td>
                <td :class="type_content.status | statusColor"><b>{{ type_content.status | status }}</b></td>
                <td>{{type_content.id}}</td>
                <td>Admin</td>
                <td>{{ type_content | dateUpdated }}</td>
                <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('typeContentEdit', type_content)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/type-content/view-new/'+type_content.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
        {{typeContentOne}}
    </div>
</template>


<script>
    import Onetype from './Onetype.vue';
    import {mapGetters, mapActions} from 'vuex'
    import Edit from './Edit';
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    export default{
        components:{Onetype, Edit, Loader},
        data:function(){
            return {
                type_content_id: window.location.href.split('/')[5],
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
                }
            }
        },

        computed:{
            ...mapGetters(['typeContentsAllVersion', 'getLoading']),
        },

        methods: {
            ...mapActions(['getTypeContentsAllVersion']),
            closeModal(id){
                $("#"+id).modal("hide");

            },
            openModal(id, type_content){
                $('#'+id).modal('show');

                if(id == 'typeContentEdit') {
                    this.type_content.id = type_content.id;
                    this.type_content.owner = type_content.owner;
                    this.type_content.api_url = type_content.api_url;
                    this.type_content.name = type_content.name;
                    this.type_content.status = type_content.status;
                    this.type_content.icon = type_content.icon;
                    this.type_content.active_after = type_content.active_after;
                    this.type_content.active_from = type_content.active_from;
                    this.type_content.description = type_content.description;
                }
            },
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

        async mounted(){
            $('.form').hide();
            this.getTypeContentsAllVersion(this.type_content_id);
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

