<template>
  <dx-data-grid
    :data-source="gridData"
    :customize-columns="customizeColumns"
    class="custom-data-grid"
    :columnAutoWidth="true"
    :style="{ height: gridHeight }"
    @row-click="showDetail"
  >
    <DxColumn data-field="index" caption="ID" />
    <DxColumn
      data-field="name"
      caption="CATEGORY NAME"
      cell-template="categoryNameTemplate"
    />
    <DxColumn caption="" cell-template="editColumn" />
    <template #categoryNameTemplate="{ data }">
      <i>{{
        data.data.name
      }}</i>
    </template>
    <template #editColumn="{ data }">
        <div class="flex justify-center" v-if="!isShowDetail">
        <Icon
            v-if="!data.data.isEdit"
            icon="ph:trash-light"
            color="#e33b3b"
            width="20"
            ref="deleteIcon"
            @click.stop="deleteCategory(data.data.id)"
        />
        </div>
    </template>

    <DxPaging :page-size="10" />
    <DxPager
      :visible="true"
      :allowed-page-sizes="pageSize"
      display-mode="compact"
      :show-page-size-selector="true"
      :show-info="true"
      :show-navigation-buttons="true"
    />
    <DxScrolling
      mode="standard"
      column-rendering-mode="virtual"
      :use-native="false"
      :scroll-by-content="true"
      :scroll-by-thumb="true"
      show-scrollbar="never"
    />
  </dx-data-grid>
  <CategoryDetailVue
    v-if="isShowDetail"
    :categoryDetail="categoryDetail"
    @close="closeDetail"
    @refreshCategoryData="refreshCategoryData"
  ></CategoryDetailVue>
</template>
<script>
import {
  DxDataGrid,
  DxPager,
  DxPaging,
  DxScrolling,
  DxColumn,
} from "devextreme-vue/data-grid";
import CategoryDetailVue from "../../pages/category/CategoryDetail.vue";
import { Icon } from '@iconify/vue';

const customizeColumns = (columns) => {
  columns[0].maxWidth = 47;
};

export default {
  components: {
    DxDataGrid,
    DxPager,
    DxPaging,
    DxScrolling,
    DxColumn,
    CategoryDetailVue,
    Icon
  },
  props: ["gridData", "gridHeight", "isDetail"],
  emits: ["refreshCategoryData"],
  data() {
    return {
      isShowDetail: false,
      customizeColumns: customizeColumns,
      pageSize: [10, 20, 50, 100],
      categoryDetail: {},
    };
  },
  methods: {
    showDetail(e) {
        const data = e.data;
        const isDeleteIcon = e.event.target === this.$refs.deleteIcon;

        if(isDeleteIcon){
            return;
        }
        this.isShowDetail = true;
        this.categoryDetail = data;
    },
    closeDetail() {
      this.isShowDetail = false;
      this.categoryDetail = {};
    },
    refreshCategoryData() {
      this.$emit("refreshCategoryData");
    },

    deleteCategory(id) {
    this.isShowDetail = false;
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          const category_id = id;
          const csrf = document.querySelector('meta[name="_token"]').content;
          fetch("/api/delete-category", {
            method: "POST",
            headers: new Headers({
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrf,
            }),
            body: JSON.stringify({
              id: category_id,
            }),
          })
            .then((response) => {
              return response.json();
            })
            .then((data) => {
              if (data.status == "success") {
                this.isloading = false;
                this.isShowSuccess = true;
                setTimeout(() => {
                  this.isShowSuccess = false;
                  this.createPopupVisible = false;
                }, 2000);
                this.refreshCategoryData();
                this.isShowDetail = false;
              }
            })
            .catch((error) => {
              return error;
            });
        }else{
            this.isShowDetail = false;
        }
      });
    },
  },
};
</script>
