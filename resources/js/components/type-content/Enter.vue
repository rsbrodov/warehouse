import draggable from "vuedraggable";
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
    <div class="container-fluid">
        <OneElement />
        <hr>
        <br>
        <div class="row ">
            <FlashMessage :position="'right bottom'" style='z-index:20001;'></FlashMessage>
            <div class="col-10 left-block">
                <div class="left-block__layout">
                    <div class="rower">
                        <div class="row-block" v-for="(row, row_index) in body" :key="row_index">
                            <div class="columns mt-4" v-for="(column, column_index) in row" :key="column_index">
                                <div class="block mt-4" v-for="(element, element_index) in column" :key="element_index">
                                    <label :for="element.name"><span class="text-danger" v-if="element.required == 1">*</span>{{element.title}}</label>
                                    <input autocomplete="off"
                                           v-model="elementBody[element.uid].value"
                                           :type="element.type"
                                           class="form-control"
                                           :disabled="elementContentOne.status != 'Draft'"
                                           :id="element.title"
                                           :name="element.name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-2">
                <div class="d-flex flex-column">
                    <div class="p-2" v-if="elementContentOne.status == 'Draft'">
                        <button class="btn btn-primary form-control text-left" @click="saveBody()">
                            <i class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить черновик
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Draft'">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация контента
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Draft'">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить контента
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Published'">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Выпустить новую версию
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Archive'">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-cloud-download fa-lg" aria-hidden="true"></i> Востановить из архива
                        </button>
                    </div>

                    <context-menu :display="showContextMenu" ref="menu">
                        <ul>
                            <div>
                                <button class="btn btn-primary form-control text-left btn-sm">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Версия первого порядка (x+1.0)
                                </button>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary form-control text-left btn-sm">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Версия второго порядка (x.y+1)
                                </button>
                            </div>
                        </ul>
                    </context-menu>

                    <div class="p-2" v-if="elementContentOne.status == 'Published'">
                        <button class="btn btn-primary form-control text-left" >
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Отправить в архив
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import OneElement from "./OneElement";
import ContextMenu from "../helpers/ContextMenu";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "Enter",
    components: { OneElement, ContextMenu },
    data() {
        return {
            showContextMenu: false,
            element_content_id: window.location.href.split('/')[5],
            url: window.location.href.split('/')[4],
            body: undefined,
            elementBody: {/*
                '140495fc70d32': {value: 123},
                '9cd029b455c85': {value: 1232},
                'a947a5b1732e8': {value: 1232},
                '92ddfd3cebdd9': {value: 1232},
                '605bf1d11e13a': {value: 1232},
                '11a5fbda5cbbc': {value: 1232},
                'a5efb03a1b641': {value: null},*/
            },
        };
    },
    computed: {
        ...mapGetters(['elementContentOne']),
    },

    methods: {
        ...mapActions(['getElementContentOne']),
        openContextMenu(event) {
            this.$refs.menu.open(event);
        },
        async getBodyTypeContent() {
            await axios.get('http://127.0.0.1:8000/type-content/get-body/' + this.elementContentOne.type_contents.id)
                .then(response => {
                    if (Object.keys(response.data).length === 0) {
                        this.body = [[]];
                    } else {
                        this.body = response.data
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async getValueElementContent() {
            await axios.get('http://127.0.0.1:8000/type-content/get-body-element-content/' + this.elementContentOne.id)
                .then(response => {
                    if (Object.keys(response.data).length === 0) {
                        this.elementBody = [[]];
                    } else {
                        this.elementBody = response.data
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        saveBody() {
            axios.post('http://127.0.0.1:8000/type-content/save-body-element', { id: this.element_content_id, body: this.elementBody })
                .then(response => {
                    if (response.status === 200) {
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
    },

    async created() {
        await this.getElementContentOne(this.element_content_id);
        await this.getBodyTypeContent();
        await this.getValueElementContent();
    },


}
</script>

<style scoped>
.left-block {
    z-index: 1;
    background-color: #ededed;
    border:1px solid #E1E1E1

}
/*строка*/
.left-block__layout {
    background-color: white;
    min-height: 110px;
    max-height: 11000px;
    margin: 10px;

}
.rower{
    padding: 10px;
}

.row-block{
    display: flex;
    flex-wrap: nowrap;
}
.columns {
    flex: 1;
    margin: 0 5px;
    border: 1px solid #E1E1E1;
    border-radius: 5px;
}
.block {
    margin: 5px;
}
</style>
