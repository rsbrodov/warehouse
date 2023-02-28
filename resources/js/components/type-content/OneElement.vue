<style>
.flex-cont {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    width: 66.66%;
    align-items:center;
}

.flex-elem {
}
</style>
<template>
    <div id="app">
        <div class="row">
            <div class="col-9">
                <div class="flex-cont">
                    <div class="flex-elem title-one"><b>{{ elementContentOne.typeContent.name }}</b></div>
                    <div class="flex-elem title-one">
                        <a :href="'/api/v1/element-content/' +elementContentOne.typeContent.apiUrl+'/'+elementContentOne.typeContent.version.major+'/'+elementContentOne.typeContent.version.minor+'/'+ elementContentOne.apiUrl+'/'+elementContentOne.version.major+'/'+elementContentOne.version.minor" class="ml-1 btn btn-sm btn-outline-secondary form-control" style="width:50px; max-height:25px; line-height:1" target="_blank">API</a>
                    </div>
                    <div class="flex-elem"><b>API URL: </b>{{ elementContentOne.api_url }}</div>
                    <div class="flex-elem"><b>Статус: </b>{{ elementContentOne.status | status }}</div>
                    <div class="flex-elem"><b>Версия:
                    </b>{{ elementContentOne.version.major }}.{{ elementContentOne.version.minor }}</div>
                    <div class="flex-elem">
                        <b>Период действия: </b>{{ elementContentOne.status | date }}
                    </div>
                </div>
            </div>
            <div class="col-3 text-right"><a href="#" class="btn btn-outline-secondary"><i class="fa fa-pencil fa-lg"
                        aria-hidden="true"></i></a></div>
        </div>
        <nav class="mt-3">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a :class="setClass('enter-vue')" :href="'/element/enter-vue/'+elementContentOne.id">Контент</a>
                <a :class="setClass('nav-link')" >Доступ</a>
                <a :class="setClass('all-version-element-content')" :href="'/element-content/all-version-element-content/' + elementContentOne.id">История изменений</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">

        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: "Onetype",
    data() {
        return {
            element_content_id: window.location.href.split('/')[5],
            url: window.location.href.split('/')[4],
        };
    },
    computed: {
        ...mapGetters(['elementContentOne']),
    },

    methods: {
        ...mapActions(['getElementContentOne']),
        setClass($param){
            if($param === this.url){
                return 'nav-link active';
            }else{
                return 'nav-link';
            }
        }
    },

    async created() {
        await this.getElementContentOne(this.element_content_id);
    },
    filters: {
        date: function (element) {
            if(!element.active_from && !element.active_after){
                return "Не задан";
            }else if(!element.active_from && element.active_after){
                return "До "+ moment(element.active_after).format('DD.MM.YYYY');
            } else if(element.active_from && !element.active_after){
                return moment(element.active_from).format('DD.MM.YYYY') + " - бессрочно";
            }else{
                return moment(element.active_from).format('DD.MM.YYYY') + " - " + moment(element.active_after).format('DD.MM.YYYY');
            }
        },
        dateUpdated: function (element_content) {
            return moment(element_content.updated_at).format('DD.MM.YYYY HH:II');
        },
        status: function (status) {
            let status_array = { Draft: 'Черновик', Published: 'Опубликовано', Archive: 'В архиве', Destroy: 'На удаление' };
            if (status) {
                return status_array[status];
            } else {
                return status_array['Draft'];
            }
        },
    }

}
</script>

<style scoped>
.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
    color: #414141;
    background-color: #E1E1E1;
    border-color: #E1E1E1;
    min-width: 175px;
    border-radius: 0px;
}

.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link {
    color: #414141;
    background-color: #FFFFFF;
    border-color: #E1E1E1;
    min-width: 175px;
    border-radius: 0px;
}

.nav-tabs {
    border-bottom: 0px !important;
}

.title-one {
    color: #414141;
    font-size: 22px;
}
</style>
