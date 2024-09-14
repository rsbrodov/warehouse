<template>
    <div id="app">
        <!-- Modal Create -->
        <div class="modal fade" id="createPayment" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <create-element
                    @close-modal="closeModal('createPayment')"
                />
            </div>
        </div>
        <div class="container-fluid">
            <Onetype />
            <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
            <nav class="mt-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link" :class="{active : activeTab === 'general'}" @click="setActiveTab('general')">Основные данные</a>
                    <a class="nav-link" :class="{active : activeTab === 'contacts'}" @click="setActiveTab('contacts')">Контакты</a>
                    <a class="nav-link" :class="{active : activeTab === 'connect'}" @click="setActiveTab('connect')">Подключение</a>
                    <a class="nav-link" :class="{active : activeTab === 'payment'}" @click="setActiveTab('payment')">Платежи</a>
                    <a class="nav-link" :class="{active : activeTab === 'historyChange'}" @click="setActiveTab('historyChange')">История изменений</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

            </div>
            <hr>
            <br>
            <div class="row" style="min-height: 700px!important; resize: both;">
                <div class="col-9 left-block">
                    <div class="left-block__layout">
                        <!--TAB GENERAL-->
                        <div v-if="activeTab === 'general'" class="left-block__undraggable-layout__column">
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="clientName"><span class="text-danger">*</span>Наименование клиента</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.clientName"
                                           type="text"
                                           class="form-control"
                                           id="clientName"
                                           name="clientName">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="id"><span class="text-danger"></span>ID клиента</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.id"
                                           type="text"
                                           class="form-control"
                                           disabled="disabled"
                                           id="id"
                                           name="id">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="tariff"><span class="text-danger">*</span>Тариф</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.tariff"
                                           type="text"
                                           class="form-control"
                                           id="tariff"
                                           name="tariff">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="paymentState"><span class="text-danger">*</span>Статус оплаты</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.paymentState"
                                           type="text"
                                           class="form-control"
                                           id="paymentState"
                                           name="paymentState">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="accessFrom"><span class="text-danger">*</span>Доступ С</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.accessFrom"
                                           type="text"
                                           class="form-control"
                                           id="accessFrom"
                                           name="accessFrom">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="paymentState"><span class="text-danger">*</span>Дата последнего платежа</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           class="form-control"
                                           id="lastPay"
                                           disabled="disabled"
                                           value="10.12.2023"
                                           name="lastPay">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="nextPayFrom"><span class="text-danger">*</span>Дата следующего платежа С</label>
                                    </div>
                                    <input autocomplete="off"
                                           value="10.12.2023"
                                           type="text"
                                           class="form-control"
                                           disabled="disabled"
                                           id="nextPayFrom"
                                           name="nextPayFrom">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="nextPayTo"><span class="text-danger">*</span>Дата следующего платежа По</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           class="form-control"
                                           id="nextPayTo"
                                           disabled="disabled"
                                           value="10.12.2023"
                                           name="nextPayTo">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="contract"><span class="text-danger">*</span>Номер и дата договора</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.contract"
                                           type="text"
                                           class="form-control"
                                           id="contract"
                                           name="contract">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="mail"><span class="text-danger">*</span>Email для уведомлений</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.mail"
                                           type="text"
                                           class="form-control"
                                           id="mail"
                                           name="mail">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="clientName"><span class="text-danger">*</span>ИНН</label>
                                    </div>
                                    <input autocomplete="off"
                                           v-model="typeContentOne.innClient"
                                           type="text"
                                           class="form-control"
                                           id="innClient"
                                           name="innClient">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="description"><span class="text-danger">*</span>Описание</label>
                                    </div>
                                    <textarea autocomplete="off"
                                           v-model="typeContentOne.description"
                                           type="text"
                                           class="form-control"
                                           id="description"
                                              name="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--TAB CONTACTS-->
                        <div v-if="activeTab === 'contacts'" class="left-block__undraggable-layout__column">
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="fio"><span class="text-danger">*</span>ФИО</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="Сидоров Михаил Иванович"
                                           class="form-control"
                                           id="fio"
                                           name="fio">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="position"><span class="text-danger">*</span>Адрес</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="Москва, 1-я Мелитопольская ул., 32А"
                                           class="form-control"
                                           id="position"
                                           name="position">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="phone"><span class="text-danger">*</span>Телефон</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="8-923-703-37-76"
                                           class="form-control"
                                           id="phone"
                                           name="phone">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="mail"><span class="text-danger">*</span>Email</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="sidiriv_miha@mail.ru"
                                           class="form-control"
                                           id="mail"
                                           name="mail">
                                </div>
                            </div>
                        </div>
                        <!--TAB CONNECT-->
                        <div v-if="activeTab === 'connect'" class="left-block__undraggable-layout__column">
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="host"><span class="text-danger">*</span>URL</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           v-model="typeContentOne.host"
                                           class="form-control"
                                           id="host"
                                           name="host">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="ip"><span class="text-danger">*</span>IP-адрес</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="194.226.171.196"
                                           class="form-control"
                                           id="ip"
                                           name="ip">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="label">
                                        <label for="port"><span class="text-danger">*</span>Порт</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="80"
                                           class="form-control"
                                           id="port"
                                           name="port">
                                </div>
                                <div class="item">
                                    <div class="label">
                                        <label for="login"><span class="text-danger">*</span>Login</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="cmsuser@mail.ru"
                                           class="form-control"
                                           id="login"
                                           name="login">
                                </div>
                            </div>
                            <div class="left-block__draggable-column">
                                <div class="item">
                                    <div class="token">
                                        <label for="token"><span class="text-danger">*</span>Токен</label>
                                    </div>
                                    <input autocomplete="off"
                                           type="text"
                                           value="923-strereg-1093-bo9iejola-sd"
                                           class="form-control"
                                           disabled="disabled"
                                           id="token"
                                           name="token">
                                </div>
                            </div>
                        </div>
                        <!--TAB CONNECT-->
                        <div v-if="activeTab === 'payment'" class="left-block__undraggable-layout__column">
                            <div class="left-block__draggable-column" style="display:flex; justify-content:center; ">
                                <table class="table table-bordered mt-4" style="width:90%">
                                    <tr style="text-align: center">
                                        <th>Сумма</th>
                                        <th>Вид оплаты</th>
                                        <th>Кем оплачено</th>
                                        <th>Дата оплаты</th>
                                        <th>Действие</th>
                                    </tr>
                                    <tr v-for="(payment, index) in payments.data" :key="index"
                                        style="text-align: center">
                                        <td :class="payment.up_down | statusColor">{{payment.up_down === 'up' ? '+' : '-'}}{{payment.amount}}</td>
                                        <td>{{payment.type}}</td>
                                        <td>{{payment.params ? 'По карте клиента' : 'Администратор'}}</td>
                                        <td>{{payment.date}}</td>
                                        <td class="text-center" nowrap>
                                            <button class="btn btn-danger del" @click="removePayment(payment.id)"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-primary form-control" @click="openModal('createPayment')">
                                    <i class="fa fa-dollar fa-lg" aria-hidden="true"></i> Добавить платеж
                                </button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="col-3">
                    <div class="d-flex flex-column">
                        <div class="p-2">
                            <button class="btn btn-primary form-control text-left"
                                    @click="saveClient()">
                                <i class="fa fa-save fa-lg" aria-hidden="true"></i> Сохранить изменения
                            </button>
                        </div>
                        <div class="p-2">
                            <button class="btn btn-primary form-control text-left" @click="deleteTypeContent(typeContentOne)">
                                <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Удалить
                            </button>
                        </div>
                        <div class="p-2">
                            <button class="btn btn-primary form-control text-left" @click="changeStatusType('Published', 'Тип контента восстановлен из архива')">
                                <i class="fa fa-cloud-download fa-lg" aria-hidden="true"></i> Востановить из архива
                            </button>
                        </div>
                        <div class="p-2" v-if="typeContentOne.status == 'Published'">
                            <button class="btn btn-primary form-control text-left" @click="changeStatusType('Archive', 'Тип контента отправлен в архив')">
                                <i class="fa fa-trash fa-lg" aria-hidden="true"></i> Отправить в архив
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapActions, mapGetters } from "vuex";
import Onetype from './Onetype.vue';
import {url_slug} from "cyrillic-slug";
import CreateElement from "../dictionary-element/CreateElement";

