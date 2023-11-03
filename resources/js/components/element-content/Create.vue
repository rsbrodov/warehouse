<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Создание элемента контента</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
            </button>
        </div>
        <form @submit.prevent="saveElementContent()">
        <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="label"><b class="text-danger">*</b><b>Заголовок</b></label>
                        <input autocomplete="off" id="label" class="form-control" type="text" v-model="form.label"
                               :class="{invalid: ($v.form.label.$dirty && !$v.form.label.required)}">
                            <small class="helper-text invalid" v-if="$v.form.label.$dirty && !$v.form.label.required">
                                Необходимо заполнить «Заголовок».<br>
                            </small>
                            <small class="helper-text invalid" v-if="errors.label">
                            {{errors.label}}<br>
                        </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.api_url"
                               :class="{invalid: ($v.form.api_url.$dirty && !$v.form.api_url.required)}">
                        <small class="helper-text invalid" v-if="$v.form.api_url.$dirty && !$v.form.api_url.required">
                            Необходимо заполнить «API URL».<br>
                        </small>
                        <small class="helper-text invalid" v-if="errors.api_url">
                            {{errors.api_url}}<br>
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <date-picker v-model="form.active_from"
                                     :lang="lang"
                                     :format="'DD.MM.YYYY'"
                                     :valueType="'format'"/>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <date-picker v-model="form.active_after"
                                     :lang="lang"
                                     :format="'DD.MM.YYYY'"
                                     :valueType="'format'"/>
                    </div>
                </div>

                <div class="row">
                    <div class="block col-12">
                        <label for="description"><b>Описание:</b></label>
                        <input autocomplete="off" type="textarea" name="description" v-model="form.description" id="description" class="form-control">
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('close-modal')">Отмена</button>
            <button id="add" type="submit" class="btn btn-primary">ОК</button>
        </div>
        </form>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import {required} from "vuelidate/lib/validators";
    import { url_slug } from 'cyrillic-slug'
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';
    export default {
        name: "Create",
         components: {DatePicker},
        data:function(){
            return {
                errors:{},
                type_content_id: window.location.href.split('/').slice(-1)[0],
                form:{
                    label:'',
                    api_url:'',
                    active_from:'',
                    active_after:'',
                    description:'',
                },
                lang: {
                    formatLocale: {
                        firstDayOfWeek: 1,
                    },
                    monthBeforeYear: false,
                },
            }
        },

        methods: {
            ...mapActions(['newElementContents']),
            async saveElementContent() {
                this.$v.form.$touch();
                if(this.$v.form.$invalid){
                    console.log('Form not subm')
                }else {
                    this.newElementContents({
                        label: this.form.label, api_url: this.form.api_url,
                        active_from: this.form.active_from, active_after: this.form.active_after, description: this.form.description, type_content_id: this.type_content_id, status: 'DRAFT'
                    }).then(response => {
                        this.$emit('get-element-content-by-url');
                        this.$emit('close-modal');
                        this.form.icon = ''; this.form.name = ''; this.form.owner = ''; this.form.api_url = ''; this.form.active_from = ''; this.form.active_after = ''; this.form.description = '';
                        this.flashMessage.success({
                            message: 'Элемент контента успешно создан',
                            time: 3000,
                        });
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
            generateUrl(){
                this.form.api_url =  url_slug(this.form.label)
            },
        },
        async created(){

        },
        validations: {
            form:{
                label: {required},
                api_url: {required},
            }
        },
    }
</script>

<style>
    select{
        font-family: fontAwesome
    }
    .invalid {
        border-color: red;
        color: red;
    }
    small .invalid {
        color: red;
    }
</style>
