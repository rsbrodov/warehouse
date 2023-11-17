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

                        <input autocomplete="off" id="dictionary_id" class="form-control" type="hidden" v-model="dictionary_id">
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
        props:['dictionary_id', 'loadList'],
        data:function(){
            return {
                form:{
                    name:'',
                }
            }
        },
        methods: {
            ...mapActions(['newDictionaryElement']),
            async saveDictionaryElement() {
                this.$v.form.$touch();
                if (this.$v.form.$invalid) {
                    console.log('Form not subm')
                } else {
                    this.newDictionaryElement({
                        form: {
                            value: this.form.name,
                            dictionary_id: this.dictionary_id,
                            load_list: this.loadList === false ? false : true
                        }

                    }).then(response => {
                        this.$emit('close-modal');
                        this.form.name = '';
                        this.$v.$reset()
                        this.flashMessage.success({
                            message: 'Элемент справочника успешно добавлен',
                            time: 3000,
                        });
                    }).catch(errors => {
                        console.log(errors);
                    });
                }
            },
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
