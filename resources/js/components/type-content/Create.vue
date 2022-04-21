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
                        <input autocomplete="off" id="owner" class="form-control" type="text" v-model="form.owner">
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="form.icon">
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.code">
                                <i :class="'fa ' + icon.code+ ' fa-lg'" aria-hidden="true">{{icon.name}}</i>
<!--                                Github &#xf09b;-->
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name"
                               :class="{invalid: ($v.form.name.$dirty && !$v.form.name.required)}">
                            <small class="helper-text invalid" v-if="$v.form.name.$dirty && !$v.form.name.required">
                                Необходимо заполнить «Наименование».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.api_url"
                               :class="{invalid: ($v.form.api_url.$dirty && !$v.form.api_url.required)}">
                        <small class="helper-text invalid" v-if="$v.form.api_url.$dirty && !$v.form.api_url.required">
                            Необходимо заполнить «API URL».
                        </small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <input autocomplete="off" type="text" name="active_from" v-model="form.active_from" id="active_from" class="form-control datepicker-here">
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <input  autocomplete="off" type="text" name="active_after" v-model="form.active_after" id="active_after" class="form-control datepicker-here">
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
    export default {
        name: "Create",

        data:function(){
            return {
                icons:null,
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
                        form: this.form
                    }).then(response => {
                        this.$emit('close-modal');
                        this.form.icon = ''; this.form.name = ''; this.form.owner = ''; this.form.api_url = ''; this.form.active_from = ''; this.form.active_after = ''; this.form.description = '';
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
        },
        async created(){
            this.getIcons();
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
