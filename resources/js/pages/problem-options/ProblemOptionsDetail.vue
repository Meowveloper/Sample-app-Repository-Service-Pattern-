<template>
  <div @click="closePopupOnClickOutside">
    <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
    <div class="fixed inset-0 flex items-center justify-end z-50">
      <div
        id="shopUserDetailCard"
        class="bg-white p-8 rounded-lg shadow-md z-10"
      >
        <!-- Card content goes here -->
        <div>
          <div class="flex justify-between">
            <h2 class="card-title">ANSWER OPTIONS</h2>
            <iconify-icon
              icon="gg:close"
              style="color: #aaa"
              width="20"
              @click="closePopup"
            ></iconify-icon>
          </div>
          <hr class="border-main" />
          <div class="detail-container">
            <div class="flex justify-end" v-if="!isEdit">
              <!-- Edit button -->
              <button
                @click.stop="editProblemOption"
                class="text-white bg-main border border-main w-70px h-8 rounded ml-3"
              >
                Edit
              </button>
            </div>

            <div class="flex justify-end" v-if="isEdit">
              <!-- Close button -->
              <button
                @click.stop="cancelEdit"
                class="text-main border border-main w-70px h-8 rounded"
              >
                Cancel
              </button>
              <!-- Update button -->
              <button
                @click.stop="validateProblemOptionsContent"
                class="text-white bg-main border border-main w-70px h-8 rounded ml-3"
              >
                Update
              </button>
            </div>

            <div class="shop-user-info-container" v-if="!isEdit">
              <div class="mb-5">
                <h5>ANSWER OPTION CONTENT</h5>
                <div class="info-box">
                  {{ ProblemOptionsDetail.content }}
                </div>
              </div>
            </div>
            <div class="shop-user-info-edit-container" v-if="isEdit">
              <div class="form-group">
                <label for="content">PROBLEM OPTIONS CONTENT</label>
                <DxTextBox
                  class="form-input mr-8"
                  id="content"
                  v-model="ProblemOptionsDetail.content"
                  ref="content"
                />
                <span
                  v-show="validationErrors.content"
                  class="validation-error mt-1"
                >
                  {{ validationErrors.content }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <DxPopup
      v-model:visible="isLoading"
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
      v-model:visible="isSuccess"
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
import { DxPopup } from "devextreme-vue/data-grid";

export default {
  components: {
    DxTextBox,
    DxPopup,
  },
  props: ["ProblemOptionsDetail"],

  data() {
    return {
      originData: null,
      isEdit: false,
      validationErrors: {
        content: null,
      },
      validationPassed: true,
      isLoading: false,
      isSuccess: false,
    };
  },
  methods: {
    restoreOriginData() {
      this.ProblemOptionsDetail.id = this.originData.id;
      this.ProblemOptionsDetail.problem_id = this.originData.problem_id;
      this.ProblemOptionsDetail.content = this.originData.content;
    },
    cancelEdit() {
      this.isEdit = false;
      this.restoreOriginData();
      this.validationPassed = true;
    },
    editProblemOption() {
      this.isEdit = true;
      if (!this.originData) {
        this.originData = { ...this.ProblemOptionsDetail };
      }
    },
    closePopupOnClickOutside(event) {
      // Check if the click event occurred outside of the popup
      if (
        !this.$el.querySelector("#shopUserDetailCard").contains(event.target)
      ) {
        // Close the popup if user clicked outside
        if (this.isEdit) {
          this.restoreOriginData();
        }
        this.closePopup();
      }
    },
    async validateProblemOptionsContent() {
      this.validationPassed = true;
      if (!this.ProblemOptionsDetail.content) {
        this.validationErrors.content = "Problem option content is required.";
        this.validationPassed = false;
      }
      if (this.validationPassed) {
        this.updateProblemOptions();
      }
    },
    async updateProblemOptions() {
      this.isLoading = true;
      const apiUrl = `/api/update-problem-option/${this.ProblemOptionsDetail.id}`;
      const requestData = {
        problem_id: this.ProblemOptionsDetail.problem_id,
        content: this.ProblemOptionsDetail.content,
      };

      const csrfToken = document.head.querySelector(
        'meta[name="_token"]'
      ).content;
      console.log(requestData);
      const response = await fetch(apiUrl, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
      });

      const encodedData = await response.json();

      setTimeout(() => {
        this.isLoading = false;
        if (encodedData.status.toLowerCase() === "success") {
          this.isSuccess = true;
          setTimeout(() => {
            this.isSuccess = false;
            this.closePopup();
          }, 1000);
        }
      }, 1000);

      this.$emit("ProblemOptionsDetail");
    },
    closePopup() {
      this.validationPassed = true;
      if (this.isEdit) {
        this.restoreOriginData();
      }
      this.$emit("close");
    },
  },
};
</script>
<style scoped>
#shopUserDetailCard {
  width: 45vw;
  height: 100vh;
  overflow: auto;
}

#shopUserDetailCard::-webkit-scrollbar {
  display: none;
}

#shopUserDetailCard hr {
  margin-top: 10px;
  margin-bottom: 20px;
}

.form-input {
  border: 1px solid #d9d9d9 !important;
  border-radius: 2px;
  width: 100%;
  height: 32px;
  margin-top: 5px;
}

.form-group {
  margin-bottom: 20px;
}

.form-input :last-of-type {
  margin-bottom: 0;
}

.info-box {
  width: 100%;
  background-color: #f1f5f5;
  border: 1px solid #d9d9d9;
  padding: 7px 10px;
  font-size: 12px !important;
  font-style: normal;
  font-weight: 400;
  line-height: 18px;
}

.shop-user-info-container h5,
.shop-user-info-edit-container label {
  font-size: 12px;
  font-style: normal;
  font-weight: 700;
  line-height: 18px;
  color: #000000;
}

.validation-error {
  font-size: 15px;
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
