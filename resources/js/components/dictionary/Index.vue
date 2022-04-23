<template>
<div>
        <div class="d-flex justify-content-center"><h1>Справочники</h1></div>
        <div class="row mt-4">
            <div class="header-block row">
                <div class="search-button col-2">
                    <button id="hideshow" class="btn btn-primary" @click="toggleSearch()"><span class="fa fa-search fa-lg" aria-hidden="true"></span></button>
                </div>
                <div class="search-form col-8">
                    <div class="form">
                        <form action="" method="post">
                            <div class="form-group row">
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Код справочника" id="code" class="form-control" v-model="filter_form.code">
                                </div>
                                <div class="col-4">
                                    <input autocomplete="off" type="text" name="name" placeholder="Наименование справочника" id="name" class="form-control" v-model="filter_form.name">
                                </div>
                                <div class="col-4">
                                    <select id="status" class="form-control" name="archive" v-model="filter_form.status">
                                        <option value=''>Все</option>
                                        <option value='0'>Действующий</option>
                                        <option value='1'>Архивный</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="create col-2">
                    <button id="clean" class="btn btn-primary" @click="cleanSearch()"><span class="fa fa-paint-brush fa-lg" aria-hidden="true"></span> Очистить</button>
                    <a href="" class="btn-create btn btn-primary"><span class="fa fa-plus-circle fa-lg" aria-hidden="true"></span></a>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>Код справочника</th>
                <th>Наименование справочника</th>
                <th>Описание справочника</th>
                <th>Последнее изменение</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            <tr v-for="(dictionary, index) in filteredDictionary" :key="index" >
                <td>{{dictionary.code}}</td>
                <td>{{dictionary.name}}</td>
                <td>{{dictionary.description}}</td>
                <td>{{ dictionary | dateUpdated }}</td>
                <td :class="dictionary.archive | statusColor"><b>{{ dictionary.archive | status }}</b></td>

<!--                <td nowrap>-->
<!--                    <a href="{{route('dictionary.show', ($dictionary->id))}}" class="btn btn-success ">-->
<!--                        <i class="fa fa-eye fa-lg" aria-hidden="true"></i>-->
<!--                    </a>-->
<!--                    <a href="{{route('dictionary.edit', ($dictionary->id))}}" class="btn btn-primary">-->
<!--                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>-->
<!--                    </a>-->
<!--                    <a href="{{route('dictionary-element.index', $dictionary->id)}}" class="btn btn-success">-->
<!--                        <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>-->
<!--                    </a>-->
<!--                    <a href="{{route('dictionary.archive', $dictionary->id)}}" class="btn btn-secondary">-->
<!--                        @if(\App\Models\Dictionary::find($dictionary->id)->archive == '1')-->
<!--                        <i class="fa fa-history fa-lg" aria-hidden="true"></i>-->
<!--                        @else-->
<!--                        <i class="fa fa-file-archive-o fa-lg" aria-hidden="true"></i>-->
<!--                        @endif-->
<!--                    </a>-->
<!--                </td>-->
            </tr>
        </table>
<!--        <a href="{{route('dictionary.create')}}" class="btn btn-primary form-control">Создать</a>-->
<!--    </div>-->
<!--    @endsection-->

</div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    //import Create from "./Create";
    import moment from 'moment'
    export default{
        //components:{Create},
        data:function(){
            return {
                filter_form:{
                    status:'',
                    name:'',
                    code:'',
                },
            }
        },

        computed:{
            ...mapGetters(['Dictionary']),
            filteredDictionary: function () {
                return this.Dictionary.filter((dictionary) => {
                    return dictionary.name.toLowerCase().match(this.filter_form.name)
                }).filter((dictionary) => {
                    return dictionary.code.toLowerCase().match(this.filter_form.code);
                })/*.filter((dictionary) => {
                    return dictionary.archive.match(this.filter_form.status);
                })*/;
            }
        },

        methods: {
            ...mapActions(['getDictionary']),
            closeModal(){
                $("#exampleModal").modal("hide");
            },
            openModal(){
                $('#exampleModal').modal('show');
            },
            toggleSearch(){
                $('.form').toggle('show');
            },
            cleanSearch(){
                $( 'form' ).each(function(){
                    this.reset();
                });
            }
        },

        filters: {
            dateUpdated: function (dictionary) {
                return moment(dictionary.updated_at).format('DD.MM.YYYY HH:II');
            },
            status: function (status) {
                if(status == 1){
                    return "Архивный";
                }else{
                    return "Действующий";
                }
            },
            statusColor: function (status) {
                if(status == 1){
                    return "text-danger";
                }else{
                    return "text-success";
                }
            }
        },

        async created(){
            this.getDictionary();
        },

        async mounted(){
            $('.form').hide();
        }
    }
</script>

<style scoped>
    table > :not(:first-child) {
        border-top: 1px solid currentColor !important;
    }
    .header-block{
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
    }
    .pencil > a {
        background-color: #007bff!important;
    }

    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal-content {
        margin: 2px auto;
        z-index: 1100 !important;
    }
</style>
