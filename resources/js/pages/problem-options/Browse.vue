<template>
  <div class="px-9 py-2 font-lato category-list">
    <div id="filterContentContainer" ref="filterContentContainer">
      <!-- page title -->
      <h1 class="page-title mb-7">ANSWER OPTIONS</h1>
      <div class="flex mb-3">

        <SearchBox @search ="getDataBySearch"/>
      </div>
    </div>

    <!-- table list view -->
    <ProblemOptionsTableVue
        @refreshProblemOptionsData = "getProblemOptionsTableData"
        :gridData="gridData"
        gridHeight = "79vh"
        :isDetail=true
    ></ProblemOptionsTableVue>

  </div>
</template>
<script>
import DxTextBox from "devextreme-vue/text-box";
import {
  DxDataGrid,
  DxPager,
  DxPaging,
  DxScrolling,
  DxColumn,
  DxEditing,
} from "devextreme-vue/data-grid";
import { DxPopup} from "devextreme-vue/popup";
import SearchBox from '../../components/general/SearchBox.vue'
import ProblemOptionsTableVue from '../../components/table/ProblemOptionsTable.vue';


export default {
  components: {
    DxTextBox,
    DxDataGrid,
    DxPager,
    DxPaging,
    DxScrolling,
    DxPopup,
    DxColumn,
    DxEditing,
    ProblemOptionsTableVue,
    SearchBox,
  },
  data() {
    return {
      gridData: [],
      pageSize: [10, 20, 50, 100],
      pageIndex: 0,
      createPopupVisible: false,
      isSaveButtonDisabled: false,
      isOptionValid: true,
      isloading: false,
      isShowSuccess: false,
      isToggleSearch: false,
      problem_option: {
        id: "",
        option: "",
        problem_id: "",
      },
    };
  },
  methods: {
    closeCreatePopUp() {
      this.problem_option.id = "";
      this.problem_option.problem_id = "";
      this.problem_option.option = "";
      this.isOptionValid = true;
      this.createPopupVisible = false;
    },

    validateOption() {
      this.isOptionValid =
        this.problem_option.option !== null && this.problem_option.option.trim() !== "";
    },

    async getProblemOptionsTableData() {
      const response = await fetch(`/api/get-all-problem-options`);
      this.fetchData(response);
    },

    async fetchData(response) {
      try {
        const data = await response.json();
        const formattedData = data.data.map((problem_options, index) => ({
          id: problem_options.id,
          problem_id: problem_options.problem_id,
          index: index + 1,
          content: problem_options.content,
          //   imageUrl: problem.imageUrl,
        }));

        this.gridData = formattedData;
      } catch (error) {
        return error;
      }
    },

    editProblemOption(data) {
      this.problem_option.id = data.id;
      this.problem_option.problem_id = data.problem_id;
      this.problem_option.option = data.content;
      this.createPopupVisible = true;
    },
    updateData() {
      // Edit Problem Option
      const csrf = document.querySelector('meta[name="_token"]').content;
      const formData = this.problem_option;
      this.isloading = true;
      fetch("/api/update-problem-option", {
        method: "POST",
        headers: new Headers({
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrf,
        }),
        body: JSON.stringify({
          data: formData,
        }),
      })
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          console.log(data);
          if (data.status === "success") {
            this.isloading = false;
            this.isShowSuccess = true;
            setTimeout(() => {
              this.isShowSuccess = false;
              this.createPopupVisible = false;
            }, 2000);
            this.problem_option.id = "";
            this.problem_option.problem_id = "";
            this.problem_option.option = "";
            this.getProblemOptionsTableData();
          }
        })
        .catch((error) => {
          return error;
        });
    },

    async getDataBySearch(data) {
      const response = await fetch(`/api/problem-options?search=${data.input}`);
      this.fetchData(response);
    },

    toggleSearch() {
      this.isToggleSearch = !this.isToggleSearch;
    },
  },
  mounted() {
    this.getProblemOptionsTableData();
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name="_token"]').content;
    },
  },
};
</script>

<style>
.category-list .create-btn {
  padding: 10px;
}
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-freespace-row {
  display: none;
}
.category-create-form label {
  color: black;
  font-size: 12px;
  font-family: Lato;
  font-weight: 700;
}

.page-title {
  font-size: 34px !important;
  font-style: normal;
  font-weight: 800 !important;
  line-height: normal;
  text-transform: uppercase;
}

.search-btn {
  border-radius: 20px !important;
  margin-right: 10px !important;
  border-color: #116a5b !important;
}

.search-btn .dx-button-mode-contained.dx-button-default {
  margin-left: 11px;
  border: none !important;
  background: transparent !important;
}

#filter-container {
  padding: 35px 0;
  border-top: 1px solid #aaaaaa;
}

#list-view-container {
  border-top: 1px solid #aaaaaa;
}
.custom-data-grid {
  overflow-x: auto;
}

.custom-data-grid .dx-datagrid-headers {
  border-bottom: none;
}

.custom-data-grid
  .dx-datagrid-headers
  .dx-datagrid-table
  .dx-header-row
  .dx-datagrid-action,
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td {
  text-align: center !important;
  border-left: none;
  border-right: none;
  border-bottom: 1px solid #d9d9d9;
  padding: 10px !important;
  text-transform: capitalize;
  font-family: Lato;
  color: black;
  font-size: 12px;
  vertical-align: middle;
}
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td img {
  display: inline;
}
.custom-data-grid
  .dx-datagrid-headers
  .dx-datagrid-table
  .dx-header-row
  .dx-datagrid-action {
  font-weight: 700;
}
.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-data-row td {
  font-weight: 400;
}

.custom-data-grid .dx-datagrid-rowsview .dx-datagrid-content .dx-row.dx-freespace-row {
  display: none;
}

#pageSizeSelect {
  border: 1px solid #d9d9d9;
  border-radius: 7px;
  color: #d9d9d9;
  padding: 3px;
  font-size: 12px;
}

#pageSizeSelect:active,
#pageSizeSelect:focus,
#pageSizeSelect:focus-visible {
  border-color: #116a5b;
  color: #116a5b;
}

.category-list .dx-pager .dx-pages {
  display: block;
}

.dx-overlay-content .dx-popup-title {
  color: black;
  font-size: 24px;
  font-family: Lato;
  font-weight: 700;
}

.validation-error {
  color: red;
  font-size: 10px;
  font-family: Lato;
}

.loading-popup-content {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.3);
  border-top: 4px solid #116a5b;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
  /* animation: spin 1s linear infinite, gradient 2s linear infinite; */
  margin-right: 10px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.loading-text {
  color: #116a5b;
  font-size: 13px;
  font-weight: 400;
  font-family: Lato;
}
</style>
