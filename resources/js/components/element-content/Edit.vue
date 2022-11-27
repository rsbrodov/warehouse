<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Редактирование элемента контента</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="$emit('close-modal', 'editElementContent')">&times;</span>
            </button>
        </div>
        <form @submit.prevent="update()">
        <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b class="text-danger">*</b><b>Заголовок</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form_edit.label"
                               :class="{invalid: ($v.vv.label.$dirty && !$v.vv.label.required)}">
                            <small class="helper-text invalid" v-if="$v.vv.label.$dirty && !$v.vv.label.required">
                                Необходимо заполнить «Заголовок».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form_edit.api_url"
                               :class="{invalid: ($v.vv.api_url.$dirty && !$v.vv.api_url.required)}">
                        <small class="helper-text invalid" v-if="$v.vv.api_url.$dirty && !$v.vv.api_url.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <datepicker
                            :id="form_edit.active_from"
                            v-model="form_edit.active_from"
                            :language="ru"
                            class="form-control">
                        </datepicker>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                         <datepicker
                            :id="form_edit.active_after"
                            v-model="form_edit.active_after"
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
            <button type="button" class="btn btn-secondary" @click="$emit('close-modal', 'editElementContent')">Отмена</button>
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
        props: ['id', 'api_url', 'status', 'description', 'status', 'active_from', 'active_after', 'label', 'type_content_id'],
        data:function(){
            return {
                ru:ru,
                form_edit:{
                    id:null,
                    label:null,
                    status:null,
                    api_url:null,
                    active_from:null,
                    active_after:null,
                    description:null,
                    type_content_id:null,
                }
            }
        },
        computed:{
            vv(){
                this.form_edit.id = this.id
                this.form_edit.label = this.label
                this.form_edit.api_url = this.api_url
                this.form_edit.active_from = this.active_from
                this.form_edit.active_after = this.active_after
                this.form_edit.description = this.description
                this.form_edit.status = this.status
                this.form_edit.type_content_id = this.type_content_id
                return this.form_edit;
            }
        },

        methods: {
            ...mapActions(['updateElementContent']),
            async update() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                } else {
                    this.updateElementContent({id: this.form_edit.id, label: this.form_edit.label, api_url: this.form_edit.api_url, active_from: this.form_edit.active_from,
                        active_after: this.form_edit.active_after, description: this.form_edit.description, status: this.form_edit.status, type_content_id: this.form_edit.type_content_id}
                    ).then(response => {
                        this.$emit('close-modal');
                        this.flashMessage.success({
                            message: 'Элемент контента отредактирован',
                            time: 3000,
                        });
                    }).catch(errors => {
                        console.log(errors);
                    });
                }
            },
            generateUrl(){
                this.form_edit.api_url =  url_slug(this.form_edit.name)
            },
        },
        validations: {
            vv:{
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
