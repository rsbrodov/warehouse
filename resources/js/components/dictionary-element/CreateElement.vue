<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание платежа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="$emit('close-modal')">&times;</span>
                </button>
            </div>
            <form @submit.prevent="saveDictionaryElement()">
                <div class="modal-body">
                    <div class="row">
                        <div class="block col-6">
                            <label for="name"><b>Сумма</b></label>
                            <input autocomplete="off" id="name" class="form-control" type="text" v-model="form.amount"
                                   :class="{invalid: ($v.form.amount.$dirty && !$v.form.amount.required)}">
                            <small class="helper-text invalid" v-if="$v.form.amount.$dirty && !$v.form.amount.required">
                                Необходимо заполнить «Сумма».
                            </small>

                            <input autocomplete="off" id="user_id" class="form-control" type="hidden" v-model="user_id">
                        </div>
                        <div class="block col-6">
                            <label for="owner"><b>Тип:</b></label>
                            <select id="owner" class="form-control" v-model="form.up_down">
                                <option disabled selected value> -- Выберите -- </option>
                                <option value="up">Пополнение</option>
                                <option value="down">Списание</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="block col-6">
                            <label for="name"><b>Вид оплаты</b></label>
                            <input autocomplete="off" id="type" class="form-control" type="text" v-model="form.type"
                                   :class="{invalid: ($v.form.type.$dirty && !$v.form.type.required)}">
                            <small class="helper-text invalid" v-if="$v.form.type.$dirty && !$v.form.type.required">
                                Необходимо заполнить «Вид оплаты».
                            </small>
                        </div>
                        <div class="block col-6">
                            <label for="date"><b>Дата оплаты:</b></label>
                            <date-picker v-model="form.date"
                                         :lang="lang"
                                         :format="'DD.MM.YYYY'"
                                         :valueType="'format'"/>
                        </div>
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
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';
    export default {
        name: "CreateElement",
        components: {DatePicker},
        props:['dictionary_id', 'loadList'],
        data:function(){
            return {
                user_id: window.location.href.split('/')[5],
                form:{
                    name:'',
                    up_down:'',
                    type:'',
                    date:'',
                },
                lang: {
                    formatLocale: {
                        firstDayOfWeek: 1,
                    },
                    monthBeforeYear: false,
                },
            }
        },
        methods: {
            ...mapActions(['newDictionaryElement']),
            async saveDictionaryElement() {
                axios.post('/payments/store', { user_id: this.user_id, amount: this.form.amount, up_down: this.form.up_down, date: this.form.date, type: this.form.type })
                    .then(response => {
                        if (response.status === 201) {
                            this.flashMessage.success({
                                message: 'Данные сохранены',
                                time: 3000,
                            });
                        }
                        this.form.amount = ''; this.form.up_down = ''; this.form.date = ''; this.form.type = '';
                        this.$emit('close-modal');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
        },
        validations: {
            form:{
                amount: {required},
                up_down: {required},
                type: {required},
                date: {required},
            }
        },
    }
</script>

<style scoped>

</style>
