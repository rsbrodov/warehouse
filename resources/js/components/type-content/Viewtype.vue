<style>
    .flex-cont{
        display:inline-flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        width:66.66%;
    }
    .flex-elem{
        margin: 5px
    }
</style>
<template>
    <div id="app">
        <!-- Modal -->
        <FlashMessage :position="'right bottom'" style='z-index:20001;'></FlashMessage>
        <div class="modal fade" id="createElement" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Viewmodal
                    @close-modal="closeModal('createElement', $event)"
                    :copy="copy"
                    :clonedItems="clonedItems"
                ></Viewmodal>
            </div>
        </div>
        <!--End Modal -->


        <!-- Edit modal -->
        <div class="modal fade" id="editElement" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <Editmodal
                    @close-modal="closeModal('editElement', $event)"
                    :copy="copy"
                    :clonedItems="clonedItems"
                ></Editmodal>
            </div>
        </div>
        <!--End Modal -->

        <div class="container-fluid">
            <!-- Stack the columns on mobile by making one full-width and the other half-width -->
            <div class="row">
                <div class="col"><b><h1>{{typeContentOne.name}}</h1></b></div>
                <div class="col"></div>
            </div>
            <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
            <div class="row">
                <div class="col-9">
                    <div class="flex-cont">
                        <div class="flex-elem"><b>Идентификатор: </b>{{typeContentOne.id}}</div>
                        <div class="flex-elem"><b>API URL: </b>{{typeContentOne.api_url}}</div>
                        <div class="flex-elem"><b>Владелец: </b> Admin</div>
                        <div class="flex-elem">
                            <b>Период действия: </b>{{typeContentOne.status | date}}
                        </div>
                        <div class="flex-elem"><b>Статус: </b>{{typeContentOne.status | status}}</div>
                        <div class="flex-elem"><b>Версия: </b>{{typeContentOne.version_major}}.{{typeContentOne.version_minor}}</div>
                    </div>
                </div>
                <div class="col-3 text-right"><a href="#" class="btn btn-outline-secondary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></div>
            </div>

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-structure-tab" data-bs-toggle="tab" data-bs-target="#nav-structure" type="button" role="tab" aria-controls="nav-structure" aria-selected="true">Состав полей</button>
                    <button class="nav-link" id="nav-access-tab" data-bs-toggle="tab" data-bs-target="#nav-access" type="button" role="tab" aria-controls="nav-access" aria-selected="false">Доступ</button>
                    <button class="nav-link" id="nav-story-tab" data-bs-toggle="tab" data-bs-target="#nav-story" type="button" role="tab" aria-controls="nav-story" aria-selected="false">История изменений</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-structure" role="tabpanel" aria-labelledby="nav-structure-tab">Какой-то состав полей</div>
                <div class="tab-pane fade" id="nav-access" role="tabpanel" aria-labelledby="nav-access-tab">Доступ к чему-то</div>
                <div class="tab-pane fade" id="nav-story" role="tabpanel" aria-labelledby="nav-story-tab">Пока это просто кнопка:<a class="nav-link" :href="'/all-version-type-content/'+typeContentOne.id_global">История изменений</a></div>
            </div>

            <br>
            <br>
            <div class="row ">
                <div class="col-9 left-block">
                    <div class="left-block__draggable-layout" v-for="(mas, index) in clonedItems" :key="index">
                        <i class="fa fa-trash mr-2 mt-2 text-primary lg" @click="deleteRow(index)"></i>
                        <draggable class="left-block__draggable-layout__draggable-parent"  ghost-class="ghost" v-model="clonedItems[index]" :options="clonedItemOptions">
                            <transition-group type="transition" name="flip-list">
                                <div class="clickable left-block__draggable-layout__draggable-parent__item" v-for="(item, indexing) in mas" :key="uuid(item)" >
                                    <p class="pl-2 pt-3 text-secondary"><i :class="item.class"></i> {{item.title}}</p>
                                    <div class="button-group">
                                        <button class="btn btn-outline-primary mr-2" @click="EditItem(item.uid)"><i class="fa fa-pencil fa-sm"></i></button>
                                        <button class="btn btn-outline-primary mr-2" @click="deleteItem(index, indexing)"><i class="fa fa-trash fa-sm"></i></button>
                                    </div>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>
                </div>

                <div class="col-3">
                    <div class="d-flex flex-column">
                        <div class="p-2"><button class="btn btn-primary form-control text-left" @click="saveBody()"><i
                            class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить черновик</button></div>
                        <div class="p-2"><a href="" class="btn btn-primary form-control text-left"><i
                            class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация типа</a></div>
                        <div class="p-2"><a href="" class="btn btn-primary form-control text-left">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить тип</a></div>
                        <div class="p-2">
                            <button @click="pushRow()" class="btn btn-outline-secondary form-control text-left"><i class="fa fa-bars fa-lg" aria-hidden="true"></i> Добавить строку</button>
                        </div>
                        <div class="p-2"><a href="" class="btn btn-outline-secondary form-control text-left"><i
                            class="fa fa-columns fa-lg" aria-hidden="true"></i> Добавить колонку</a></div>


                        <draggable
                            v-model="availableItems"
                            :options="availableItemOptions"
                            :clone="handleClone"
                            @end="moveAction"
                        >
                            <div class="p-2" v-for="item in availableItems" >
                                <a class="btn btn-outline-secondary form-control text-left">
                                    <i :class="item.class" aria-hidden="true"></i> {{item.name}}
                                </a>
                            </div>
                        </draggable>
                    </div>
                </div>
            </div>
        </div>34
    </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import Viewmodal from "./Viewmodal";
    import Editmodal from "./Editmodal";
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "Viewtype",
        components: {draggable, Viewmodal, Editmodal},
        data() {
            return {
                type_content_id: window.location.href.split('/')[5],
                copy: null,
                data: null,
                clonedItems: [
                    [

                    ]
                ],
                availableItems: [
                    {
                        class: "fa fa-code fa-lg",
                        name: "HTML редактор",
                        type: "textarea",
                    },
                    {
                        class: "fa fa-caret-down fa-lg",
                        name: "Выпадающий список",
                        type: "select",
                    },
                    {
                        class: "fa fa-calendar fa-lg",
                        name: "Дата/Время",
                        type: "datetime",
                    },
                    {
                        class: "fa fa-image fa-lg",
                        name: "Изображение",
                        type: "file",
                    },
                    {
                        class: "fa fa-list fa-lg",
                        name: "Радио-группа",
                        type: "radio",
                    },
                    {
                        class: "fa fa-text-height fa-lg",
                        name: "Текстовое поле",
                        type: "text",
                    },
                ],

                clonedItemOptions: {
                    group: "items"
                },

                availableItemOptions: {
                    group: {
                        name: "items",
                        pull: "clone",
                        put: false
                    },
                    sort: false
                }
            };
        },
        computed:{
            ...mapGetters(['typeContentOne']),

        },

        methods: {
             ...mapActions(['getTypeContentOne']),
            handleClone(item) {
                let cloneMe = JSON.parse(JSON.stringify(item));
                this.$set(cloneMe, 'title', '');
                this.$set(cloneMe, 'required', '');

                if(cloneMe.type == 'select' || cloneMe.type == 'radio'){
                    this.$set(cloneMe, 'dictionary_id', null);
                }

                //делаем ключик в момент клонирования
                const key = Math.random().toString(16).slice(2);
                this.$set(cloneMe, "uid", key);

                this.copy = key;
                return cloneMe;
            },
            moveAction(item) {
                this.openModal('createElement');
            },

            deleteItem(index, indexing) {
                this.clonedItems[index].splice(indexing, 1);
            },
            deleteRow(index) {
                if (this.clonedItems.length > 1){
                    this.clonedItems.splice(index, 1);
                }else{
                    this.flashMessage.error({
                        message: 'Нельзя удалить все блоки',
                        time: 3000,
                    });
                }

            },

            uuid(e) {
                if (e.uid) return e.uid;
                const key = Math.random()
                    .toString(16)
                    .slice(2);
                this.$set(e, "uid", key);
                return e.uid;
            },

            closeModal(id) {
                $("#"+id).modal("hide");

            },
            openModal(id) {
                $('#'+id).modal('show');
            },

            EditItem(uid) {
                this.copy = uid;
                this.openModal('editElement');
            },
            pushRow() {
                let dop_array = [];
                this.clonedItems.push(dop_array);
            },
            saveBody(){
                axios.post('http://127.0.0.1:8000/type-content/save-body', {id: this.type_content_id, body: this.clonedItems})
                .then(response => {
                    if (response.status === 200){
                        this.flashMessage.success({
                            message: 'Данные сохранены',
                            time: 3000,
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
                })
            },

            async getBody(){
               await axios.get('http://127.0.0.1:8000/type-content/get-body/'+ this.type_content_id)
                    .then(response => {
                        if (Object.keys(response.data).length === 0){
                            this.clonedItems = [[]];
                        }else{
                            this.clonedItems = response.data
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
        },

        async created(){
            await this.getBody();
            await this.getTypeContentOne(this.type_content_id);
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
        }

    }
</script>

<style scoped>
    .left-block {
        z-index:1;
        background-color: #ededed;
    }

    .left-block__draggable-layout {
        background-color: white;
        min-height: 110px;
        max-height: 11000px;
        text-align: right;
        padding-bottom: 10px;
        margin-top:20px;

    }

    .left-block__draggable-layout__draggable-parent {
        background-color: #c4c4c4;
        border-radius: 5px;
        min-height: 60px;
        width: 96%;
        margin:0 auto;
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
    .left-block__draggable-layout__draggable-parent__item > .button-group {
        display: flex;
        justify-content: space-between;
    }
    .left-block__draggable-layout__draggable-parent__item > p {
        color: black;
        font-size: 18px;
    }
    i:hover {
        cursor: pointer;
    }

/*это класс от транзишина он дает задержку*/
    .flip-list-move{
        transition: transform 0.5s;
    }
    /*это класс от драгабла он дает тень прозрачность и подцветку*/
    .ghost{
        border-left: 6px solid rgb(0, 183, 255);
        box-shadow: 10px 10px 5px -1px rgb(0, 0, 0, 0.14);
        opacity: .7;
    }
    ._vue-flash-msg-container_right-bottom {
        z-index: 10000000 !important;
    }
</style>
