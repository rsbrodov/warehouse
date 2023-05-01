<template>
    <ul class="list-pagination">
      <li :class="{disabled : isInFirstPage }"
          class="list-pagination__item">
        <button
            type="button"
            @click="onClickPreviousPage"
            :disabled="isInFirstPage"
            class="list-pagination__item-link page-next"
        >
          <img svg-inline src="/storage/images/arr-left.svg" alt="">
        </button>
      </li>
      <li v-if="currentPage > maxVisibleButtons" :class="{disabled : isInFirstPage }"
          class="list-pagination__item">
        <button
            type="button"
            @click="onClickFirstPage"
            :disabled="isInFirstPage"
            class="list-pagination__item-link page-prev"
        >
          1
        </button>
      </li>

      <li v-if="currentPage > maxVisibleButtons"
          class="list-pagination__item">
        <span
            type="button"
            class="list-pagination__item-etc"
        >
          ...
        </span>
      </li>
      <li v-for="(page, index) in pages" v-if="page.name !== totalPages" :key="index" class="list-pagination__item">
        <button
            type="button"
            @click="onClickPage(page.name)"
            :disabled="page.isDisabled"
            class="list-pagination__item-link"
            :class="{ active: isPageActive(page.name) }"
            :aria-label="`Страница ${page.name}`"
        >
          {{ page.name }}
        </button>
      </li>
      <li v-if="currentPage < totalPages - maxVisibleButtons / 2"
          class="list-pagination__item">
        <span
            type="button"
            class="list-pagination__item-etc"
        >
          ...
        </span>
      </li>
      <li
          class="list-pagination__item">
        <button
            type="button"
            class="list-pagination__item-link"
            :class="{ active: isPageActive(totalPages) }"
            @click="onClickLastPage"
        >
          {{ totalPages }}
        </button>
      </li>
      <li :class="{disabled : isInLastPage }"
          class="list-pagination__item">
        <button
            type="button"
            @click="onClickNextPage"
            :disabled="isInLastPage"
            class="list-pagination__item-link page-next"
        >
          <img svg-inline src="/storage/images/arr-right.svg" alt="">
        </button>
      </li>


    </ul>

</template>

<script>

export default {
    name: 'pagination',
    components: {},
    props: {
        maxVisibleButtons: {
            type: Number,
            required: false,
            default: 7
        },
        totalPages: {
            type: Number,
            required: true
        },
        total: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        },
        page: {
            type: Number,
            required: true
        },

    },
    mounted() {
        // window.paginApp = this;
    },
    computed: {
        currentPage() {
            if (this.page > this.totalPages) {
                return this.totalPages
            } else {
                return this.page
            }
        },
        startPage() {
            if (this.currentPage === 1) {
                return 1;
            }
            if (this.currentPage === this.totalPages) {
                return this.totalPages - this.maxVisibleButtons + 1;
            }
            return this.currentPage - 1;
        },
        endPage() {
            return Math.min(this.startPage + this.maxVisibleButtons - 1, this.totalPages);
        },
        pages() {
            const range = [];
            let startP = this.startPage;
            if ((this.totalPages - startP) < this.maxVisibleButtons) {
                startP = this.totalPages - this.maxVisibleButtons
            }
            console.log('startP', startP);
            for (let i = startP; i <= this.endPage; i += 1) {
                if (i > 0) {
                    range.push({
                        name: i,
                        isDisabled: i === this.currentPage
                    });
                }
            }
            //if(this.maxVisibleButtons > this.totalPages && (this.totalPages - this.currentPage < this.maxVisibleButtons / 2)){ Версия до 26.04.2023
            if (this.maxVisibleButtons < this.totalPages && (this.totalPages - this.currentPage < this.maxVisibleButtons / 2)) {
                console.log('попали в обрезку pages');
                return range.splice(this.maxVisibleButtons / 2, range.length);
            } else {
                return range;
            }
        },
        /*firstPages() {
          const range = [];
          for (let i = 1; i <= this.maxVisibleButtons / 2; i+= 1 ) {
            range.push({
              name: i,
              isDisabled: i === this.currentPage
            });
          }
          return range;
        }, */
        isInFirstPage() {
            return this.currentPage === 1;
        },
        isInLastPage() {
            return this.currentPage === this.totalPages;
        },
    },
    methods: {
        onClickFirstPage() {
            this.$emit('pagechanged', 1);
        },
        onClickPreviousPage() {
            this.$emit('pagechanged', this.currentPage - 1);
        },
        onClickPage(page) {
            this.$emit('pagechanged', page);
        },
        onClickNextPage() {
            this.$emit('pagechanged', this.currentPage + 1);
        },
        onClickLastPage() {
            this.$emit('pagechanged', this.totalPages);
        },
        isPageActive(page) {
            return this.currentPage === page;
        },

    }
}


</script>

<style scoped lang="css">
    .list-pagination {
        padding: 0;
        margin: 0;
        list-style: none;
        display: flex;
        position: relative;
    }
    .list-pagination.pagination-loaded {
        opacity: 0.4;
    }
    .list-pagination.pagination-loaded:after {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        content: "";
        background: rgba(0, 0, 0, 0);
        z-index: 1;
    }
    .list-pagination__item {
        margin-right: 4px;
        position: relative;
    }
    .list-pagination__item-link, .list-pagination__item-etc {
        border-radius: 4px;
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        width: 48px;
        height: 48px;
        font-size: 16px;
        font-weight: 500;
        align-items: center;
        justify-content: center;
        color: rgba(0, 0, 0, 0.74);
        transition: all 0.3s ease;
    }
    .list-pagination__item-link.active, .list-pagination__item-etc.active {
        background: #F5F5F7;
        border-color: rgba(0, 0, 0, 0.74);
    }
    .list-pagination__item-link:hover, .list-pagination__item-etc:hover {
        background: #dcdcde;
        border-color: #dcdcde;
    }
    .list-pagination__item-etc {
        background: none;
        border-color: rgba(0, 0, 0, 0);
        color: rgba(0, 0, 0, 0.38);
    }
    .list-pagination__item-etc:hover {
        background: none;
        border-color: rgba(0, 0, 0, 0);
    }
    .list-pagination__item.disabled .page-prev {
        fill: rgba(0, 0, 0, 0.2);
        pointer-events: none;
        cursor: default;
    }
    .list-pagination__item.disabled .page-next {
        fill: rgba(0, 0, 0, 0.2);
        pointer-events: none;
        cursor: default;
    }
    .list-pagination .page-prev {
        fill: rgba(0, 0, 0, 0.74);
    }
    .list-pagination .page-next {
        fill: rgba(0, 0, 0, 0.74);
    }
    ul{
        margin-bottom: 15px!important;
    }
</style>
