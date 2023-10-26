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
                        <select id="owner" class="form-control" v-model="localValue.owner">
                            <option disabled selected value> -- Выберите пользователя -- </option>
                            <option v-for="(user, index) in users" :key="index" :value="user.id">
                                <i aria-hidden="true">{{user.name}}</i>
                            </option>
                        </select>
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="localValue.icon">
                            <option disabled selected value> -- Выберите иконку -- </option>
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code" v-html="generateIcon(icon)"></option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b class="text-danger">*</b><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="localValue.name"
                               :class="{invalid: ($v.localValue.name.$dirty && !$v.localValue.name.required)}">
                            <small class="helper-text invalid" v-if="$v.localValue.name.$dirty && !$v.localValue.name.required">
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="apiUrl"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="apiUrl" class="form-control" type="text" v-model="localValue.apiUrl"
                               :class="{invalid: ($v.localValue.apiUrl.$dirty && !$v.localValue.apiUrl.required)}">
                        <small class="helper-text invalid" v-if="$v.localValue.apiUrl.$dirty && !$v.localValue.apiUrl.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="activeFrom"><b>Период действия с:</b></label>
                        <input type="date"
                               :id="localValue.activeFrom"
                               :value="localValue.activeFrom"
                               @input="localValue.activeFrom = $event.target.valueAsDate"
                               class="form-control">
                    </div>

                    <div class="block col-6">
                        <label for="activeAfter"><b>Период действия по:</b></label>
                        <input type="date"
                               :id="localValue.activeAfter"
                               :value="localValue.activeAfter"
                               @input="localValue.activeAfter = $event.target.valueAsDate"
                               class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="block col-12">
                        <label for="description"><b>Описание:</b></label>
                        <input autocomplete="off" type="textarea" name="description" v-model="localValue.description" id="description" class="form-control">
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
        computed: {
            localValue() {
                return Object.assign({}, this.value);
            }
        },
        methods: {
            ...mapActions(['update']),
            async getIcons() {
                axios.get('/type-content/icons')
                    .then(response => {
                        this.icons = response.data;
                    });
            },
            generateIcon(icon){
                return '&#x' + icon.unicode + '; ' + icon.name;
            },
            async updateTypeContent() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    console.log('Form not subm')
                } else {
                    console.log(123);
                    this.update({id: this.localValue.id, owner: this.localValue.owner, icon: this.localValue.icon, name: this.localValue.name, apiUrl: this.localValue.apiUrl,
                        activeFrom: this.localValue.activeFrom, activeAfter: this.localValue.activeAfter, description: this.localValue.description, status: this.localValue.status}
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
                let v = this.localValue.name;
                console.log('generate url', this.value.apiUrl);
                this.value.apiUrl =  url_slug(this.value.name)
            },
            getUsers(){
                axios.get('/users-list')
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
            localValue:{
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
