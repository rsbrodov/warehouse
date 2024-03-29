<style>
.flex-cont {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    width: 66.66%;
}

.flex-elem {
    margin: 5px
}
</style>
<template>
    <div id="app">
        <div v-if="typeContentOne" class="content">
            <div class="row">
                <div class="col">
                    <div style="display: flex">
                        <b><h2>{{typeContentOne.clientName}}</h2></b>
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col-9">
                    <div class="flex-cont">
                        <div class="flex-elem"><b>Статус: </b>{{ typeContentOne.status | status}}</div>
                        <div class="flex-elem"><b>Баланс: </b> 8000</div>
                        <div class="flex-elem"><b>Платеж: </b> 5000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Edit from './Edit';

export default {
    name: "Onetype",
    components:{Edit},
    data() {
        return {
            type_content_id: window.location.href.split('/')[5],
            url: window.location.href.split('/')[4],
        };
    },
    computed: {
        ...mapGetters(['typeContentOne']),
    },

    methods: {
        ...mapActions(['getTypeContentOne']),
        setClass($param){
            if($param === this.url){
                return 'nav-link active';
            }else{
                return 'nav-link';
            }
        },
        openModal(id, type_content){
            $('#'+id).modal('show');
        },
        async closeModal(id){
            $("#"+id).modal("hide");
            if(id == 'typeContentEdit') {
                await this.getTypeContentOne(this.type_content_id);
            }

        },
    },

    async created() {
        await this.getTypeContentOne(this.type_content_id);
    },
    filters: {
        date: function (type_content) {
            if (!type_content.active_from && !type_content.active_after) {
                return "Не задан";
            } else if (!type_content.active_from && type_content.active_after) {
                return "До " + moment(type_content.active_after).format('DD.MM.YYYY');
            } else if (type_content.active_from && !type_content.active_after) {
                return moment(type_content.active_from).format('DD.MM.YYYY') + " - бессрочно";
            } else {
                return moment(type_content.active_from).format('DD.MM.YYYY') + " - " + moment(type_content.active_after).format('DD.MM.YYYY');
            }

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






.left-block {
    z-index: 1;
    background-color: #ededed;
}

.left-block__draggable-layout {
    background-color: white;
    min-height: 110px;
    max-height: 11000px;
    text-align: right;
    padding-bottom: 10px;
    margin-top: 20px;

}

.left-block__draggable-layout__draggable-parent {
    background-color: #c4c4c4;
    border-radius: 5px;
    min-height: 60px;
    width: 96%;
    margin: 0 auto;
    padding-bottom: 10px;
    padding-top: 10px;
}

.left-block__draggable-layout__draggable-parent__item {
    background-color: white;
    width: 98%;
    border-radius: 5px;
    height: 45px;
    margin: 5px auto;
    cursor: move;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.left-block__draggable-layout__draggable-parent__item:active {
    cursor: grabbing;
}

.left-block__draggable-layout__draggable-parent__item>.button-group {
    display: flex;
    justify-content: space-between;
}

.left-block__draggable-layout__draggable-parent__item>p {
    color: black;
    font-size: 18px;
}

i:hover {
    cursor: pointer;
}

/*это класс от транзишина он дает задержку*/
.flip-list-move {
    transition: transform 0.5s;
}

/*это класс от драгабла он дает тень прозрачность и подцветку*/
.ghost {
    border-left: 6px solid rgb(0, 183, 255);
    box-shadow: 10px 10px 5px -1px rgb(0, 0, 0, 0.14);
    opacity: .7;
}

._vue-flash-msg-container_right-bottom {
    z-index: 10000000 !important;
}
</style>
