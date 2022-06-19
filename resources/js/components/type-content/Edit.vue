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
                        <input autocomplete="off" id="owner" class="form-control" type="text" v-model="form_edit.owner">
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
                        <label for="name"><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form_edit.name"
                               :class="{invalid: ($v.vv.name.$dirty && !$v.vv.name.required)}">
                            <small class="helper-text invalid" v-if="$v.vv.name.$dirty && !$v.vv.name.required">
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form_edit.api_url"
                               :class="{invalid: ($v.vv.api_url.$dirty && !$v.vv.api_url.required)}">
                        <small class="helper-text invalid" v-if="$v.vv.api_url.$dirty && !$v.vv.api_url.required">
                            Необходимо заполнить «API URL».
                        </small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <input autocomplete="off" type="text" name="active_from" v-model="form_edit.active_from" id="active_from" class="form-control datepicker-here">
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <input  autocomplete="off" type="text" name="active_after" v-model="form_edit.active_after" id="active_after" class="form-control datepicker-here">
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
    export default {
        name: "Edit",
        props: ['id', 'owner', 'icon', 'api_url', 'status', 'description', 'status', 'active_from', 'active_after', 'name'],
        data:function(){
            return {
                icons:null,
                form_edit:{
                    id:null,
                    owner:null,
                    icon:null,
                    name:null,
                    status:null,
                    api_url:null,
                    active_from:null,
                    active_after:null,
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
                this.form_edit.api_url = this.api_url
                this.form_edit.active_from = this.active_from
                this.form_edit.active_after = this.active_after
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
                    this.update({form: this.form_edit, id: this.form_edit.id}
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
        },
        async created(){
            this.getIcons();
            
        },
        validations: {
            vv:{
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