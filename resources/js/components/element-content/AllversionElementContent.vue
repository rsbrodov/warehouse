<template>
    <div>
        <FlashMessage :position="'right bottom'"></FlashMessage>
        <!-- Modal edit -->
        <div class="modal fade" id="elementContentEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Edit
                    :id="element_content.id"
                    :api_url="element_content.api_url"
                    :label="element_content.label"
                    :status="element_content.status"
                    :active_from="element_content.active_from"
                    :active_after="element_content.active_after"
                    :description="element_content.description"
                    @close-modal="closeModal('elementContentEdit')"
                />
            </div>
        </div>

        <OneElement/>
        <hr>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th style="white-space: nowrap">Элемент контента</th>
                <th>Версия</th>
                <th>Статус</th>
                <th>Идентификатор версии</th>
                <th>Автор изменений</th>
                <th>Дата и время изменений</th>
                <th>Действия</th>
            </tr>
            <tr v-if="elementContentsAllVersion.length === 0 && getLoading === false">
                <td class="text-center text-danger" colspan="7"><b>Данные не найдены!</b></td>
            </tr>
            <tr v-else-if="getLoading === true" style="border:none">
                <td class="text-center text-danger" colspan="6"><Loader/></td>
            </tr>
            <tr v-else v-for="(element_content, index) in elementContentsAllVersion" :key="index" >
                <td style="white-space: nowrap"> {{element_content.label}}</td>
                <td>{{element_content.version_major +'.'+ element_content.version_minor}}</td>
                <td :class="element_content.status | statusColor"><b>{{ element_content.status | status }}</b></td>
                <td>{{element_content.id}}</td>
                <td>Admin</td>
                <td>{{ element_content | dateUpdated }}</td>
                <td nowrap>
                    <a class="btn btn-outline-primary btn-unbordered" @click="openModal('elementContentEdit', element_content)"><i class="fa fa-pencil fa-lg"></i></a>
                    <a :href="'/element/enter-vue/'+element_content.id" class="btn btn-outline-primary btn-unbordered"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        </table>
    </div>
</template>


<script>
    import OneElement from '../type-content/OneElement';
    import {mapGetters, mapActions} from 'vuex'
    import Edit from './Edit';
    import Loader from "../helpers/Loader";
    import moment from 'moment'
    export default{
        components:{OneElement, Edit, Loader},
        data:function(){
            return {
                element_content_id: window.location.href.split('/')[5],
                element_content:{
                    id:null,
                    api_url:null,
                    label:null,
                    status:null,
                    active_after:null,
                    active_from:null,
                    description:null
                }
            }
        },

        computed:{
            ...mapGetters(['elementContentsAllVersion', 'getLoading']),
        },

        methods: {
            ...mapActions(['getElementContentsAllVersion']),
            closeModal(id){
                $("#"+id).modal("hide");

            },
            openModal(id, element_content){
                $('#'+id).modal('show');

                if(id == 'elementContentEdit') {
                    this.element_content.id = element_content.id;
                    this.element_content.api_url = element_content.api_url;
                    this.element_content.label = element_content.label;
                    this.element_content.status = element_content.status;
                    this.element_content.icon = element_content.icon;
                    this.element_content.active_after = element_content.active_after;
                    this.element_content.active_from = element_content.active_from;
                    this.element_content.description = element_content.description;
                }
            },
        },

        filters: {
            date: function (element_content) {
                if(!element_content.active_from && !element_content.active_after){
                    return "Не задан";
                }else if(!element_content.active_from && element_content.active_after){
                    return "До "+ moment(element_content.active_after).format('DD.MM.YYYY');
                } else if(element_content.active_from && !element_content.active_after){
                    return moment(element_content.active_from).format('DD.MM.YYYY') + " - бессрочно";
                }else{
                    return moment(element_content.active_from).format('DD.MM.YYYY') + " - " + moment(element_content.active_after).format('DD.MM.YYYY');
                }

            },
            dateUpdated: function (element_content) {
                return moment(element_content.update_date).format('DD.MM.YYYY HH:II');
            },
            status: function (status) {
                let status_array = {Draft: 'Черновик', Published: 'Опубликовано', Archive:'В архиве', Destroy: 'На удаление'};
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
            }
        },

        async mounted(){
            $('.form').hide();
            this.getElementContentsAllVersion(this.element_content_id);
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

