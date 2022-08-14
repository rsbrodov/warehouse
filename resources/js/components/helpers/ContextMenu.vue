<template>
<div class="context-menu pr-4 pt-4" v-show="show" :style="style" ref="context" tabindex="0">
    <button type="button" class="close"><span aria-hidden="true" @click="close">&times;</span></button>
        <slot></slot>
    </div>
</template>
<script>
import Vue from 'vue';

export default {
    name: 'ContextMenu',
    props: {
        display: Boolean, // prop detect if we should show context menu
    },
    data() {
        return {
            left: 0, // left position
            top: 0, // top position
            show: false, // affect display of context menu
        };
    },
    computed: {
        // get position of context menu
        style() {
            return {
                top: this.top + 'px',
                left: this.left + 'px',
            };
        },
    },
    methods: { 
        // closes context menu 
        close() {
            this.show = false;
            this.left = 0;
            this.top = 0;
        },
        open(event) {
            // updates position of context menu 
            let pagel_x = event.pageX-200;
            this.left = pagel_x || pagel_x;
            this.top = event.pageY || event.clientY;
            // make element focused 
            // @ts-ignore
            Vue.nextTick(() => this.$el.focus());
            this.show = true;
        },
    },
};
</script>
<style>
.context-menu {
    position: fixed;
    background: white;
    z-index: 999;
    outline: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    cursor: pointer;
}
.close {
	position: absolute;
	top: 0rem;
	right: 0rem;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 2.5rem;
	height: 2.5rem;
	border: none;
	background-color: transparent;
	font-size: 1.5rem;
	transition: 0.25s linear;
	
}
</style>