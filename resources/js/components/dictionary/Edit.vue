<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование справочника23</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="updDictionary()">
                <div class="modal-body">
                    {{element}}
                     <div class="block">
                         <label for="name"><b class="text-danger">*</b><b>Наименование справочника</b></label>
                         <input autocomplete="off" id="name" class="form-control" type="text" v-model="element.name"
                                :class="{invalid: (!$v.element.name.required)}">
                         <small class="helper-text invalid" v-if="!$v.element.name.required">
                             Необходимо заполнить «Наименование».
                         </small>
                     </div>

                     <div class="block">
                         <label for="api_url"><b class="text-danger">*</b><b>Код справочника:</b></label>
                         <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="element.code"
                                :class="{invalid: ($v.element.code.$dirty && !$v.element.code.required)}">
                         <small class="helper-text invalid" v-if="$v.element.code.$dirty && !$v.element.code.required">
                             Необходимо заполнить «Код».
                         </small>
                     </div>
                     <div class="block">
                         <label for="description"><b>Описание справочника:</b></label>
                         <textarea autocomplete="off" name="description" v-model="element.description" id="description" class="form-control"/>
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
        },
        data:function(){
            return {
                element:{},
            }
        },
        watch: {
            'dictionary': {
                handler: function (newValue, oldValue) {
                    this.element = newValue;
                    console.log('dsdsdsd',this.element)
                },
                deep: true
            },
        },
        methods: {
            ...mapActions(['updateDictionary']),
            async updDictionary() {
                console.log(123)
                this.$v.$touch();
                if (this.$v.$invalid) {
                    console.log('Form not subm')
                } else {
                    this.updateDictionary({id: this.element.id, name: this.element.name, code: this.element.code, description: this.element.description}
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
            element:{
                code: {required},
                name: {required},
            }
        },


    }
</script>

<style scoped>

</style>