export default {
    name: "Viewtype",
    components: {CreateElement, Onetype },
    data() {
        return {
            activeTab: 'general',
            changeStatus: false,
            activeTypeContent: [],
            payments: [],
            showContextMenu: false,
            type_content_id: window.location.href.split('/')[5],
            copy: null,
            currentClone: null,
            currentEdit: null,
            data: null,
            clonedItems: [
                [
                    []
                ],
            ],
        };
    },
    computed: {
        ...mapGetters(['typeContentOne']),

    },

    methods: {
        ...mapActions(['getTypeContentOne']),
        saveClient() {
            axios.post('/type-content/save-body', { data: this.typeContentOne})
                .then(response => {
                    if (response.status === 200) {
                        this.changeStatus = false;
                        this.flashMessage.success({
                            message: 'Данные сохранены',
                            time: 3000,
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async deleteTypeContent(typeContent) {
            if (confirm('Вы уверены, что хотите удалить тип контента «' + typeContent.name + ' ' + typeContent.version.major + '.' + typeContent.version.minor + '»?')) {
                await axios.delete('/type-content/' + this.type_content_id)
                    .then(response => {
                        if (response.status === 204) {
                            this.flashMessage.success({
                                message: 'Тип контента удален, Вы будете перенаправлены на страницу со списком типов контента через 3 секунды',
                                time: 3000,
                            });
                            window.setTimeout(function () {
                                window.location.href = "/type-content/index"
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },

        closeModal(id){
            $("#"+id).modal("hide");
            if(id == 'createPayment') {
                this.getPayments();
            }

        },
        openModal(id){
            $('#'+id).modal('show');
        },

        async getPayments() {
            await axios.get('/payments/findPayments?user_id='+this.type_content_id)
                .then(response => {
                    this.payments = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        },

        async removePayment(id) {
            if (confirm('Вы уверены, что хотите удалить платеж?')) {
                await axios.delete('/payments/' + id)
                    .then(response => {
                        if (response.status === 204) {
                            this.flashMessage.success({
                                message: 'Платеж удален',
                                time: 3000,
                            });
                        }
                        this.getPayments();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },



        async changeStatusType(status, message) {
            this.update({
                id: this.typeContentOne.id, owner: this.typeContentOne.owner, icon: this.typeContentOne.icon, name: this.typeContentOne.name, apiUrl: this.typeContentOne.apiUrl,
                    activeFrom: this.typeContentOne.activeFrom, activeAfter: this.typeContentOne.activeAfter, description: this.typeContentOne.description, status: status,
            }
            ).then(response => {
                this.$emit('close-modal');
                this.flashMessage.success({
                    message: message,
                    time: 3000,
                });
                this.getTypeContentOne(this.type_content_id);

                /****/
                this.getActiveTypeContent();
                /****/
            }).catch(errors => {
                console.log(errors);
            });

        },

        setActiveTab(param){
            this.activeTab = param;
        },

    },

    async created() {
        await this.getTypeContentOne(this.type_content_id);
        await this.getPayments();
    },
    filters: {
        date: function (type_content) {
            if (!type_content.active_from && !type_content.active_after) {
                return "Не задан";
            } else if (!type_content.active_from && type_content.active_after) {
                return "До " + moment(type_content.active_after).format('DD.MM.YYYY');
            } else if (type_content.active_from && !type_content.active_after) {
                return moment(type_content.active_from).format('DD.MM.YYYY') + " - бессрочно";
            } else {
                return moment(type_content.active_from).format('DD.MM.YYYY') + " - " + moment(type_content.active_after).format('DD.MM.YYYY');
            }

        },
        dateUpdated: function (type_content) {
            return moment(type_content.update_date).format('DD.MM.YYYY HH:II');
        },
        status: function (status) {
            let status_array = { Draft: 'Черновик', Published: 'Опубликовано', Archive: 'В архиве' };
            if (status) {
                return status_array[status];
            } else {
                return status_array['Draft'];
            }
        },
        statusColor: function (status) {
            let status_array = {up: 'text-success', down: 'text-danger'};
            if(status){
                return status_array[status];
            }else{
                return status_array['up'];
            }
        }
    },
    beforeMount() {
        window.addEventListener("beforeunload", this.preventNav)
    },

    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNav);
    },

}

</script>

<style scoped>
    .del {
        background-color: #dc3545!important;
    }
.left-block {
    z-index: 1;
    background-color: #ededed;
    border:1px solid #E1E1E1

}
/*строка*/
.left-block__layout {
    background-color: white;
    min-height: 110px;
    max-height: 11000px;
    text-align: right;
    padding-bottom: 10px;

}
/*draggable column*/
.left-block__draggable-column{
    display:flex;
    margin: 5px -10px;
    background-color: white;
    min-height: 50px;
}
.item{
    flex: 1 1 auto;
    margin: 5px 10px;
}

.left-block__undraggable-layout__column{
    margin:10px;
}


.left-block__draggable-layout__draggable-parent__item>.button-group {
    display: flex;
    justify-content: space-between;
}

.left-block__draggable-layout__draggable-parent__item>p {
    color: black;
    font-size: 18px;
}
.label{
    text-align: left;
}


.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
    color: #414141;
    background-color: #E1E1E1;
    border-color: #E1E1E1;
    min-width: 175px;
    border-radius: 0px;
}

.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link {
    color: #414141;
    background-color: #FFFFFF;
    border-color: #E1E1E1;
    min-width: 175px;
    border-radius: 0px;
}

.nav-tabs {
    border-bottom: 0px !important;
    cursor: pointer;
}
</style>
