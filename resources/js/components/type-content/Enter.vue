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
                                    <div v-else-if="element.type == 'file'">
                                        <div class="img-container">
                                            <div class="image-upload-wrap"
                                                :class="elementContentOne.status != 'Draft' ? 'img-hovered' : ''">
                                                <input :id="'file-upload-input'+element.uid"
                                                       class="file-upload-input"
                                                       type="file"
                                                       :disabled="elementContentOne.status != 'Draft'"
                                                       :style="elementContentOne.status != 'Draft' ? 'cursor:not-allowed' : ''"
                                                       :class="{invalid: (errors[element.uid])}"
                                                       @change="handleFileChange($event, element.uid)"
                                                       accept="image/*"
                                                >
                                                <div id="drag-text">
                                                    <div :id="'drag-text__image'+element.uid" v-show="element.value || checkFileArray(element.uid)">
                                                        <img :src="'/storage/'+element.value" width="300">
                                                    </div>
                                                    <div v-if="!checkFileArray(element.uid) && !element.value && elementContentOne.status == 'Draft'" class="drag-block">
                                                        <button class="btn btn-outline-primary btn-unbordered form-control"
                                                                onclick="$('#file-upload-input'+element.uid).trigger( 'click' )">
                                                            <i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i>
                                                        </button>
                                                        <p v-if="elementContentOne.status == 'Draft'">Перетащите в область или загрузите изображение</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="file-upload-content" v-if="element.value || checkFileArray(element.uid)">
                                                <div v-if="elementContentOne.status == 'Draft'" class="btn-manager-image">
                                                    <button class="file-remove-btn btn btn-outline-danger btn-unbordered form-control text-left" @click="removeUploadImage(element.uid)">
                                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                    <button class="file-upload-btn btn btn-outline-primary btn-unbordered form-control text-left" onclick="$('#file-upload-input'+element.uid).trigger( 'click' )">
                                                        <i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="helper-text invalid" v-if="errors[element.uid]">
                                        {{errors[element.uid]}}<br>
                                    </small>
                                    <div v-else-if="element.type == 'html'">
                                        <ckeditor :editor="editor" v-model="element.value" :config="editorConfig"></ckeditor>
                                    </div>

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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: "Enter",
    components: { OneElement, ContextMenu, Datepicker, ClassicEditor },
    data() {
        return {
            file: [],
            img: null,
            uidElement: '',
            editor: ClassicEditor,
            editorData: '<p><b>Content of the editor.</b></p>',
            editorConfig: {},
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
        };
    },
    computed: {
        ...mapGetters(['elementContentOne']),
    },

    methods: {
        ...mapActions(['getElementContentOne', 'updateElementContent']),
        handleFileChange(event, elementUid){
            this.deleteFileArray(elementUid);
            this.file.push({uid: elementUid, file: event.target.files[0]});
            this.img = event.target.files[0];
            this.uidElement = elementUid;
            console.log('11', this.file);
            var image=URL.createObjectURL(event.target.files[0]);
            var imagediv= document.getElementById('drag-text__image'+elementUid);
            var newimg=document.createElement('img');
            console.log(imagediv);
            imagediv.innerHTML='';
            newimg.src=image;
            newimg.width="300";
            imagediv.appendChild(newimg);
        },
        removeUploadImage(elementUid){
            this.deleteFileArray(elementUid);
            var imagediv= document.getElementById('drag-text__image'+elementUid);
            imagediv.innerHTML='';
            console.log('elementUid', elementUid);
            console.log('elementBody', this.elementBody);
            for (var indexRow = this.elementBody.length - 1; indexRow >= 0; --indexRow) {
                for (var indexColumn = this.elementBody[indexRow].length - 1; indexColumn >= 0; --indexColumn) {
                    for (var indexItem = this.elementBody[indexRow][indexColumn].length - 1; indexItem >= 0; --indexItem) {
                        if(this.elementBody[indexRow][indexColumn][indexItem]['uid'] == elementUid){
                            console.log('indexItemUU', this.elementBody[indexRow][indexColumn][indexItem]);
                            this.elementBody[indexRow][indexColumn][indexItem]['value'] = '';
                        }
                    }
                }
            }
        },
        openContextMenu(event) {
            this.$refs.menu.open(event);
        },
        async getValueElementContent() {
            await axios.get('/type-content/get-body-element-content/' + this.elementContentOne.id)
                .then(response => {
                    this.elementBody = response.data
                })
                .catch(error => {
                    console.log(error);
                })
        },
        saveBody() {
            axios.post('/type-content/save-body-element', { id: this.element_content_id, body: this.elementBody })
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
                });

            if(this.file !== []){
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };
                let data = new FormData();
                data.append('id', this.element_content_id);
                data.append('uidElement', this.uidElement);
                this.file.forEach(i => {
                    data.append('file'+i.uid, i.file);
                });
                data.append('file', this.img);
                console.log('!!!!', this.file);
                axios.post('/upload-image', data, config)
                    .then(response => {
                        if (response.status === 200) {
                            this.errors = [];
                            /*this.flashMessage.success({
                                message: 'Данные сохранены',
                                time: 3000,
                            });*/
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data.message;
                    })
            }
        },
        async deleteElementContent() {
            await axios.delete('/element-content/' + this.element_content_id)
                .then(response => {
                    if (response.status === 204) {
                        this.flashMessage.success({
                            message: 'Элемент контента удален, Вы будете перенаправлены на страницу со списком элементов контента через 3 секунды',
                            time: 3000,
                        });
                        window.setTimeout(function () {
                            window.location.href = "/type-content/index"
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
            await axios.get('/element-content/new-version/' + this.element_content_id + '/' + version)
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
            await axios.get('/element-content/update-fields/' + this.element_content_id)
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
        },
        checkFileArray(uidElement){
            var status = false;
            console.log('checkFileArray', uidElement);
            this.file.forEach((element) => {
                if(element.uid === uidElement){
                    console.log('checkFileArraytrue', uidElement);
                    status = true;
                }
            });
            console.log('checkFileArrayfalse', uidElement);
            return status;
        },
        deleteFileArray(uidElement){
            var status = false;
            this.file.forEach((element) => {
                if(element.uid === uidElement){
                    this.file.splice(element,1);
                }
            });
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






/**/


.file-upload-btn:hover {
    cursor: pointer;
}


.image-upload-wrap {
    margin-top: 20px;
    border: 2px dashed #3fa5d1;
    position: relative;
    min-height: 200px;
    border-radius: 5px;
}

.image-dropping,
.image-upload-wrap:hover {
    background-color: #dbdbdb;
    border:none;
}

.img-hovered{
    background-color: #dbdbdb;
}

.image-title-wrap {
    padding: 0 15px 15px 15px;
    color: #222;
}

#drag-text {
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:200px;

}

#drag-text p {
    text-transform: uppercase;
    color: #5c5c5c;
    font-size: 18px;
}

.file-upload-image {
    margin: auto;
    padding: 20px;

}

.remove-image {
    width: 200px;
    margin: 0;
    color: #fff;
    background: #cd4535;
    border: none;
    padding: 10px;
    border-radius: 4px;
    border-bottom: 4px solid #b02818;
    transition: all .2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
}

.remove-image:hover {
    background: #c13b2a;
    color: #ffffff;
    transition: all .2s ease;
    cursor: pointer;
}

.remove-image:active {
    border: 0;
    transition: all .2s ease;
}

.file-upload-input {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
}

.btn-manager-image{
    display: flex;
    justify-content:center;
    padding:0 5px;
}
.file-upload-btn, .file-remove-btn {
    width: 46px;
    margin: 0 5px;
}


    .img-container{
        border: 1px solid rgba(0, 0, 0, 0.30);
        border-radius: 5px;
        padding:5px;
    }
</style>
