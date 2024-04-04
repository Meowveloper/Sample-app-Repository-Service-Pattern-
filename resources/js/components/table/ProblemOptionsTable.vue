<template>
    <dx-data-grid
      :data-source="gridData"
      :customize-columns="customizeColumns"
      class="custom-data-grid"
      :columnAutoWidth="true"
      :style="{ height: gridHeight }"
      @row-click="showDetail"
    >
      <DxColumn data-field="id" caption="ID" />
      <DxColumn
        data-field="name"
        caption="ANSWER CONTENT"
        cell-template="problemOptionsTemplate"
      />
      <DxColumn data-field="problem_id" caption="PROBLEM ID" />

      <template #problemOptionsTemplate="{ data }">
        <i>{{
          data.data.content
        }}</i>
      </template>

      <DxPaging :page-size="12" />
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

    <ProblemOptionsDetailVue
      v-if="isShowDetail"
      :ProblemOptionsDetail="ProblemOptionsDetail"
      @close="closeDetail"
      @ProblemOptionsDetail="refreshProblemOptionsData"
    ></ProblemOptionsDetailVue>

  </template>
  <script>
  import {
    DxDataGrid,
    DxPager,
    DxPaging,
    DxScrolling,
    DxColumn,
} from "devextreme-vue/data-grid";

import ProblemOptionsDetailVue from "../../pages/problem-options/ProblemOptionsDetail.vue"

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
      ProblemOptionsDetailVue,
    },
    props: ["gridData", "gridHeight","isDetail"],

    emits: ["refreshProblemOptionsData"],

    data() {
      return {
        isShowDetail: false,
        customizeColumns: customizeColumns,
        pageSize: [12, 24, 58, 106],

        ProblemOptionsDetail: {},
      };
    },
    methods: {
      showDetail(e) {
        const data = e.data;
        this.isShowDetail = true;
        this.ProblemOptionsDetail = data;
      },
      closeDetail() {
        this.isShowDetail = false;
        this.ProblemOptionsDetail = {};
      },
      refreshProblemOptionsData() {
        this.$emit("refreshProblemOptionsData");
      },

    },
  };
  </script>
