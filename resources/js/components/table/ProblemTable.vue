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
      data-field="category_name"
      caption="CATEGORY NAME"
      cell-template="categoryIdTemplate"
    />
    <DxColumn
      data-field="imageUrl"
      caption="IMAGE"
      cell-template="imageTemplate"
    />
    <DxColumn
      data-field="expected_time_to_complete"
      caption="EXPECTED TIME TO COMPLETE"
      cell-template="expected_time_to_completeTemplate"
    />
    <DxColumn
      data-field="correct_option"
      caption="CORRECT OPTION"
      cell-template="correct_optionTemplate"
    />
    <DxColumn data-field="" caption="" cell-template="editColumn" />

    <template #categoryIdTemplate="{ data }">
      <i alt="category_id" width="70" height="70">{{
        data.data.category_name
      }}</i>
    </template>

    <template #imageTemplate="{ data }">
      <img
        :src="`${data.data.imageUrl}`"
        alt="problem_img"
        width="70"
        height="70"
      />
    </template>

    <template #expected_time_to_completeTemplate="{ data }">
      <i alt="expected_time_to_complete" width="70" height="70">{{
        data.data.expected_time_to_complete
      }}</i>
    </template>

    <template #correct_optionTemplate="{ data }">
      <i alt="correct_option" width="70" height="70">
        {{ data.data.correct_option }}
      </i>
    </template>

    <template #editColumn="{ data }">
    <div class="flex justify-center" v-if="!isShowDetail">
        <Icon
        v-if="!data.data.isEdit"
        icon="ph:trash-light"
        color="#e33b3b"
        width="20"
        ref="deleteIcon"
        @click.stop="deleteProblem(data.data.id)"
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
  <ProblemDetail
    v-if="isShowDetail"
    :problemDetail="problemDetail"
    @close="closeDetail"
    @refreshProblemData="refreshProblemData"
  ></ProblemDetail>
</template>
  <script>
import {
  DxDataGrid,
  DxPager,
  DxPaging,
  DxScrolling,
  DxColumn,
} from "devextreme-vue/data-grid";
import ProblemDetail from "../../pages/problem/ProblemDetail.vue"
import { Icon } from "@iconify/vue";

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
    ProblemDetail
  },
  props: ["gridData", "gridHeight", "isDetail"],

  emits: ["refreshProblemData"],
  data() {
    return {
      isShowDetail: false,
      customizeColumns: customizeColumns,
      pageSize: [10, 20, 50, 100],
      problemDetail: {},
    };
  },
  methods: {
    showDetail(e) {
        const data = e.data;
        const isDeleteIcon = e.event.target === this.$refs.deleteIcon;
        if (isDeleteIcon) {
            return;
        }

        // Continue with the showDetail logic for other cases
        this.isShowDetail = true;
        this.problemDetail = data;
    },

    closeDetail() {
      this.isShowDetail = false;
      this.problemDetail = {};
    },
    refreshProblemData() {
      this.$emit("refreshProblemData");
    },

    deleteProblem(id) {
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
          const problem_id = id;
          const csrf = document.querySelector('meta[name="_token"]').content;
          fetch("/api/delete-problem", {
            method: "POST",
            headers: new Headers({
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrf,
            }),
            body: JSON.stringify({
              id: problem_id,
            }),
          })
          .then((response) => {
                this.refreshProblemData();
                this.isShowDetail = false;
                return response.json();
            })
            .catch((error) => {
                console.log(error);
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
