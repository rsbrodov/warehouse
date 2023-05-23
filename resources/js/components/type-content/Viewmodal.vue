<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Информация о поле</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="cancel()">&times;</span>
            </button>
        </div>
        <form @submit.prevent="saveDropElement()">
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="title"><b>Наименование:</b></label>
                        <input autofocus autocomplete="off" id="title" class="form-control" type="text" v-model="localValue.title"
                               :class="{invalid: !validation}">
                        <small class="helper-text invalid" v-if="!validation">
                            Необходимо заполнить «Наименование».
                        </small>
                    </div>
                    <div class="block col-6">
                        <label for="required"><b>Обязательно к заполнению:</b></label>
                        <select id="required" class="form-control" v-model="localValue.required">
                            <option value="1">Да</option>
                            <option value="0">Нет</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b>Тип поля:</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="localValue.name" disabled="true">
                    </div>
                    <div class="block col-6" v-if="localValue.type == 'select' || localValue.type == 'radio' || localValue.type == 'checkbox'">
                        <label for="dictionary_id"><b>Справочник:</b></label>
                        <select id="dictionary_id" class="form-control"
                                v-model="localValue.dictionary_id">
                            <option v-for="(dic, index) in Dictionary"
                                    :key="index"
                                    :value="dic.id"
                            >
                                {{dic.name}}
                            </option>
                        </select>
                    </div>
                    <div class="block col-6">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cancel()">Отмена</button>
                <button id="add" type="submit" class="btn btn-primary">ОК</button>
            </div>
        </form>
    </div>
</template>

<script>

    export default {
        name: "Viewmodal",
        props: ['copy', 'clonedItems', 'currentClone'],
        data() {
            return {
                Dictionary:[],
                validation: true
            }
        },
        computed:{
            localValue() {
                return Object.assign({}, this.currentClone);
            },
        },
        methods:{
            async getDictionary() {
                await axios.get('http://127.0.0.1:8000/dictionary/findDictionaryNotEmptyElement')
                    .then(response => {
                        this.Dictionary = response.data
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            saveDropElement() {
                if(!this.localValue.title){
                    this.validation = false;
                }else{
                    this.validation = true;
                    this.$emit('close-modal', {status: 'SET', localValue: this.localValue, copy: this.copy})
                }
            },
            cancel() {
                this.$emit('close-modal', {status: 'REMOVE', localValue: this.localValue, copy: this.copy})
            },
        },
        async mounted(){
            this.getDictionary();
        }
    }
</script>

<style scoped>

</style>
