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
                        <div class="row-block" v-for="(row, row_index) in elementBody" :key="row_index">
                            <div class="columns mt-4" v-for="(column, column_index) in row" :key="column_index">
                                <div class="block mt-4" v-for="(element, element_index) in column" :key="element_index">
                                    <div class="label">
                                        <label :for="element.name"><span class="text-danger" v-if="element.required == 1">*</span>{{element.title}}</label>
                                    </div>
                                    <input v-if="element.type == 'text'"
                                           autocomplete="off"
                                           v-model="element.value"
                                           :type="element.type"
                                           class="form-control"
                                           :disabled="elementContentOne.status != 'Draft'"
                                           :id="element.title"
                                           :name="element.value"
                                           :class="{invalid: (errors[element.uid])}">
                                    <textarea v-else-if="element.type == 'textarea'"
                                           autocomplete="off"
                                           v-model="element.value"
                                           :type="element.type"
                                           class="form-control"
                                           :disabled="elementContentOne.status != 'Draft'"
                                           :id="element.title"
                                           :name="element.value"
                                           :class="{invalid: (errors[element.uid])}"
                                           rows="5" />
                                    <select v-else-if="element.type == 'select'"
                                            :id="element.uid"
                                            :disabled="elementContentOne.status != 'Draft'"
                                            class="form-control"
                                            :class="{invalid: (errors[element.uid])}"
                                            v-model="element.value">
                                        <option disabled selected value> -- Выберите вариант -- </option>
                                        <option v-for="(dropdown, index) in element.parameter" :key="index" :value="dropdown">{{dropdown}}</option>
                                    </select>
                                    <div v-else-if="element.type == 'radio'">
                                        <span v-for="(dropdown, index) in element.parameter" :key="index">
                                            <input type="radio"
                                                   :value="dropdown"
                                                   v-model="element.value"
                                                   :class="{invalid: (errors[element.uid])}">
                                            <label class="mr-2">{{dropdown}}</label>
                                        </span>
                                    </div>
                                    <div v-else-if="element.type == 'checkbox'">
                                        <span v-for="(dropdown, index) in element.parameter" :key="index">
                                            <input type="checkbox"
                                                   :value="dropdown"
                                                   :id="index"
                                                   v-model="element.value[dropdown]"
                                                   :class="{invalid: (errors[element.uid])}">
                                            <label class="mr-2">{{dropdown}}</label>
                                        </span>
                                    </div>
                                    <datepicker v-else-if="element.type == 'datetime'"
                                                :id="element.uid"
                                                :disabled="elementContentOne.status != 'Draft'"
                                                class="form-control"
                                                :class="{invalid: (errors[element.uid])}"
                                                v-model="element.value"
                                                :language="ru">
                                    </datepicker>
                                    <div v-else-if="element.type == 'file'"
                                         class="block-img"  >

                                        <vue-dropzone v-if="!elementContentOne.body"
                                            ref="myVueDropzone"
                                            id="dropzone"
                                            :options="dropzoneOptions"
                                            @vdropzone-complete="afterUploadComplete(element.uid)"></vue-dropzone>
                                        <img v-else
                                             :id="element.uid"
                                             :src="elementBody.value"
                                             class="p-2"
                                             style="width:90%; height: auto"/>
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
                        <button class="btn btn-primary form-control text-left" @click='openContextMenu($event)'>
                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Выпустить новую версию
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Published'">
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Draft', 'Элемент контента переведен в статус Черновик')">
                            <i class="fa fa-reply-all fa-lg" aria-hidden="true"></i> Депубликация контента
                        </button>
                    </div>

                    <div class="p-2" v-if="elementContentOne.status == 'Archive'">
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Draft', 'Элемент контента восстановлен из архива')">
                            <i class="fa fa-cloud-download fa-lg" aria-hidden="true"></i> Востановить из архива
                        </button>
                    </div>

                    <context-menu :display="showContextMenu" ref="menu">
                        <ul>
                            <div>
                                <button class="btn btn-primary form-control text-left btn-sm"
                                        @click='createNewVersion("major")'>
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Версия первого порядка (x+1.0)
                                </button>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary form-control text-left btn-sm"
                                        @click='createNewVersion("minor")'>
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Версия второго порядка (x.y+1)
                                </button>
                            </div>
                        </ul>
                    </context-menu>

                    <div class="p-2" v-if="elementContentOne.status == 'Published' || elementContentOne.status == 'Destroy'">
                        <button class="btn btn-primary form-control text-left" @click="changeStatus('Archive', 'Элемент контента отправлен в архив')">
                            <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Отправить в архив
                        </button>
                    </div>
                    <div class="p-2" v-if="elementContentOne.status == 'Draft'">
                        <button class="btn btn-primary form-control text-left" @click="updateFields()">
                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить состав полей
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
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
    name: "Enter",
    components: { OneElement, ContextMenu, Datepicker, vueDropzone: vue2Dropzone },
    data() {
        return {
            upload_url: null,
            ru:ru,
            showContextMenu: false,
            element_content_id: window.location.href.split('/')[5],
            url: window.location.href.split('/')[4],
            elementBody: undefined,
            dropdownTemp: [
                {id: 1, value: 'Вариант 1'},
                {id: 2, value: 'Вариант 2'},
                {id: 3, value: 'Вариант 3'},
            ],
            errors: [],
            dropzoneOptions: {
                url: BASE_URL + 'upload-image',
                thumbnailWidth: 150,
                maxFilesize: 5,
                headers: { "My-Awesome-Header": "header value" },
                parallelUploads: 1,
                maxFiles: 1,
                //autoProcessQueue: false,//автоматическая отправка на бэк после загрузки
                addRemoveLinks: true,
                init: function() {
                    this.on("success", function(file, responseText) {
                        console.log(responseText);
                        localStorage.setItem('lastUpload', responseText.message)
                        //this.elementBody[element.uid].value
                    });
                }
            }
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
        async getValueElementContent() {
            await axios.get(BASE_URL + 'type-content/get-body-element-content/' + this.elementContentOne.id)
                .then(response => {
                    this.elementBody = response.data
                })
                .catch(error => {
                    console.log(error);
                })
        },
        saveBody() {
            axios.post(BASE_URL + 'type-content/save-body-element', { id: this.element_content_id, body: this.elementBody })
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
            await axios.delete(BASE_URL + 'element-content/' + this.element_content_id)
                .then(response => {
                    if (response.status === 200) {
                        this.flashMessage.success({
                            message: 'Элемент контента удален, Вы будете перенаправлены на страницу со списком элементов контента через 3 секунды',
                            time: 3000,
                        });
                        window.setTimeout(function () {
                            window.location.href = BASE_URL + "type-content/index"
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async changeStatus(status, message) {
            this.updateElementContent({id: this.elementContentOne.id,
                label: this.elementContentOne.label,
                api_url: this.elementContentOne.apiUrl,
                active_from: this.elementContentOne.active_from,
                active_after: this.elementContentOne.active_after,
                description: this.elementContentOne.description,
                status: status,
                type_content_id: this.elementContentOne.type_content_id}
            ).then(response => {
                this.getElementContentOne(this.element_content_id);
                this.flashMessage.success({
                    message: message,
                    time: 3000,
                });
            }).catch(errors => {
                console.log(errors);
            });

        },
        async afterUploadComplete(response) {
            this.elementBody[response].value = localStorage.getItem('lastUpload');
        },

        async createNewVersion(version) {
            await axios.get(BASE_URL + 'element-content/new-version/' + this.element_content_id + '/' + version)
                .then(response => {
                    if (response.status === 200) {
                        this.flashMessage.success({
                            message: 'Новая версия создана. Перейдите в раздел История изменений',
                            time: 3000,
                        });
                        this.$refs.menu.close();
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
        async updateFields() {
            await axios.get(BASE_URL + 'element-content/update-fields/' + this.element_content_id)
                .then(response => {
                    if (response.status === 200) {
                        this.flashMessage.success({
                            message: 'Состав полей обновлен.',
                            time: 3000,
                        });
                        this.getValueElementContent();
                    }
                })
                .catch(error => {
                    this.flashMessage.error({
                        message: 'Возникла ошибка обновления',
                        time: 3000,
                    });
                })
        }
    },

    async created() {
        await this.getElementContentOne(this.element_content_id);
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
    text-align: center;
}
</style>
