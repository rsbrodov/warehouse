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
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code">
                                <i :class="'fa ' + icon.code+ ' fa-lg'" aria-hidden="true">{{icon.name}}</i>
                            </option>
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
                        <input type="date"
                               :id="form.activeFrom"
                               v-model="form.activeFrom"
                               class="form-control">
                    </div>

                    <div class="block col-6">
                        <label for="activeAfter"><b>Период действия по:</b></label>
                        <input type="date"
                               :id="form.activeAfter"
                               v-model="form.activeAfter"
                               class="form-control">
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
    import Datepicker from 'vuejs-datepicker';
    import {ru} from 'vuejs-datepicker/dist/locale'
    import { url_slug } from 'cyrillic-slug'
    export default {
        name: "Create",
         components: {Datepicker},
        data:function(){
            return {
                icons:null,
                errors:{},
                users:null,
                ru:ru,
                form:{
                    icon:'',
                    name:'',
                    owner:'',
                    apiUrl:'',
                    activeFrom:'',
                    activeAfter:'',
                    description:'',
                }
            }
        },

        methods: {
            ...mapActions(['newTypeContents']),
            async saveTypeContent() {
                this.$v.form.$touch();
                if(this.$v.form.$invalid){
                    console.log('Form not subm')
                }else {
                    this.newTypeContents({
                        icon: this.form.icon, name: this.form.name, owner: this.form.owner, apiUrl: this.form.apiUrl,
                        activeFrom: this.form.activeFrom, activeAfter: this.form.activeAfter, description: this.form.description
                    }).then(response => {
                        this.$emit('close-modal');
                        this.form.icon = ''; this.form.name = ''; this.form.owner = ''; this.form.apiUrl = ''; this.form.activeFrom = ''; this.form.activeAfter = ''; this.form.description = '';
                        this.flashMessage.success({
                            message: 'Тик контента успешно создан',
                            time: 3000,
                        });
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
            async getIcons() {
                axios.get('http://127.0.0.1:8000/type-content/icons')
                    .then(response => {
                        this.icons = response.data;
                    });
            },
            generateUrl(){
                this.form.apiUrl =  url_slug(this.form.name)
            },
            getUsers(){
                axios.get('http://127.0.0.1:8000/users-list')
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
