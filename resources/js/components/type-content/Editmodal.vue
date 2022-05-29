<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Информация о поле</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" @click="$emit('close-modal', 'editElement')">&times;</span>
            </button>
        </div>
        <form @submit.prevent="saveDropElement()">
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="title"><b>Наименование:</b></label>
                        <input autocomplete="off" id="title" class="form-control" type="text" v-model="vv.title">
                    </div>
                    <div class="block col-6">
                        <label for="required"><b>Обязательно к заполнению:</b></label>
                        <select id="required" class="form-control" v-model="vv.required">
                            <option value="1">Да</option>
                            <option value="0">Нет</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b>Тип поля:</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="vv.name" disabled="true">
                    </div>
                    <div class="block col-6">
                        <label for="dictionary_id"><b>Справочник:</b></label>
                        <select id="dictionary_id" class="form-control"
                                v-model="vv.dictionary_id">
                            <option v-for="(dic, index) in Dictionary"
                                    :key="index"
                                    :value="dic.id"
                            >
                                {{dic.name}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="$emit('close-modal', 'editElement')">Отмена</button>
                <button id="add" type="submit" class="btn btn-primary">ОК</button>
            </div>
        </form>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "Editmodal",
        props: ['copy', 'clonedItems'],
        data() {
            return {
            }
        },
        computed:{
            ...mapGetters(['Dictionary']),
            vv(){
                let find = this.clonedItems.find(clonedItems => clonedItems.uid === this.copy)
                return find;
            }
        },
        methods:{
            ...mapActions(['getDictionary']),
            saveDropElement() {
                this.$emit('close-modal', 'editElement', this.vv)
            }
        },
        async mounted(){
            this.getDictionary();
        }
    }
</script>

<style scoped>

</style>
