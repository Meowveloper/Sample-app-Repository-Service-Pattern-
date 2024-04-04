<template>
    <div class="px-9 pt-2 pb-1 font-lato">
      <div style="height: 19vh">
        <!-- page title -->
        <h1 class="page-title mb-7">USERS</h1>
        <div class="flex mb-5">
          <SearchBox @search="getDataBySearch" />
        </div>
      </div>

      <!-- table list view -->
      <UserTable
          @refreshUserData="getUsersTableData"
          :gridData="gridData"
          gridHeight="79vh"
      ></UserTable>

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
  import { DxPopup } from 'devextreme-vue/popup';
  import UserTable from "../../components/table/UserTable.vue";

  export default {
    components: {
      SearchBox,
      Loader,
      SuccessPopUp,
      DxPopup,
      UserTable
    },
    data() {
      return {
          isLoading : false,
          isSuccess : false,
          gridData: [],
      };
    },
    methods: {
      async getUsersTableData() {
        const response = await fetch(`/api/get-all-users`);
        this.fetchData(response);
      },
      async fetchData(response) {
        try {
          const data = await response.json();
          const formattedData = data.data.map((user, index) => ({
            id: user.id,
            index: index + 1,
            name: user.name,
            email : user.email,
            user_id : user.user_id,
            point : user.point,
            login_method: (user.facebook_id) ? 'Facebook' :
                          (user.google_id) ? 'Google' :
                          (user.apple_id) ? 'Apple' :
                          'Unknown Login Method',
            last_login_date : user.last_login_date,
          }));

          this.gridData = formattedData;
        } catch (error) {
          return error;
        }
      },
      async getDataBySearch(data) {
        const response = await fetch(`/api/users?search=${data.input}`);
        this.fetchData(response);
      },
    },
    mounted() {
      this.getUsersTableData();
    },
    computed: {
      csrf() {
        return document.querySelector('meta[name="_token"]').content;
      },
    },
  };
  </script>


