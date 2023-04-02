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
                        <select id="owner" class="form-control" v-model="value.owner">
                            <option disabled selected value> -- Выберите пользователя -- </option>
                            <option v-for="(user, index) in users" :key="index" :value="user.id">
                                <i aria-hidden="true">{{user.name}}</i>
                            </option>
                        </select>
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="value.icon">
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code">
                                <i :class="'fa ' + icon.code+ ' fa-lg'" aria-hidden="true">{{icon.name}}</i>
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b class="text-danger">*</b><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="value.name"
                               :class="{invalid: ($v.value.name.$dirty && !$v.value.name.required)}">
                            <small class="helper-text invalid" v-if="$v.value.name.$dirty && !$v.value.name.required">
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="apiUrl"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="apiUrl" class="form-control" type="text" v-model="value.apiUrl"
                               :class="{invalid: ($v.value.apiUrl.$dirty && !$v.value.apiUrl.required)}">
                        <small class="helper-text invalid" v-if="$v.value.apiUrl.$dirty && !$v.value.apiUrl.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="activeFrom"><b>Период действия с:</b></label>
                        <datepicker
                            :id="value.activeFrom"
                            v-model="value.activeFrom"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>

                    <div class="block col-6">
                        <label for="activeAfter"><b>Период действия по:</b></label>
                         <datepicker
                            :id="value.activeAfter"
                            v-model="value.activeAfter"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>
                </div>

                <div class="row">
                    <div class="block col-12">
                        <label for="description"><b>Описание:</b></label>
                        <input autocomplete="off" type="textarea" name="description" v-model="value.description" id="description" class="form-control">
                    </div>
                </div>
{{value}}
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
        props: {
            value: {
                required: true,
            },
        },
        data:function(){
            return {
                ru:ru,
                icons:null,
                users:null,
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
                    this.update({id: this.value.id, owner: this.value.owner, icon: this.value.icon, name: this.value.name, apiUrl: this.value.apiUrl,
                        activeFrom: this.value.activeFrom, activeAfter: this.value.activeAfter, description: this.value.description, status: this.value.status}
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
                this.value.apiUrl =  url_slug(this.value.name)
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
            value:{
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
