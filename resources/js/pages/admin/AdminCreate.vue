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
              <h2 class="card-title">CREATE ADMIN</h2>
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
                <label for="name">NAME</label>
                <DxTextBox
                  class="form-input mr-8 mb-2"
                  id="name"
                  v-model="admin.name"
                />
                <span v-if="isValid" class="validation-error mt-2">
                  {{
                    validationErrors && validationErrors.name
                      ? validationErrors.name[0]
                      : ""
                  }}
                </span>
              </div>
              <div class="form-group">
                <label for="email">EMAIL</label>
                <DxTextBox
                  class="form-input mr-8 mb-2"
                  id="email"
                  v-model="admin.email"
                />
                <span v-if="isValid" class="validation-error mt-2">
                  {{
                    validationErrors && validationErrors.email
                      ? validationErrors.email[0]
                      : ""
                  }}
                </span>
              </div>
              <div class="form-group">
                <label for="password">PASSWORD</label>
                <DxTextBox
                  class="form-input mr-8 mb-2"
                  id="password"
                  v-model="admin.password"
                />
                <span v-if="isValid" class="validation-error mt-2">
                  {{
                    validationErrors && validationErrors.password
                      ? validationErrors.password[0]
                      : ""
                  }}
                </span>
              </div>
              <div class="form-group">
                <label for="name">CONFIRM PASSWORD</label>
                <DxTextBox
                  class="form-input mr-8 mb-2"
                  id="name"
                  v-model="admin.confirm_password"
                />
                <span v-if="isValid" class="validation-error mt-2">
                  {{
                    validationErrors && validationErrors.confirm_password
                      ? validationErrors.confirm_password[0]
                      : ""
                  }}
                </span>
              </div>
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
              @click="createAdmin"
              class="text-white bg-main border border-main w-70px h-8 rounded ml-6"
            >
              Save
            </button>
          </div>
          <!-- <DxPopup  :drag-enabled="false">
            <div class="popup-content">

            </div>
          </DxPopup>
          <DxPopup  :drag-enabled="false">
            <div class="popup-content">

            </div>
          </DxPopup> -->
          <Loader v-if="isLoading && !isSuccess"/>
          <SuccessPopUp v-if="isSuccess && !isLoading"/>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { DxPopup } from "devextreme-vue/popup";
  import DxTextBox from "devextreme-vue/text-box";
  import Loader from "../../components/general/Loader.vue";
  import SuccessPopUp from "../../components/general/SuccessPopUp.vue";

  export default {
    components: {
      DxPopup,
      DxTextBox,
      Loader,
      SuccessPopUp,
    },
    data() {
      return {
        admin: {
          id: null,
          name: "",
          email : "",
          password : "",
          confirm_password : ""
        },
        isLoading: false,
        isSuccess: false,
        isValid: true,
        validationErrors: [],
      };
    },
    methods: {
      closePopupOnClickOutside(event) {
        // Check if the click event occurred outside of the popup
        if (
          !this.$el.querySelector("#shopUserCreateCard").contains(event.target)
        ) {
          // Close the popup by calling your closePopup method
          this.closePopup();
        }
      },
      async createAdmin() {
        try {
          this.isLoading = true;
          const apiUrl = "/api/save-admin-data";
          const requestData = {
            name: this.admin.name,
            email : this.admin.email,
            password : this.admin.password,
            password_confirmation : this.admin.confirm_password
          };

          const csrfToken = document.head.querySelector(
            'meta[name="_token"]'
          ).content;

          const response = await fetch(apiUrl, {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
          });

          const encodedData = await response.json();

          // Show validation error immediately on response
          if (!response.ok) {
            this.isLoading = false;
            this.validationErrors = encodedData.errors;
          }
          await new Promise((resolve) => setTimeout(resolve, 1000))
          this.isLoading = false;

          if (response.ok && encodedData.status.toLowerCase() === "success") {
            this.isSuccess = true;
            await new Promise((resolve) => setTimeout(resolve, 1000));

            this.isSuccess = false;
            this.closePopup();

            this.$emit("refreshAdminData");
          }
        } catch (error) {
          console.error("An error occurred:", error);
        }
      },

      closePopup() {
        this.isValid = true;
        this.$emit("close");
      },
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
  </style>
