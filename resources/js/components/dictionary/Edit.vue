<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование тарифа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="updDictionary()">
                <div class="modal-body">
                    {{errors}}
                     <div class="block">
                         <label for="name"><b class="text-danger">*</b><b>Наименование тарифа</b></label>
                         <input autocomplete="off" id="name" class="form-control" type="text" v-model="localDictionary.name"
                                :class="{invalid: (!$v.localDictionary.name.required)}">
                         <small class="helper-text invalid" v-if="!$v.localDictionary.name.required">
                             Необходимо заполнить «Наименование».
                         </small>
<!--                         <small class="helper-text invalid" v-if="errors.name">-->
<!--                             {{errors.name}}<br>-->
<!--                         </small>-->
                     </div>
                     <div class="block">
                         <label for="description"><b>Описание тарифа:</b></label>
                         <textarea autocomplete="off" name="description" v-model="localDictionary.description" id="description" class="form-control"/>
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
        props: {
            dictionary: {
                required: true,
            },
            data:function(){
                return {
                    errors:{},
                }
            },
        },
        computed: {
            localDictionary() {
                return Object.assign({}, this.dictionary);
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
                    this.updateDictionary({id: this.localDictionary.id, name: this.localDictionary.name, code: this.localDictionary.code, description: this.localDictionary.description}
                    ).then(response => {
                        this.$emit('close-modal');
                        this.flashMessage.success({
                            message: 'Тариф отредактирован',
                            time: 3000,
                        });
                    }).catch(error => {
                        console.log(error);
                        /*if (error.response.status === 422) {
                            console.log(error);//this.errors = error.response.data.errors || {};
                        }*/
                    });
                }
            },

        },
        validations: {
            localDictionary:{
                code: {required},
                name: {required},
            }
        },


    }
</script>

<style scoped>

</style>
