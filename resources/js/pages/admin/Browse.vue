<template>
  <div class="px-9 pt-2 pb-1 font-lato">
    <div style="height: 19vh">
      <!-- page title -->
      <h1 class="page-title mb-7">ADMINS</h1>
      <div class="flex mb-5">
        <SearchBox @search="getDataBySearch" />
        <button
          class="bg-main ms-4 text-white rounded-sm px-4 create-btn"
          @click="clickCreateBtn"
        >
          Create New Admin
        </button>

        <AdminCreate
          v-if="isCreate"
          @refreshAdminData="getAdminsTableData"
          @close="close"
        ></AdminCreate>
      </div>
    </div>

    <!-- table list view -->
    <AdminTable
      v-if="auth_id !== undefined"
      @refreshAdminData="getAdminsTableData"
      :gridData="gridData"
      :auth_id="auth_id"
      gridHeight="79vh"
    ></AdminTable>

    <DxPopup v-if="!isLoading" :drag-enabled="false">
      <div class="popup-content">
        <loader />
      </div>
    </DxPopup>
    <DxPopup v-if="!isSuccess" :drag-enabled="false">
      <div class="popup-content">
        <success-pop-up />
      </div>
    </DxPopup>
  </div>
</template>
  <script>
import SearchBox from "../../components/general/SearchBox.vue";
import Loader from "../../components/general/Loader.vue";
import SuccessPopUp from "../../components/general/SuccessPopUp.vue";
import { DxPopup } from "devextreme-vue/popup";
import AdminTable from "../../components/table/AdminTable.vue";
import AdminCreate from "./AdminCreate.vue";

export default {
  components: {
    SearchBox,
    Loader,
    SuccessPopUp,
    DxPopup,
    AdminTable,
    AdminCreate,
  },
  data() {
    return {
      isCreate: false,
      isLoading: false,
      isSuccess: false,
      auth_id: "",
      gridData: [],
    };
  },
  methods: {
    clickCreateBtn() {
      this.isCreate = true;
    },
    close(){
        this.isCreate = false;
    },
    async getAdminsTableData() {
      const response = await fetch(`/api/get-all-admins`);
      this.fetchData(response);
    },
    async fetchData(response) {
      try {
        const data = await response.json();
        const formattedData = data.data.map((user, index) => ({
          id: user.id,
          index: index + 1,
          name: user.name,
          email: user.email,
        }));

        this.gridData = formattedData;
      } catch (error) {
        return error;
      }
    },
    async getDataBySearch(data) {
      const response = await fetch(`/api/admins?search=${data.input}`);
      this.fetchData(response);
    },
    async authCheck() {
      try {
        const response = await fetch("/api/auth-check");
        const responseData = await response.json();

        this.auth_id = responseData.data;
      } catch (error) {
        console.error("Error during auth check:", error);
      }
    },
  },
  mounted() {
    this.authCheck();
    this.getAdminsTableData();
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name="_token"]').content;
    },
  },
};
</script>


