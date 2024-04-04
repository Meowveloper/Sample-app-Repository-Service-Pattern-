<template>
  <div class="px-9 py-2 font-lato problem-list">
    <div id="filterContentContainer" ref="filterContentContainer">
      <!-- page title -->
      <h1 class="page-title mb-7">PROBLEMS</h1>
      <div class="flex mb-3">
        <!-- <DxTextBox
          ref="search"
          class="search-btn"
          width="280px"
          height="34px"
          placeholder="Search"
          :buttons="[
            {
              location: 'before',
              name: 'searchButton',
              options: searchButton,
            },
          ]"
        >
        </DxTextBox> -->
        <!-- create new shop btn -->
        <button
          class="bg-main text-white rounded-sm px-4 creat-btn"
          @click="clickCreateBtn"
        >
          Create New Problem
        </button>

        <ProblemCreate
            v-if="isCreate"
            @refreshProblemData="getProblemTableData"
            @close="close"
        ></ProblemCreate>
      </div>
    </div>

    <!-- table list view -->
    <ProblemTable
        @refreshProblemData="getProblemTableData"
        :gridData="gridData"
        :isDetail=true
    ></ProblemTable>

    <DxPopup
      v-model:visible="isloading"
      :drag-enabled="false"
      :hide-on-outside-click="true"
      :show-close-button="false"
      :show-title="false"
      :width="300"
      :height="100"
      container=".dx-viewport"
    >
      <div class="loading-popup-content">
        <div class="spinner"></div>
        <span class="loading-text">Loading, please wait...</span>
      </div>
    </DxPopup>
    <DxPopup
      v-model:visible="isShowSuccess"
      :hide-on-outside-click="true"
      :drag-enabled="false"
      :show-close-button="false"
      :show-title="false"
      :width="300"
      :height="100"
      container=".dx-viewport"
    >
      <div class="loading-popup-content">
        <iconify-icon
          icon="gg:check-o"
          style="color: #2ab936; margin-right: 10px"
          width="24"
        ></iconify-icon>
        <span class="loading-text">Done successfully.</span>
      </div>
    </DxPopup>
  </div>
</template>
<script>
import DxTextBox from "devextreme-vue/text-box";
import DxSelectBox from "devextreme-vue/select-box";
import {
  DxDataGrid,
  DxPager,
  DxPaging,
  DxScrolling,
} from "devextreme-vue/data-grid";
import { DxPopup } from "devextreme-vue/popup";
import DxButton from "devextreme-vue/button";
import { DxFileUploader } from "devextreme-vue/file-uploader";
import ProblemTable from '../../components/table/ProblemTable.vue';
import ProblemCreate from './ProblemCreate.vue'

const searchButton = {
  icon: "/images/icons/search.svg",
  type: "default",
};

export default {
  components: {
    DxTextBox,
    DxButton,
    DxSelectBox,
    DxDataGrid,
    DxPager,
    DxPaging,
    DxScrolling,
    DxPopup,
    DxFileUploader,
    ProblemTable,
    ProblemCreate
  },
  data() {
    return {
      searchButton: searchButton,
      gridData: [],
      isCreate : false,
      pageSize: [10, 20, 50, 100],
      pageIndex: 0,
      createPopupVisible: false,
      isloading: false,
      isShowSuccess: false,
    };
  },
  methods: {
    clickCreateBtn() {
      this.isCreate = true;
    },
    close(){
        this.isCreate = false;
    },
    onImageChange(e) {
      // Handle image change and update the problem object
      this.problem.image = e.value;
    },
    onUploaded(e) {
      // Handle image change and update the problem object
    },
    customRequest(e) {
      // Handle image change and update the problem object
      const csrf = document.querySelector('meta[name="_token"]').content;
      const formData = new FormData();
      formData.append("file", e.file);

      fetch("/api/upload-problem-image", {
        method: "POST",
        headers: new Headers({
          "Content-Type": "multipart/form-data",
          "X-CSRF-TOKEN": csrf,
        }),
        body: JSON.stringify({
            data: formData,
        }),
      })
        .then((response) => {
            console.log(response);
          return response.json();
        })
        .then((data) => {
          console.log("Data received:", data);

          if (data.status === "success") {
            // Update the problem.imageUrl
            this.problem.imageUrl = data.image;

            // Log the imageUrl to the console
            console.log("Image URL:", this.problem.imageUrl);
          } else {
            console.error("Server returned an error:", data.message);
          }
        })
        .catch((error) => {
          console.error("Error during fetch:", error);
        });
    },

    async getProblemTableData() {
      const response = await fetch(`/api/get-all-problems`);
      this.fetchData(response);
    },
    async fetchData(response) {
      try {
        const data = await response.json();
        this.gridData = data.data.map((problem, index) => {
          const category_name = problem.category ? problem.category.name : "";
          return {
            id: problem.id,
            category_id: problem.category_id,
            category_name: category_name,
            expected_time_to_complete: problem.expected_time_to_complete,
            correct_option_id: problem.correct_option_id,
            correct_option : problem.correct_option.content,
            index: index + 1,
            imageUrl: problem.imageUrl,
          };
        });
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    async getCategories() {
      try {
        const response = await fetch("/api/get-all-categories");
        const data = await response.json();
        if (data) {
          this.categories = data;
        }
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    },

    async fetchProblemOptions() {
      try {
        const csrf = document.querySelector('meta[name="_token"]').content;
        const problem_id = this.problem.id;
        const response = await fetch("/api/get-problem-options", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf,
          },
          body: JSON.stringify({
            problem_id: problem_id,
          }),
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        if (data) {
          this.problemOptions = data.data;
        }
      } catch (error) {
        console.error("Error fetching problem options:", error);
      }
    },
  },
  mounted() {
    this.getProblemTableData();
    this.getCategories();
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name="_token"]').content;
    },
  },
};
</script>
<style>
.problem-list .creat-btn {
  padding: 10px;
}
.custom-data-grid
  .dx-datagrid-rowsview
  .dx-datagrid-content
  .dx-row.dx-freespace-row {
  display: none;
}
.problem-create-form label {
  color: black;
  font-size: 12px;
  font-family: Lato;
  font-weight: 700;
}

.problem-create-form .dx-texteditor.dx-editor-outlined {
  width: 17vw;
  margin-top: 5px;
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
.custom-data-grid
  .dx-datagrid-rowsview
  .dx-datagrid-content
  .dx-row.dx-data-row
  td {
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
.custom-data-grid
  .dx-datagrid-rowsview
  .dx-datagrid-content
  .dx-row.dx-data-row
  td
  img {
  display: inline;
}
.custom-data-grid
  .dx-datagrid-headers
  .dx-datagrid-table
  .dx-header-row
  .dx-datagrid-action {
  font-weight: 700;
}
.custom-data-grid
  .dx-datagrid-rowsview
  .dx-datagrid-content
  .dx-row.dx-data-row
  td {
  font-weight: 400;
}

.custom-data-grid
  .dx-datagrid-rowsview
  .dx-datagrid-content
  .dx-row.dx-freespace-row {
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

.problem-list .dx-pager .dx-pages {
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

.dx-popup .dx-closebutton {
  display: block !important;
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
