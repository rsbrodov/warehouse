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
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.api_url"
                               :class="{invalid: ($v.form.api_url.$dirty && !$v.form.api_url.required)}">
                        <small class="helper-text invalid" v-if="$v.form.api_url.$dirty && !$v.form.api_url.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <datepicker
                            :id="form.active_from"
                            v-model="form.active_from"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <datepicker
                            :id="form.active_after"
                            v-model="form.active_after"
                            :language="ru"
                            class="form-control">
                        </datepicker>
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
                users:null,
                ru:ru,
                form:{
                    icon:'',
                    name:'',
                    owner:'',
                    api_url:'',
                    active_from:'',
                    active_after:'',
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
                        icon: this.form.icon, name: this.form.name, owner: this.form.owner, api_url: this.form.api_url, 
                        active_from: this.form.active_from, active_after: this.form.active_after, description: this.form.description
                    }).then(response => {
                        this.$emit('close-modal');
                        this.form.icon = ''; this.form.name = ''; this.form.owner = ''; this.form.api_url = ''; this.form.active_from = ''; this.form.active_after = ''; this.form.description = '';
                        this.flashMessage.success({
                            message: 'Тик контента успешно создан',
                            time: 3000,
                        });
                    }).catch(errors => {
                        console.log(errors);
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
                this.form.api_url =  url_slug(this.form.name)   
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
