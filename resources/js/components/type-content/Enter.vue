<style>
.flex-cont {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    width: 66.66%;
}

.flex-elem {
    margin: 5px
}
</style>
<template>
    <div class="container-fluid">
    </div>
</template>

<script>

export default {
    name: "Enter",
    data() {
        return {}
    },


}
</script>

<style scoped>
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
    margin-top: 20px;
    display: flex;

}
</style>
