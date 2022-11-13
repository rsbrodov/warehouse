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
                                    <div class="label">
                                        <label :for="element.name"><span class="text-danger" v-if="element.required == 1">*</span>{{element.title}}</label>
                                    </div>
                                    <input v-if="element.type == 'text'"
                                           autocomplete="off"
                                           v-model="elementBody[element.uid].value"
                                           :type="element.type"
                                           class="form-control"
                                           :disabled="elementContentOne.status != 'Draft'"
                                           :id="element.title"
                                           :name="element.name"
                                           :class="{invalid: (errors[element.uid])}">
                                    <textarea v-else-if="element.type == 'textarea'"
                                           autocomplete="off"
                                           v-model="elementBody[element.uid].value"
                                           :type="element.type"
                                           class="form-control"
                                           :disabled="elementContentOne.status != 'Draft'"
                                           :id="element.title"
                                           :name="element.name"
                                           :class="{invalid: (errors[element.uid])}"
                                           rows="5" />
                                    <select v-else-if="element.type == 'select'"
                                            :id="element.uid"
                                            :disabled="elementContentOne.status != 'Draft'"
                                            class="form-control"
                                            :class="{invalid: (errors[element.uid])}"
                                            v-model="elementBody[element.uid].value">
                                        <option disabled selected value> -- Выберите вариант -- </option>
                                        <option v-for="(dropdown, index) in dropdownList[element.uid]" :key="index" :value="index">{{dropdown}}</option>
                                    </select>
                                    <div v-else-if="element.type == 'radio'">
                                        <span v-for="(dropdown, index) in dropdownList[element.uid]" :key="index">
                                            <input type="radio"
                                                   :value="index"
                                                   v-model="elementBody[element.uid].value"
                                                   :class="{invalid: (errors[element.uid])}">
                                            <label class="mr-2">{{dropdown}}</label>
                                        </span>
                                    </div>
                                    <datepicker v-else-if="element.type == 'datetime'"
                                                :id="element.uid"
                                                :disabled="elementContentOne.status != 'Draft'"
                                                class="form-control"
                                                :class="{invalid: (errors[element.uid])}"
                                                v-model="elementBody[element.uid].value"
                                                :language="ru">
                                    </datepicker>
                                    <div v-else-if="element.type == 'file'"
                                         class="block-img"  >
                                        <img :id="element.uid"
                                             src="https://zor.com/de/media/catalog/product/cache/3/image/9df78eab33525d08d6e5fb8d27136e95/p/l/placeholder_big_3.jpg"
                                             class="p-2"
                                             style="max-height: 320px; width:100%"/>
                                    </div>
                                    <small class="helper-text invalid" v-if="errors[element.uid]">
                                        {{errors[element.uid]}}<br>
                                    </small>
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
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Published', 'Элемент контента опубликован')">
                            <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i> Публикация контента
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Draft' || elementContentOne.status == 'Archive'" @click="changeStatus('Destroy', 'Элемент контента помечен на удаление')">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Пометить на удаление
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Destroy'" >
                        <button class="btn btn-primary form-control text-left" @click="deleteElementContent()">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить элемент контента
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Published'">
                        <button class="btn btn-primary form-control text-left">
                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Выпустить новую версию
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Published'">
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Draft', 'Элемент контента переведен в статус Черновик')">
                            <i class="fa fa-reply-all fa-lg" aria-hidden="true"></i> Депубликация контента
                        </button>
                    </div>

                    <div class="p-2" v-if="elementContentOne.status == 'Publish'">
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Draft', 'Элемент контента восстановлен из архива')">
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
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Archive', 'Элемент контента отправлен в архив')">
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
import Datepicker from 'vuejs-datepicker';
import {ru} from "vuejs-datepicker/dist/locale";

export default {
    name: "Enter",
    components: { OneElement, ContextMenu, Datepicker },
    data() {
        return {
            ru:ru,
            showContextMenu: false,
            element_content_id: window.location.href.split('/')[5],
            url: window.location.href.split('/')[4],
            body: undefined,
            elementBody: {},
            dropdownTemp: [
                {id: 1, value: 'Вариант 1'},
                {id: 2, value: 'Вариант 2'},
                {id: 3, value: 'Вариант 3'},
            ],
            dropdownList: [],
            errors: [],
        };
    },
    computed: {
        ...mapGetters(['elementContentOne']),
    },

    methods: {
        ...mapActions(['getElementContentOne', 'updateElementContent']),
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
                    this.elementBody = response.data


                    axios.get('http://127.0.0.1:8000/select/dropdownlistby/' + this.elementContentOne.type_contents.id)
                        .then(response => {
                            this.dropdownList = response.data
                        })
                        .catch(error => {
                            console.log(error);
                        })


                })
                .catch(error => {
                    console.log(error);
                })
        },
        saveBody() {
            axios.post('http://127.0.0.1:8000/type-content/save-body-element', { id: this.element_content_id, body: this.elementBody })
                .then(response => {
                    if (response.status === 200) {
                        this.errors = [];
                        this.flashMessage.success({
                            message: 'Данные сохранены',
                            time: 3000,
                        });
                    }
                })
                .catch(error => {
                   this.errors = error.response.data.message;
                })
        },
        async deleteElementContent() {
            await axios.delete('http://127.0.0.1:8000/element-content/' + this.element_content_id)
                .then(response => {
                    if (response.status === 200) {
                        this.flashMessage.success({
                            message: 'Элемент контента удален, Вы будете перенаправлены на страницу со списком элементов контента через 3 секунды',
                            time: 3000,
                        });
                        window.setTimeout(function () {
                            window.location.href = "http://127.0.0.1:8000/element-content/'" + this.elementContentOne.type_contents.id
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async changeStatus(status, message) {
            this.updateElementContent({
                    id: this.elementContentOne.id, label: this.elementContentOne.label, api_url: this.elementContentOne.api_url,
                    active_from: this.elementContentOne.active_from, active_after: this.elementContentOne.active_after, description: this.elementContentOne.description, status: status,
                }
            ).then(response => {
                this.$emit('close-modal');
                this.flashMessage.success({
                    message: message,
                    time: 3000,
                });
                this.getElementContentOne(this.element_content_id);
            }).catch(errors => {
                console.log(errors);
            });

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
    /*border: 1px solid #E1E1E1;*/
    /*border-radius: 5px;*/
}
.block {
    margin: 5px;
    flex-wrap: nowrap;
}
.block-img {
    border: 1px solid #E1E1E1;
    border-radius: 5px;
}
</style>
