<template>
<div>
        <!-- Modal Dictionary create -->
<!--        <div class="modal fade" id="dictionaryElementCreate" aria-hidden="true">-->
<!--            <div class="modal-dialog modal-lg" role="document">-->
<!--                <CreateElement @close-modal="closeModal('dictionaryElementCreate')"></CreateElement>-->
<!--            </div>-->
<!--        </div>-->
        <div class="d-flex justify-content-center"><h1>Элементы справочника</h1></div>
        <table class="table table-bordered table-hover mt-4">
            <tr>
                <th>Список значений справочника</th>
                <th>Управление</th>
            </tr>
            <tr v-for="(dictionary_element, index) in DictionaryElement" :key="index" >
                <td>{{dictionary_element.value}}</td>
                <td nowrap>
<!--                <button class="btn btn-danger del" @click="removeDictionary(dictionary.id)"><i class="fa fa-trash fa-lg"></i></button>-->
<!--                <button class="btn btn-warning plus" @click="openModal('dictionaryElementCreate')"><i class="fa fa-plus fa-lg" style="color:white"></i></button>-->
<!--                <a :href="'/dictionary/'+dictionary.id+'/dictionary-element'" class="btn btn-success eye"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>-->
                </td>
            </tr>
        </table>
</div>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    /*import Create from './create'
    import CreateElement from "./CreateElement";*/
    export default{
        //components:{Create, CreateElement},
        data:function(){
            return {
                id: window.location.href.split('/').slice(-3)[0],
            }
        },

        computed:{
            ...mapGetters(['DictionaryElement']),
        },

        methods: {
            ...mapActions(['getDictionaryElement', 'deleteDictionaryElement']),
            closeModal(id){
                if(id == 'dictionaryElementCreate'){
                    $("#dictionaryElementCreate").modal("hide");
                }

            },
            removeDictionary(id){
                this.deleteDictionary(id);
            },
            openModal(id){
                if(id == 'dictionaryElementCreate') {
                    $('#dictionaryElementCreate').modal('show');
                }
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

        async created(){
            this.getDictionaryElement(this.id);
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
    .del {
        background-color: #dc3545!important;
    }
    .plus {
        background-color: #ffc107!important;
    }
    .eye {
        background-color: #28a745!important;
    }
</style>
