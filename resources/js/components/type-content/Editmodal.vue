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
                        <label for="required"><b>Обязательно к заполнению</b></label>
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
    export default {
        name: "Editmodal",
        props: ['copy', 'clonedItems'],
        data() {
            return {
            }
        },
        computed:{
            vv(){
                let find = this.clonedItems.find(clonedItems => clonedItems.uid === this.copy)
                return find;
            }
        },
        methods:{
            saveDropElement() {
                this.$emit('close-modal', 'editElement', this.vv)
            }
        }
    }
</script>

<style scoped>

</style>
