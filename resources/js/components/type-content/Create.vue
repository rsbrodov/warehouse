<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Создание типа контента</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <form @submit.prevent="saveTypeContent()">
                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="owner"><b>Владелец типа контента:</b></label>
                        <input autocomplete="off" id="owner" class="form-control" type="text" v-model="form.owner">
                    </div>

                    <div class="block col-6">
                        <label for="icon"><b>Иконка для отображения</b></label>
                        <select id="icon" class="form-control" v-model="form.icon">
                            <option v-for="(icon, index) in icons" :key="index" :value="icon.id">
                                {{icon.name}}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="name"><b>Наименование типа контента</b></label>
                        <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.name">
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>API URL:</b></label>
                        <input autocomplete="off" id="api_url" class="form-control" type="text" v-model="form.api_url">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="block col-6">
                        <label for="active_from"><b>Период действия с:</b></label>
                        <input autocomplete="off" type="text" name="active_from" v-model="form.active_from" id="active_from" class="form-control datepicker-here">
                    </div>

                    <div class="block col-6">
                        <label for="api_url"><b>Период действия по:</b></label>
                        <input  autocomplete="off" type="text" name="active_after" v-model="form.active_after" id="active_after" class="form-control datepicker-here">
                    </div>
                </div>

                <div class="row">
                    <div class="block col-12">
                        <label for="description"><b>Описание:</b></label>
                        <input autocomplete="off" type="textarea" name="description" v-model="form.description" id="description" class="form-control">
                    </div>
                </div>


            </form>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-primary">ОК</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Create",

        data:function(){
            return {
                form:{
                    icon:'',
                    name:'',
                    owner:'',
                    api_url:'',
                    active_from:'',
                    active_after:'',
                    description:'',
                }
            }
        },

        methods: {
            async saveTypeContent() {
                // axios.get('http://127.0.0.1:8000/type-content')
                //     .then(response => {
                //         //console.log(response.data)
                //         this.type_contents = response.data;
                //     })
                //     .catch(error => {
                //         console.log(error);
                //     });
                console.log(123);
            }
        },
    }
</script>

<style scoped>

</style>
