<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Создание типа контента</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
            </button>
        </div>
        <form @submit.prevent="saveTypeContent()">
        <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="owner"><b>Владелец типа контента:</b></label>
                        <select id="owner" class="form-control" v-model="form.owner">
                            <option disabled selected value> -- Выберите пользователя -- </option>
                            <option v-for="(user, index) in users" :key="index" :value="user.id">
                                <i aria-hidden="true">{{user.name}}</i>
                            </option>
                        </select>
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="form.icon">
                            <option disabled selected value> -- Выберите иконку -- </option>
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code" v-html="generateIcon(icon)"></option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b class="text-danger">*</b><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name"
                               :class="{invalid: ($v.form.name.$dirty && !$v.form.name.required)}">
                            <small class="helper-text invalid" v-if="$v.form.name.$dirty && !$v.form.name.required">
                                Необходимо заполнить «Наименование».<br>
                            </small>
                            <small class="helper-text invalid" v-if="errors.name">
                            {{errors.name}}<br>
                        </small>
                    </div>

                    <div class="block col-6">
                        <label for="apiUrl"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="apiUrl" class="form-control" type="text" v-model="form.apiUrl"
                               :class="{invalid: ($v.form.apiUrl.$dirty && !$v.form.apiUrl.required)}">
                        <small class="helper-text invalid" v-if="$v.form.apiUrl.$dirty && !$v.form.apiUrl.required">
                            Необходимо заполнить «API URL».<br>
                        </small>
                        <small class="helper-text invalid" v-if="errors.apiUrl">
                            {{errors.apiUrl}}<br>
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="activeFrom"><b>Период действия с:</b></label>
                        <date-picker v-model="form.activeFrom"
                                     :lang="lang"
                                     :format="'DD.MM.YYYY'"
                                     :valueType="'format'"/>
                    </div>

                    <div class="block col-6">
                        <label for="activeAfter"><b>Период действия по:</b></label>
                        <date-picker v-model="form.activeAfter"
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
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';
    import { url_slug } from 'cyrillic-slug'
    export default {
        name: "Create",
         components: {DatePicker},
        data:function(){
            return {
                icons:null,
                errors:{},
                users:null,
                form:{
                    icon:'',
                    name:'',
                    owner:'',
                    apiUrl:'',
                    activeFrom:'',
                    activeAfter:'',
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
            ...mapActions(['newTypeContents']),
            generateIcon(icon){
                return '&#x' + icon.unicode + '; ' + icon.name;
            },
            async saveTypeContent() {
                this.$v.form.$touch();
                if(this.$v.form.$invalid){
                    console.log('Form not subm')
                }else {
                    this.newTypeContents({
                        icon: this.form.icon, name: this.form.name, owner: this.form.owner, apiUrl: this.form.apiUrl,
                        activeFrom: this.form.activeFrom, activeAfter: this.form.activeAfter, description: this.form.description, status: 'DRAFT'
                    }).then(response => {
                        console.log(1111111111111);
                        this.$emit('get-type-content-by-url');
                        this.$emit('close-modal');
                        this.form.icon = ''; this.form.name = ''; this.form.owner = ''; this.form.apiUrl = ''; this.form.activeFrom = ''; this.form.activeAfter = ''; this.form.description = '';
                        this.$v.$reset()
                        this.flashMessage.success({
                            message: 'Тик контента успешно создан',
                            time: 3000,
                        });
                    }).catch(error => {
                        console.log('ошибуля');
                        console.log(error);
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }else{
                            this.flashMessage.error({
                                message: 'Ошибка создания типа контента',
                                time: 3000,
                            });
                        }
                    });
                }
            },
            async getIcons() {
                axios.get('/type-content/icons')
                    .then(response => {
                        this.icons = response.data;
                    });
            },
            generateUrl(){
                this.form.apiUrl =  url_slug(this.form.name)
            },
            getUsers(){
                axios.get('/users-list')
                    .then(response => {
                        this.users = response.data;
                    });
            }
        },
        async created(){
            this.getIcons();
            this.getUsers();
        },
        validations: {
            form:{
                name: {required},
                apiUrl: {required},
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
