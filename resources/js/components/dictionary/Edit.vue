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
                         <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name"
                                :class="{invalid: (!$v.form.name.required)}">
                         <small class="helper-text invalid" v-if="!$v.form.name.required">
                             Необходимо заполнить «Наименование».
                         </small>
                     </div>

                     <div class="block">
                         <label for="api_url"><b>Код справочника:</b></label>
                         <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.code"
                                :class="{invalid: ($v.form.code.$dirty && !$v.form.code.required)}">
                         <small class="helper-text invalid" v-if="$v.form.code.$dirty && !$v.form.code.required">
                             Необходимо заполнить «Код».
                         </small>
                     </div>
                     <div class="block">
                         <label for="description"><b>Описание справочника:</b></label>
                         <textarea autocomplete="off" name="description" v-model="dictionary_id.description" id="description" class="form-control"/>
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
        props:['dictionary_id'],
        data:function(){
            return {
                form:{
                    name:null,
                    code:null,
                    description:null,
                }
            }
        },
        methods: {
            ...mapActions(['updateDictionary']),
            async updDictionary() {
                this.$v.form.$touch();
                if (this.$v.form.$invalid) {
                    console.log('Form not subm')
                } else {
                    this.updateDictionary({
                        id: this.dictionary_id.id,
                        form: this.dictionary_id
                    }).then(response => {
                        this.$emit('close-modal');
                        console.log(response);
                    }).catch(errors => {
                        console.log(errors);
                    });
                }
            },
            async Calc(){
                this.form.name = this.dictionary_id.name;
                this.form.code = this.dictionary_id.code;
                this.form.description = this.dictionary_id.description;
            }

        },
        validations: {
            form:{
                name: {required},
                code: {required},
            }
        },
        async beforeUpdated(){
            this.Calc();
        },


    }
</script>

<style scoped>

</style>
