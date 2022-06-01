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
            <!-- Stack the columns on mobile by making one full-width and the other half-width -->
            <div class="row">
                <div class="col"><b><h1>Тип контента 45</h1></b></div>
                <div class="col"></div>
            </div>
            <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
            <div class="row">
                <div class="col-3"><b>Идентификатор:</b> 45-fdf-45-dhg-6</div>
                <div class="col-1"><b>API URL:</b> /products</div>
                <div class="col-1"><b>Владелец:</b> Admin</div>
                <div class="col">
                    <b>Период действия:</b>Не задан
                </div>
                <div class="col-3"><a href="#" class="btn btn-outline-secondary"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></a></div>
            </div>

            <div class="row">
                <div class="col-1">
                    <b>Статус:</b>Черновик
                </div>
                <div class="col-1"><b>Версия:</b> 2.0</div>
            </div>
            <div class="row">
                <div class="col-2"><a href="" class="btn btn-outline-secondary form-control">Состав полей</a></div>
                <div class="col-2"><a href="" class="btn btn-outline-secondary form-control">Доступ</a></div>
                <div class="col-2"><a href=""
                                      class="btn btn-outline-secondary form-control">История изменений</a></div>
            </div>
            <br>
            <br>
            <div class="row ">
                <div class="col-9 left-block">
                    <div class="left-block__draggable-layout mt-2" v-for="(mas, index) in clonedItems" :key="index">
                        <draggable class="left-block__draggable-layout__draggable-parent mt-3 mb-3" v-model="clonedItems[index]" :options="clonedItemOptions">
                            <div class="clickable left-block__draggable-layout__draggable-parent__item mt-2 mb-2" v-for="(item, indexing) in mas" :key="uuid(item)" >
                                <p class="pl-2 pt-3 text-secondary"><i :class="item.class"></i> {{item.title}}</p>
                                <div class="button-group">
                                    <button class="btn btn-outline-secondary mr-2" @click="EditItem(item.uid)"><i class="fa fa-pencil fa-sm"></i></button>
                                    <button class="btn btn-outline-secondary mr-2" @click="deleteItem(index, indexing)"><i class="fa fa-trash fa-sm"></i></button>
                                </div>
                            </div>
                        </draggable>
                    </div>
                </div>

                <div class="col-3">
                    <div class="d-flex flex-column">
                        <div class="p-2"><a href="" class="btn btn-primary form-control text-left"><i
                            class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить черновик</a></div>
                        <div class="p-2"><a href="" class="btn btn-primary form-control text-left"><i
                            class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация типа</a></div>
                        <div class="p-2"><a href="" class="btn btn-primary form-control text-left"><i
                            class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить тип</a></div>
                        <div class="p-2"><a href="" class="btn btn-outline-secondary form-control text-left"><i
                            class="fa fa-bars fa-lg" aria-hidden="true"></i> Добавить строку</a></div>
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

    export default {
        name: "Viewtype",
        components: {draggable, Viewmodal, Editmodal},
        data() {
            return {
                copy: null,
                clonedItems: [
                    /*[
                        {
                            class: "fa fa-code fa-lg",
                            name: "HTML редактор",
                            type: "textarea",
                        },
                    ],*/
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

        methods: {
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
        },

    }
</script>

<style scoped>
    .left-block {
        background-color: #d6d6d6;
    }

    .left-block__draggable-layout {
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80px;
    }
    .left-block__draggable-layout__draggable-parent {
        background-color: #c4c4c4;
        border-radius: 5px;
        min-height: 60px;
        width: 98%;
    }
    .left-block__draggable-layout__draggable-parent__item {
        background-color: white;
        width: 98%;
        border-radius: 5px;
        min-height: 40px;
        margin: 0 auto;
        cursor: grab;
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
</style>
