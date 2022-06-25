<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование справочника</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="updDictionary()">
                <div class="modal-body">
                     <div class="block">
                         <label for="name"><b>Наименование справочника</b></label>
                         <input autocomplete="off" id="name" class="form-control" type="text" v-model="vv.name"
                                :class="{invalid: (!$v.form_edit.name.required)}">
                         <small class="helper-text invalid" v-if="!$v.form_edit.name.required">
                             Необходимо заполнить «Наименование».
                         </small>
                     </div>

                     <div class="block">
                         <label for="api_url"><b>Код справочника:</b></label>
                         <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="vv.code"
                                :class="{invalid: ($v.form_edit.code.$dirty && !$v.form_edit.code.required)}">
                         <small class="helper-text invalid" v-if="$v.form_edit.code.$dirty && !$v.form_edit.code.required">
                             Необходимо заполнить «Код».
                         </small>
                     </div>
                     <div class="block">
                         <label for="description"><b>Описание справочника:</b></label>
                         <textarea autocomplete="off" name="description" v-model="vv.description" id="description" class="form-control"/>
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
    import {mapActions, mapGetters} from "vuex";
    export default {
        name: "Edit",
        props:['dictionary_id', 'dictionary_name', 'dictionary_code', 'dictionary_description'],
        data:function(){
            return {
                form_edit:{
                    id:null,
                    name:null,
                    code:null,
                    description:null,
                }
            }
        },
        computed:{
            vv(){
                this.form_edit.id = this.dictionary_id;
                this.form_edit.name = this.dictionary_name;
                this.form_edit.code = this.dictionary_code;
                this.form_edit.description = this.dictionary_description;
                return this.form_edit;
            }
        },
        methods: {
            ...mapActions(['updateDictionary']),
            async updDictionary() {
                console.log(123)
                this.$v.$touch();
                if (this.$v.$invalid) {
                    console.log('Form not subm')
                } else {
                    this.updateDictionary({id: this.form_edit.id, name: this.form_edit.name, code: this.form_edit.code, description: this.form_edit.description}
                    ).then(response => {
                        this.$emit('close-modal');
                        this.flashMessage.success({
                            message: 'Справочник отредактирован',
                            time: 3000,
                        });
                    }).catch(errors => {
                        console.log(errors);
                    });
                }
            },

        },
        validations: {
            form_edit:{
                code: {required},
                name: {required},
            }
        },


    }
</script>

<style scoped>

</style>
