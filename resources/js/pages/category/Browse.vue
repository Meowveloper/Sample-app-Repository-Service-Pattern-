<template>
  <div class="px-9 pt-2 pb-1 font-lato">
    <div style="height: 19vh">
      <!-- page title -->
      <h1 class="page-title mb-7">CATEGORIES</h1>
      <div class="flex mb-5">
        <SearchBox @search="getDataBySearch" />
        <!-- create new shop btn -->
        <button
          class="bg-main ms-4 text-white rounded-sm px-4 create-btn"
          @click="clickCreateBtn"
        >
          Create New Category
        </button>
        <CategoryCreateVue
            v-if="isCreate"
            @refreshData="getCategoriesTableData"
            @close="close"
            :isDetail=true
        ></CategoryCreateVue>
      </div>
    </div>

    <!-- table list view -->
    <CategoryTable
        @refreshCategoryData="getCategoriesTableData"
        :gridData="gridData"
        gridHeight="79vh"
        :isDetail=true
    ></CategoryTable>
    <!-- <DxPopup  :drag-enabled="false">
      <div class="popup-content">

      </div>
    </DxPopup>
    <DxPopup  :drag-enabled="false">
      <div class="popup-content">

      </div>
    </DxPopup> -->
  </div>
</template>
<script>


import SearchBox from "../../components/general/SearchBox.vue";
import CategoryCreateVue from "./CategoryCreate.vue";
import CategoryTable from "../../components/table/CategoryTable.vue";
import Loader from "../../components/general/Loader.vue";
import SuccessPopUp from "../../components/general/SuccessPopUp.vue";
import { DxPopup } from 'devextreme-vue/popup';

export default {
  components: {
    SearchBox,
    CategoryCreateVue,
    CategoryTable,
    Loader,
    SuccessPopUp,
    DxPopup
  },
  data() {
    return {
        isLoading : false,
        isSuccess : false,
        gridData: [],
        isCreate: false,
    };
  },
  methods: {
    clickCreateBtn() {
      this.isCreate = true;
    },
    close() {
      this.isCreate = false;
    },

    async getCategoriesTableData() {
      const response = await fetch(`/api/get-all-categories`);
      this.fetchData(response);
    },

    async fetchData(response) {
      try {
        const data = await response.json();

        const formattedData = data.data.map((category, index) => ({
          id: category.id,
          index: index + 1,
          name: category.name,
          //   imageUrl: problem.imageUrl,
        }));

        this.gridData = formattedData;
      } catch (error) {
        return error;
      }
    },

    deleteCategory(id) {
        this.isLoading = true;
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
                }, 2000);
                this.getCategoriesTableData();
              }
            })
            .catch((error) => {
              return error;
            });
        }
      });
    },

    async getDataBySearch(data) {
      const response = await fetch(`/api/categories?search=${data.input}`);
      this.fetchData(response);
    },
  },
  mounted() {
    this.getCategoriesTableData();
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name="_token"]').content;
    },
  },
};
</script>


