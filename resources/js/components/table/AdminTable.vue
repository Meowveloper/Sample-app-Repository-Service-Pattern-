<template>
  <dx-data-grid
    :data-source="gridData"
    :customize-columns="customizeColumns"
    class="custom-data-grid"
    :columnAutoWidth="true"
    :style="{ height: gridHeight }"
    @row-click="showDetail"
  >
    <DxColumn data-field="index" caption="ID"></DxColumn>
    <DxColumn
      data-field="name"
      caption="NAME"
      cell-template="adminNameTemplate"
    ></DxColumn>
    <DxColumn
      data-field="email"
      caption="EMAIL"
      cell-template="adminEmailTemplate"

    ></DxColumn>
    <DxColumn data-field="" caption="" cell-template="editTemplate"></DxColumn>
    <template #adminNameTemplate="{ data }">
      {{ data.data.name }}
    </template>
    <template #adminEmailTemplate="{ data }">
      {{ data.data.email.toLowerCase() }}
    </template>

    <template #editTemplate="{ data }">
        <div>
            <div class="flex justify-center">
                <Icon
                    icon="ph:trash-light"
                    color="#e33b3b"
                    width="20"
                    ref="deleteIcon"
                    @click.stop="deleteAdmin(data.data.id)"
                />
            </div>
            <!-- ... -->
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
    <AdminDetail
    v-if="isShowDetail"
    :adminDetail="adminDetail"
    @close="closeDetail"
    @refreshAdminData="refreshAdminData"
  ></AdminDetail>
  </dx-data-grid>
</template>

  <script>
import {
  DxDataGrid,
  DxPager,
  DxPaging,
  DxScrolling,
  DxColumn,
} from "devextreme-vue/data-grid";
import { Icon } from "@iconify/vue";
import AdminDetail from "../../pages/admin/AdminDetail.vue";
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
    Icon,
    AdminDetail
  },
  props: ["gridData", "gridHeight","auth_id"],
  data() {
    return {
      customizeColumns: customizeColumns,
      pageSize: [10, 20, 50, 100],
      isDetail : false,
      isShowDetail :false,
      adminDetail : {}
    };
  },
  methods: {
    showDetail(e){
        const data= e.data;
        const isDeleteIcon = e.event.target === this.$refs.deleteIcon;

        if(isDeleteIcon){
            return;
        }

        this.isShowDetail = true;
        this.adminDetail = data;
    },
    closeDetail(){
        this.isShowDetail = false;
        this.adminDetail = {};
    },
    refreshAdminData() {
      this.$emit("refreshAdminData");
    },
    deleteAdmin(id) {
    if(id == this.auth_id){
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "error",
        title: "𝗖𝗮𝗻𝗻𝗼𝘁 𝗱𝗲𝗹𝗲𝘁𝗲 𝘁𝗵𝗲 𝗰𝘂𝗿𝗿𝗲𝗻𝘁 𝘂𝘀𝗲𝗿."
        });
    }else{
        Swal.fire({
        title: "Are you sure to delete?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          const admin_id = id;
          const csrf = document.querySelector('meta[name="_token"]').content;
          fetch("/api/delete-admin", {
            method: "POST",
            headers: new Headers({
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrf,
            }),
            body: JSON.stringify({
              id: admin_id,
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
                this.refreshAdminData();
              }
            })
            .catch((error) => {
              return error;
            });
        } else {
          this.isShowDetail = false;
        }
      });
    }

    },
  },

};
</script>
