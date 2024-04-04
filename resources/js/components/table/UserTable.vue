<template>
    <dx-data-grid
      :data-source="gridData"
      :customize-columns="customizeColumns"
      class="custom-data-grid"
      :columnAutoWidth="true"
      :style="{ height: gridHeight }"
    >
      <DxColumn data-field="index" caption="ID" />
      <DxColumn
        data-field="name"
        caption="NAME"
        cell-template="userNameTemplate"
      />
      <DxColumn
        data-field="user_id"
        caption="USER ID"
        cell-template="userUser_idTemplate"
      />
      <DxColumn
        data-field="point"
        caption="SCORE"
        cell-template="userPointTemplate"
      /><DxColumn
        data-field="login_method"
        caption="LOGIN METHOD"
        cell-template="userLoginMethodTemplate"
      />
      <DxColumn
        data-field="last_login_date"
        caption="LAST LOGIN DATE"
        cell-template="userLastLoginDateTemplate"
      />
      <template #userNameTemplate="{ data }">
        <i>{{
          data.data.name
        }}</i>
      </template>
      <template #userUser_idTemplate="{ data }">
        <i>{{
          data.data.user_id
        }}</i>
      </template>
      <template #userPointTemplate="{ data }">
        <i>{{
          data.data.point
        }}</i>
      </template>
      <template #userLoginMethodTemplate="{ data }">
        <i>{{
          data.data.login_method
        }}</i>
      </template>
      <template #userLastLoginDateTemplate="{ data }">
        <i>{{ new Date(data.data.last_login_date).toISOString().split('T')[0] }}</i>
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
  </template>

  <script>
  import {
    DxDataGrid,
    DxPager,
    DxPaging,
    DxScrolling,
    DxColumn,
  } from "devextreme-vue/data-grid";

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
    },
    props: ["gridData", "gridHeight", "isDetail"],
    data() {
      return {
        customizeColumns: customizeColumns,
        pageSize: [10, 20, 50, 100],
      };
    },
  };
  </script>
