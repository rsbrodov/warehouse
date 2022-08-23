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
            <Onetype/>
            <hr>
            <br>
            <div class="row ">
                <div class="col-9 left-block">
                <FlashMessage :position="'right bottom'" style='z-index:20001;'></FlashMessage>

                    <div class="left-block__draggable-layout" v-for="(mas, index) in clonedItems" :key="index">
                        <i class="fa fa-trash mr-2 mt-2 text-primary lg" @click="deleteRow(index)"></i>
                        <draggable class="left-block__draggable-layout__draggable-parent"  ghost-class="ghost" v-model="clonedItems[index]" :options="clonedItemOptions">
                            <!-- <transition-group type="transition" name="flip-list"> -->
                                <div class="clickable left-block__draggable-layout__draggable-parent__item" v-for="(item, indexing) in mas" :key="uuid(item)" >
                                    <p class="pl-2 pt-3 text-secondary"><i :class="item.class"></i> {{item.title}}</p>
                                    <div class="button-group">
                                        <button class="btn btn-outline-primary mr-2" @click="EditItem(item.uid)"><i class="fa fa-pencil fa-sm"></i></button>
                                        <button class="btn btn-outline-primary mr-2" @click="deleteItem(index, indexing)"><i class="fa fa-trash fa-sm"></i></button>
                                    </div>
                                </div>
                            <!-- </transition-group> -->
                        </draggable>
                    </div>
                </div>

                <div class="col-3">
                    <div class="d-flex flex-column">
                        <div class="p-2" v-if="typeContentOne.status == 'Draft'">
                            <button class="btn btn-primary form-control text-left" @click="saveBody()">
                                <i class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить черновик
                            </button>
                        </div>
                        <div class="p-2" v-if="typeContentOne.status == 'Draft'">
                            <button class="btn btn-primary form-control text-left" @click="publishTypeContent()">
                                <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация типа
                            </button>
                        </div>
                        <div class="p-2" v-if="typeContentOne.status == 'Draft'">
                            <button class="btn btn-primary form-control text-left" @click="deleteTypeContent()">
                                <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить тип
                            </button>
                        </div>
                        <div class="p-2" v-if="typeContentOne.status == 'Published'">
                            <button class="btn btn-primary form-control text-left" @click='openContextMenu($event)'>
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Выпустить новую версию
                            </button>
                        </div>
                        <div class="p-2" v-if="typeContentOne.status == 'Archive'">
                            <button class="btn btn-primary form-control text-left" @click='openContextMenu($event)'>
                                <i class="fa fa-cloud-download fa-lg" aria-hidden="true"></i> Востановить из архива
                            </button>
                        </div>

                        <context-menu :display="showContextMenu" ref="menu">
                            <ul>
                                <div>
                                    <button class="btn btn-primary form-control text-left btn-sm" @click='createNewVersion("major")'>
                                        <i class="fa fa-refresh" aria-hidden="true"></i> Версия первого порядка (x+1.0)
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-primary form-control text-left btn-sm" @click='createNewVersion("minor")'>
                                        <i class="fa fa-refresh" aria-hidden="true"></i> Версия второго порядка (x.y+1)
                                    </button>
                                </div>
                            </ul>
                        </context-menu>

                        <div class="p-2" v-if="typeContentOne.status == 'Published'">
                            <button class="btn btn-primary form-control text-left" @click="newVersionTypeContent()">
                                <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Отправить в архив
                            </button>
                        </div>
                        <hr>
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
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import Viewmodal from "./Viewmodal";
    import Editmodal from "./Editmodal";
    import {mapActions, mapGetters} from "vuex";
    import ContextMenu from "../helpers/ContextMenu.vue"
    import Onetype from './Onetype.vue';

    export default {
        name: "Viewtype",
        components: {draggable, Viewmodal, Editmodal, ContextMenu, Onetype},
        data() {
            return {
                showContextMenu: false,
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
                    {
                        class: "fa fa-check-square-o fa-lg",
                        name: "Чек-лист",
                        type: "checkbox",
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
            openContextMenu(event) {
                this.$refs.menu.open(event);
            },
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
            async publishTypeContent(){
                await axios.post('http://127.0.0.1:8000/type-content/publish-type-content', {id: this.type_content_id})
                .then(response => {
                    if (response.status === 200){
                        this.flashMessage.success({
                            message: 'Тип контента опубликован',
                            time: 3000,
                        });
                        this.getTypeContentOne(this.type_content_id);
                    }
                })
                .catch(error => {
                    console.log(error);
                })
            },

            async deleteTypeContent(){
                await axios.delete('http://127.0.0.1:8000/type-content/'+ this.type_content_id)
                .then(response => {
                    if (response.status === 200){
                        this.flashMessage.success({
                            message: 'Тип контента удален, Вы будете перенаправлены на страницу со списком типов контента через 3 секунды',
                            time: 3000,
                        });
                        window.setTimeout(function(){
                            window.location.href = "http://127.0.0.1:8000/type-content/index"
                        }, 3000);
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
            async createNewVersion(version){
                await axios.get('http://127.0.0.1:8000/type-content/new-version/'+ this.type_content_id + '/' + version)
                .then(response => {
                    if (response.status === 200){
                        this.flashMessage.success({
                            message: 'Новая версия создана',
                            time: 3000,
                        });
                        //$('._vue-flash-msg-body__text').append('<a href="http://127.0.0.1:8000/type-content/view-new/'+response.data.id+'">Ссылка</a>');
                    }
                })
                .catch(error => {
                    this.flashMessage.error({
                        message: error.response.data.errors.version_error,
                        time: 3000,
                    });
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
