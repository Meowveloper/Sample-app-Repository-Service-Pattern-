<template>
  <div @click="closePopupOnClickOutside">
    <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
    <div class="fixed inset-0 flex items-center justify-center z-50">
      <div
        id="shopUserCreateCard"
        class="bg-white p-8 rounded-lg shadow-md z-10"
      >
        <!-- Card content goes here -->
        <div>
          <div class="flex justify-between items-center">
            <h2 class="card-title">CREATE PROBLEM</h2>
            <iconify-icon
              icon="gg:close"
              style="color: #aaa"
              width="20"
              @click="closePopup"
            ></iconify-icon>
          </div>
          <hr class="border-main" />
          <div class="shop-user-create-form">
            <div class="form-group">
              <label for="problem_image" class="d-flex"
                >PROBLEM IMAGE
                <span class="ms-2"> (RECOMMEND SIZE : 600 x 600)</span></label
              >
              <DxFileUploader
                :accept="'image/*'"
                v-model="image"
                :on-uploaded="onUploaded"
                :upload-headers="{ 'X-CSRF-TOKEN': csrf }"
                upload-url="/api/upload-problem-image"
                @input="onImageChange"
              ></DxFileUploader>
              <span v-if="isValid" class="validation-error mt-2">
              {{
                validationErrors && validationErrors.imageUrl
                  ? validationErrors.imageUrl[0]
                  : ""
              }}
            </span>
            </div>

            <div class="form-group">
              <label for="category_name" class="mb-1">CATEGORY NAME</label>
              <DxSelectBox
                :items="categories"
                :displayExpr="'name'"
                :valueExpr="'id'"
                :placeholder="'Select a category'"
                v-model="problem.selectedCategory"
                class="mb-1"
              >
              </DxSelectBox>
              <span v-if="isValid" class="validation-error mt-2">
                {{
                  validationErrors && validationErrors.selectedCategory
                    ? validationErrors.selectedCategory[0]
                    : ""
                }}
              </span>
            </div>

            <div class="form-group" v-for="index in 4" :key="index">
              <label :for="'option_' + index" class="mb-1"
                >{{ getAnswerLabel(index) }} ANSWER OPTION</label
              >
              <br />
              <div class="d-flex">
                <div class="text-box">
                  <DxTextBox v-model="problem['option_' + index]" class="mb-1"/>
                  <span v-if="isValid" class="validation-error mt-2">
                        {{
                            validationErrors && validationErrors["option_" + index]
                            ? validationErrors["option_" + index][0]
                            : ""
                        }}
                    </span>
                </div>

                <div class="radio-group ms-2 mt-2">
                  <input
                    type="radio"
                    :id="'correct_option_' + index"
                    :name="'correct_option'"
                    :value="index"
                    v-model="problem.correct_option"
                  />
                </div>
              </div>
            </div>
            <!-- <span v-if="isValid" class="validation-error mt-2">
                {{
                  validationErrors && validationErrors.selectedCategory
                    ? validationErrors.correct_option
                    : ""
                }}
              </span> -->
          </div>
        </div>
        <div class="flex justify-end mt-3">
          <!-- Close button -->
          <button
            @click="closePopup"
            class="text-main border border-main w-70px h-8 rounded"
          >
            Cancel
          </button>
          <!-- Create button -->
          <button
            @click="createProblem"
            class="text-white bg-main border border-main w-70px h-8 rounded ml-3"
          >
            Save
          </button>
        </div>
            <Loader v-if="isLoading && !isSuccess"/>
            <SuccessPopUp v-if="!isLoading && isSuccess" />
      </div>
    </div>
  </div>
</template>

<script>
import { DxPopup } from "devextreme-vue/popup";
import { DxRadio, DxRadioGroup } from "devextreme-vue/radio-group";
import DxTextBox from "devextreme-vue/text-box";
import Loader from "../../components/general/Loader.vue";
import SuccessPopUp from "../../components/general/SuccessPopUp.vue";
import DxFileUploader from "devextreme-vue/file-uploader";
import DxSelectBox from "devextreme-vue/select-box";

export default {
  components: {
    DxPopup,
    DxTextBox,
    DxRadio,
    DxRadioGroup,
    DxFileUploader,
    Loader,
    SuccessPopUp,
    DxSelectBox,
  },
  data() {
    return {
      image: [],
      categories: [],
      problemOptions: [],
      problem: {
        id: "",
        selectedCategory: "",
        correct_option: "",
        option_1: "",
        option_2: "",
        option_3: "",
        option_4: "",
      },
      isValid : true,
      validationErrors: [],
      csrf: "",
      isLoading: false,
      isSuccess: false,
    };
  },
  methods: {
    getAnswerLabel(index) {
      const labels = ["FIRST", "SECOND", "THIRD", "FOURTH"];
      return labels[index - 1] || "";
    },
    clearValidationErrors(field) {
      this.validationErrors[field] = null;
    },
    closePopupOnClickOutside(event) {
      // Check if the click event occurred outside of the popup
      if (
        !this.$el.querySelector("#shopUserCreateCard").contains(event.target)
      ) {
        // Close the popup by calling your closePopup method
        this.closePopup();
      }
    },
    onImageChange() {
      // Handle image change explicitly if needed
      console.log("Image changed:", this.image);
      // Additional actions can be performed here
    },
    onUploaded(e) {
      const responseData = JSON.parse(e.request.response);
      this.image = [responseData.image];
      console.log(this.image, "image uploaded");
    },
    async createProblem() {
      this.isLoading = true;
      const apiUrl = "/api/save-problem-data";
      const requestData = {
        imageUrl: this.image,
        selectedCategory: this.problem.selectedCategory,
        correct_option: this.problem["option_" + this.problem.correct_option],
        option_1: this.problem.option_1,
        option_2: this.problem.option_2,
        option_3: this.problem.option_3,
        option_4: this.problem.option_4,
      };
      const csrfToken = this.csrf;
      const response = await fetch(apiUrl, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
      });

      const encodedData = await response.json();
      if (!response.ok) {
        this.isLoading = false;
        if(encodedData.errors.correct_option){
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
            title: encodedData.errors.correct_option
            });
        }
        this.validationErrors = encodedData.errors;

      }

      setTimeout(() => {
        this.isLoading = false;
        if (encodedData.status.toLowerCase() == "success") {
          this.isSuccess = true;
          setTimeout(() => {
            this.isSuccess = false;
            this.closePopup();
          }, 1000);
        }
      }, 1000);

      this.$emit("refreshProblemData");
    },
    closePopup() {
        this.isValid = true;
      this.$emit("close");
    },
    async getCategoryList() {
      const response = await fetch("/api/get-all-categories");
      const encodedData = await response.json();

      this.categories = encodedData.data;
    },
  },
  mounted() {
    this.getCategoryList();
    const csrfToken = document.head.querySelector(
      'meta[name="_token"]'
    ).content;
    this.csrf = csrfToken;
  },
};
</script>

<style scoped>
#shopUserCreateCard {
  width: 710px;
  height: 300x;
  overflow: auto;
}

#shopUserCreateCard::-webkit-scrollbar {
  display: none;
}

#shopUserCreateCard hr {
  margin-top: 10px;
  margin-bottom: 20px;
}

.shop-user-create-form label {
  font-size: 12px;
  font-style: normal;
  font-weight: 700;
  line-height: 18px;
  color: #000000;
}

.validation-error {
  font-size: 15px;
}

.d-flex {
  display: flex;
}

.text-box {
  width: 70vh;
}

input[type="radio"] {
  transform: scale(1.2); /* You can adjust the scale value as needed */
}
</style>
