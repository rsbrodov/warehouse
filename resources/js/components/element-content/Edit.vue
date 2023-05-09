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
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="localValue.label"
                               :class="{invalid: ($v.localValue.label.$dirty && !$v.localValue.label.required)}">
                            <small class="helper-text invalid" v-if="$v.localValue.label.$dirty && !$v.localValue.label.required">
                                Необходимо заполнить «Заголовок».
                            </small>
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b class="text-danger">*</b><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="localValue.apiUrl"
                               :class="{invalid: ($v.localValue.apiUrl.$dirty && !$v.localValue.apiUrl.required)}">
                        <small class="helper-text invalid" v-if="$v.localValue.apiUrl.$dirty && !$v.localValue.apiUrl.required">
                            Необходимо заполнить «API URL».
                        </small>
                        <a class="btn btn-warning btn-sm mt-1" @click="generateUrl()"><i class="fa fa-undo" aria-hidden="true"></i> Сгенерировать</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <input type="date"
                               :id="localValue.active_from"
                               v-model="localValue.active_from"
                               class="form-control">
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <input type="date"
                               :id="localValue.active_after"
                               v-model="localValue.active_after"
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
        props: {
            value: {
                required: true,
            },
            type_content_id: {
                required: true,
            },
        },
        data:function(){
            return {
                ru:ru,
            }
        },
        computed: {
            localValue() {
                return Object.assign({}, this.value);
            }
        },
        methods: {
            ...mapActions(['updateElementContent']),
            async update() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                } else {
                    this.updateElementContent({id: this.localValue.id, label: this.localValue.label, api_url: this.localValue.apiUrl, active_from: this.localValue.active_from,
                        active_after: this.localValue.active_after, description: this.localValue.description, status: this.localValue.status, type_content_id: this.localValue.type_content_id}
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
                this.localValue.api_url =  url_slug(this.localValue.name)
            },
        },
        validations: {
            localValue:{
                label: {required},
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
