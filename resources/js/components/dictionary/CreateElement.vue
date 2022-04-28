<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание элемента справочника</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="saveDictionaryElement()">
                <div class="modal-body">
                    <div class="block">
                        <label for="name"><b>Наименование элемента справочника</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name"
                               :class="{invalid: ($v.form.name.$dirty && !$v.form.name.required)}">
                        <small class="helper-text invalid" v-if="$v.form.name.$dirty && !$v.form.name.required">
                            Необходимо заполнить «Наименование».
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
        name: "CreateElement",
        data:function(){
            return {
                form:{
                    name:'',
                }
            }
        },
        methods: {
            //...mapActions(['newDictionary']),
            async saveDictionaryElement() {
                this.$v.form.$touch();
                if (this.$v.form.$invalid) {
                    console.log('Form not subm')
                } else {
                    this.$emit('close-modal');
                    this.form.name = '';
                    /*this.newDictionary({
                        form: this.form
                    }).then(response => {
                        this.$emit('close-modal');
                        this.form.code = '';
                        this.form.name = '';
                        this.form.description = '';
                        console.log(response);
                    }).catch(errors => {
                        console.log(errors);
                    });*/
                }
            }
        },
        validations: {
            form:{
                name: {required},
            }
        },
    }
</script>

<style scoped>

</style>
