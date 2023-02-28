<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Редактирование типа контента</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="$emit('close-modal', 'editTypeContent')">&times;</span>
            </button>
        </div>
        <form @submit.prevent="updateTypeContent()">
        <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="owner"><b>Владелец типа контента:</b></label>
                        <select id="owner" class="form-control" v-model="form_edit.owner">
                            <option disabled selected value> -- Выберите пользователя -- </option>
                            <option v-for="(user, index) in users" :key="index" :value="user.id">
                                <i aria-hidden="true">{{user.name}}</i>
                            </option>
                        </select>
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="vv.icon">
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code">
                                <i :class="'fa ' + icon.code+ ' fa-lg'" aria-hidden="true">{{icon.name}}</i>
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b class="text-danger">*</b><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form_edit.name"
                               :class="{invalid: ($v.vv.name.$dirty && !$v.vv.name.required)}">
                            <small class="helper-text invalid" v-if="$v.vv.name.$dirty && !$v.vv.name.required">
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="apiUrl"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="apiUrl" class="form-control" type="text" v-model="form_edit.apiUrl"
                               :class="{invalid: ($v.vv.apiUrl.$dirty && !$v.vv.apiUrl.required)}">
                        <small class="helper-text invalid" v-if="$v.vv.apiUrl.$dirty && !$v.vv.apiUrl.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="activeFrom"><b>Период действия с:</b></label>
                        <datepicker
                            :id="form_edit.activeFrom"
                            v-model="form_edit.activeFrom"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>

                    <div class="block col-6">
                        <label for="activeAfter"><b>Период действия по:</b></label>
                         <datepicker
                            :id="form_edit.activeAfter"
                            v-model="form_edit.activeAfter"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>
                </div>

                <div class="row">
                    <div class="block col-12">
                        <label for="description"><b>Описание:</b></label>
                        <input autocomplete="off" type="textarea" name="description" v-model="form_edit.description" id="description" class="form-control">
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('close-modal', 'editTypeContent')">Отмена</button>
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
        name: "Edit",
        components: {Datepicker},
        props: ['id', 'owner', 'icon', 'apiUrl', 'status', 'description', 'status', 'activeFrom', 'activeAfter', 'name'],
        data:function(){
            return {
                ru:ru,
                icons:null,
                users:null,
                form_edit:{
                    id:null,
                    owner:null,
                    icon:null,
                    name:null,
                    status:null,
                    apiUrl:null,
                    activeFrom:null,
                    activeAfter:null,
                    description:null,
                }
            }
        },
        computed:{
            vv(){
                this.form_edit.id = this.id
                this.form_edit.owner = this.owner
                this.form_edit.icon = this.icon
                this.form_edit.name = this.name
                this.form_edit.status = this.status
                this.form_edit.apiUrl = this.apiUrl
                this.form_edit.activeFrom = this.activeFrom
                this.form_edit.activeAfter = this.activeAfter
                this.form_edit.description = this.description
                return this.form_edit;
            }
        },

        methods: {
            ...mapActions(['update']),
            async getIcons() {
                axios.get('http://127.0.0.1:8000/type-content/icons')
                    .then(response => {
                        this.icons = response.data;
                    });
            },
            async updateTypeContent() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    console.log('Form not subm')
                } else {
                    console.log(123);
                    this.update({id: this.form_edit.id, owner: this.form_edit.owner, icon: this.form_edit.icon, name: this.form_edit.name, apiUrl: this.form_edit.apiUrl,
                        activeFrom: this.form_edit.activeFrom, activeAfter: this.form_edit.activeAfter, description: this.form_edit.description, status: this.form_edit.status}
                    ).then(response => {
                        this.$emit('close-modal');
                        this.flashMessage.success({
                            message: 'Тип контента отредактирован',
                            time: 3000,
                        });
                    }).catch(errors => {
                        console.log(errors);
                    });
                }
            },
            generateUrl(){
                this.form_edit.apiUrl =  url_slug(this.form_edit.name)
            },
            getUsers(){
                axios.get('http://127.0.0.1:8000/users-list')
                    .then(response => {
                        this.users = response.data;
                    });
            },
        },
        async created(){
            this.getIcons();
            this.getUsers();

        },
        validations: {
            vv:{
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
