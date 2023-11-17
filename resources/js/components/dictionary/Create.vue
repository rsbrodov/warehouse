<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание справочника</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="saveDictionary()">
                <div class="modal-body">
                    <div class="block">
                         <label for="name"><b class="text-danger">*</b><b>Наименование справочника</b></label>
                         <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name"
                                :class="{invalid: ($v.form.name.$dirty && !$v.form.name.required)}">
                         <small class="helper-text invalid" v-if="$v.form.name.$dirty && !$v.form.name.required">
                             Необходимо заполнить «Наименование».
                         </small>
                        <small class="helper-text invalid" v-if="errors.name">
                            {{errors.name}}<br>
                        </small>
                     </div>

                     <div class="block">
                         <label for="api_url"><b class="text-danger">*</b><b>Код справочника:</b></label>
                         <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.code"
                                :class="{invalid: ($v.form.code.$dirty && !$v.form.code.required)}">
                         <small class="helper-text invalid" v-if="$v.form.code.$dirty && !$v.form.code.required">
                             Необходимо заполнить «Код».
                         </small>
                         <small class="helper-text invalid" v-if="errors.code">
                             {{errors.code}}<br>
                         </small>
                     </div>
                     <div class="block">
                         <label for="description"><b>Описание справочника:</b></label>
                         <textarea autocomplete="off" name="description" v-model="form.description" id="description" class="form-control"
                                   :class="{invalid: ($v.form.description.$dirty && !$v.form.description.required)}"/>
                         <small class="helper-text invalid" v-if="$v.form.description.$dirty && !$v.form.description.required">
                             Необходимо заполнить «Описание».
                         </small>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="$emit('close-modal')">Отмена</button>
                    <button id="add" type="submit" class="btn btn-primary">ОК</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {required} from "vuelidate/lib/validators";
    import {mapActions} from "vuex";
    export default {
        name: "Create_dictionary",
        data:function(){
            return {
                form:{
                    name:'',
                    code:'',
                    description:'',
                },
                errors:{},
            }
        },
        methods: {
            ...mapActions(['newDictionary']),
            async saveDictionary() {
                this.$v.form.$touch();
                if (this.$v.form.$invalid) {

                } else {
                    this.newDictionary({
                        name: this.form.name, code: this.form.code, description: this.form.description,
                    }).then(response => {
                        this.$emit('close-modal');
                        this.$emit('get-dictionary-by-url');
                        this.form.code = '';
                        this.form.name = '';
                        this.form.description = '';
                        this.$v.$reset()
                        this.flashMessage.success({
                            message: 'Справочник успешно создан',
                            time: 3000,
                        });
                        console.log(23232);
                    }).catch(error => {
                        console.log('ошибуля');
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }else{
                            this.flashMessage.error({
                                message: 'Ошибка создания справочника',
                                time: 3000,
                            });
                        }
                    });
                }
            }
        },
        validations: {
            form:{
                name: {required},
                code: {required},
                description: {required},
            }
        },
    }
</script>

<style scoped>
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
