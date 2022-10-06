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
        <!-- Stack the columns on mobile by making one full-width and the other half-width -->
        <div class="row">
            <div class="col"><b>
                    <h2>{{ elementContentOne.name }}</h2>
                </b></div>
            <div class="col"></div>
        </div>
        <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
        <div class="row">
            <div class="col-9">
                <div class="flex-cont">
                    <div class="flex-elem title-one"><b>{{ elementContentOne.type_contents.name }}</b></div>
                    <div class="flex-elem"><b>API URL: </b>{{ elementContentOne.api_url }}</div>
                    <div class="flex-elem"><b>Статус: </b>{{ elementContentOne.status | status }}</div>
                    <div class="flex-elem"><b>Версия:
                    </b>{{ elementContentOne.version_major }}.{{ elementContentOne.version_minor }}</div>
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
                <a :class="setClass('enter-vue')" :href="'/type-content/enter-vue/' + elementContentOne.id">Контент</a>
                <a :class="setClass('nav-link')" >Доступ</a>
                <a :class="setClass('all-version-type-content')">История изменений</a>
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
        date: function (element_content) {
            if (!element_content.active_from && !element_content.active_after) {
                return "Не задан";
            } else if (!element_content.active_from && element_content.active_after) {
                return "До " + moment(element_content.active_after).format('DD.MM.YYYY');
            } else if (element_content.active_from && !element_content.active_after) {
                return moment(element_content.active_from).format('DD.MM.YYYY') + " - бессрочно";
            } else {
                return moment(element_content.active_from).format('DD.MM.YYYY') + " - " + moment(element_content.active_after).format('DD.MM.YYYY');
            }

        },
        dateUpdated: function (element_content) {
            return moment(element_content.updated_at).format('DD.MM.YYYY HH:II');
        },
        status: function (status) {
            let status_array = { Draft: 'Черновик', Published: 'Опубликовано', Archive: 'В архиве' };
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
