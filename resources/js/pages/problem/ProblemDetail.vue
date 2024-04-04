<template>
    <div @click="closePopupOnClickOutside">
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-end z-50">
            <div id="shopUserDetailCard" class="bg-white p-8 rounded-lg shadow-md z-10">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between">
                        <h2 class="card-title">PROBLEM DETAIL</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main" />
                    <div class="detail-container">
                        <div class="flex justify-end" v-if="!isEdit">
                            <!-- Edit button -->
                            <button @click.stop="editProblem"
                                class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Edit
                            </button>
                        </div>
                        <div class="flex justify-end" v-if="isEdit">
                            <!-- Close button -->
                            <button @click.stop="cancelEdit" class="text-main border border-main w-70px h-8 rounded">
                                Cancel
                            </button>
                            <!-- Create button -->
                            <button @click.stop="updateProblem"
                                class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Save
                            </button>
                        </div>

                        <div class="shop-user-info-container" v-if="!isEdit">
                            <div class="mb-5">
                                <h5 class="mb-1">PROBLEM IMAGE</h5>
                                <div class="">
                                    <img :src="problemDetail.imageUrl" alt="" width="450" height="450" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>CATEGORY NAME</h5>
                                <div class="info-box">
                                    {{ problemDetail.category_name }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>CORRECT ANSWER OPTION</h5>
                                <div class="info-box">
                                    {{ problemDetail.correct_option }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>FIRST ANSWER OPTION</h5>
                                <div class="info-box">
                                    {{ problemOptions.option_1 }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>SECOND ANSWER OPTION</h5>
                                <div class="info-box">
                                    {{ problemOptions.option_2 }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>THIRD ANSWER OPTION</h5>
                                <div class="info-box">
                                    {{ problemOptions.option_3 }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>FOURTH ANSWER OPTION</h5>
                                <div class="info-box">
                                    {{ problemOptions.option_4 }}
                                </div>
                            </div>
                        </div>
                        <div class="shop-user-info-edit-container" v-if="isEdit">
                            <div class="form-group">
                                <label for="problem_image" class="d-flex">PROBLEM IMAGE
                                    <span class="ms-2"> (RECOMMEND SIZE : 600 x 600)</span></label>
                                <DxFileUploader :accept="'image/*'" v-model="image" :on-uploaded="onUploaded"
                                    :upload-headers="{ 'X-CSRF-TOKEN': csrf }" upload-url="/api/upload-problem-image"
                                    @input="onImageChange"></DxFileUploader>
                                    <span v-if="!isValid" class="validation-error mt-2">
                                        {{ validationErrors && validationErrors.imageUrl ? validationErrors.imageUrl : "" }}
                                    </span>

                            </div>
                            <div class="form-group">
                                <label for="name">CATEGORY NAME</label>
                                <DxSelectBox :items="categories" :displayExpr="'name'" :valueExpr="'id'"
                                    v-model="problemDetail.category_id">
                                </DxSelectBox>
                                <span v-if="!isValid" class="validation-error mt-2">
                                    {{ validationErrors && validationErrors.category_id ? validationErrors.category_id : "" }}
                                </span>

                            </div>
                            <div class="form-group" v-for="(option, index) in options" :key="index">
                                <label :for="'option_' + index" class="mb-1">{{ getAnswerLabel(index) }} ANSWER OPTION</label>
                                <br />
                                <div class="d-flex">
                                    <div class="text-box">
                                        <DxTextBox v-model="option.content" />
                                        <span v-if="isValid" class="validation-error mt-2">
                                            {{
                                                validationErrors && validationErrors["option_" + (index + 1)]
                                                ? validationErrors["option_" + (index + 1)][0]
                                                : ""
                                            }}
                                        </span>
                                    </div>
                                    <input
                                        type="radio"
                                        :id="'correct_option_' + index"
                                        :name="'correct_option'"
                                        :value="option.id"
                                        v-model="problemDetail.correct_option_id"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <DxPopup v-if="isLoading" :drag-enabled="false">
            <div class="popup-content">
                
            </div>
        </DxPopup>
        <DxPopup v-if="isSuccess" :drag-enabled="false">
            <div class="popup-content">
                
            </div>
        </DxPopup> -->
        <LoaderVue v-if="isLoading"/>
        <SuccessPopUp v-if="isSuccess"/>
    </div>
</template>

<script>
import DxTextBox from "devextreme-vue/text-box";
import DxSelectBox from "devextreme-vue/select-box";
import { DxPopup } from "devextreme-vue/data-grid";
import LoaderVue from "../../components/general/Loader.vue";
import SuccessPopUp from "../../components/general/SuccessPopUp.vue";
import ProblemOptionDetail from "../problem-options/ProblemOptionsDetail.vue";
import DxFileUploader from "devextreme-vue/file-uploader";

export default {
    components: {
        DxTextBox,
        DxSelectBox,
        DxPopup,
        LoaderVue,
        SuccessPopUp,
        ProblemOptionDetail,
        DxFileUploader,
    },
    props: ["problemDetail"],
    data() {
        return {
            csrf: "",
            originData: null,
            isEdit: false,
            isDetail: true,
            isShowDetail: false,
            image: [],
            categories: [],
            options: [],
            problemOptions: {
                option_1: "",
                option_2: "",
                option_3: "",
                option_4: "",
            },
            validationErrors: [],
            isValid : true,
            isLoading: false,
            isSuccess: false,
        };
    },
    methods: {
        getAnswerLabel(index) {
            const labels = ["FIRST", "SECOND", "THIRD", "FOURTH"];
            return labels[index] || "";
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
        restoreOriginData() {
            this.problemDetail.id = this.originData.id;
            this.problemDetail.category_id = this.originData.category_id;
            this.problemDetail.imageUrl = this.originData.imageUrl;
            this.problemDetail.expected_time_to_complete =
            this.originData.expected_time_to_complete;
            this.problemDetail.correct_option_id = this.originData.correct_option_id;
            this.problemOptions.option_1 = this.problemOptions[0].content;
            this.problemOptions.option_2 = this.problemOptions[1].content;
            this.problemOptions.option_3 = this.problemOptions[2].content;
            this.problemOptions.option_4 = this.problemOptions[3].content;
        },
        cancelEdit() {
            this.isEdit = false;
            this.restoreOriginData();
            this.validationPassed = true;
        },
        editProblem() {
            this.isEdit = true;
            this.validationPassed = true;
            if (!this.originData) {
                this.originData = { ...this.problemDetail };
            }
            if (this.categories.length == 0) {
                this.getCategories();
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
                this.validationPassed = true;
                this.closePopup();
            }
        },
        async updateProblem() {
            this.isLoading = true;
            const apiUrl = `/api/update-problem-data/${this.problemDetail.id}`;
            const imageUrl = this.image.length > 0 ? this.image.map(url => url.substring(url.lastIndexOf('/') + 1)) : [this.problemDetail.imageUrl.substring(this.problemDetail.imageUrl.lastIndexOf('/') + 1)];
            const requestData = {
                problem_id: this.problemDetail.id,
                category_id: this.problemDetail.category_id,
                expected_time_to_complete: this.problemDetail.expected_time_to_complete,
                imageUrl: imageUrl,
                correct_option_id: this.problemDetail.correct_option_id,
                option_1: this.options[0]?.content || '',
                option_2: this.options[1]?.content || '',
                option_3: this.options[2]?.content || '',
                option_4: this.options[3]?.content || '',
            };

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            try {
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
                    if (encodedData.errors.correct_option) {
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
                    console.log(this.validationErrors);
                }

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

                this.$emit("refreshProblemData");
            } catch (error) {
                console.error("Error updating problem:", error);
                this.isLoading = false;
            }
        },
        async getCategories() {
            try {
                const response = await fetch("/api/get-all-categories");
                const data = await response.json();
                if (data) {
                    this.categories = data.data;
                }
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        async getProblemOptions() {
            const problem_id = this.problemDetail.id;
            const response = await fetch(`/api/get-problem-options/${problem_id}`);
            const data = await response.json();
            this.options = data.data;
            const formattedData = data.data.map((problemOptions, index) => ({
                id: problemOptions.id,
                content: problemOptions.content,
            }));
            this.problemOptions = formattedData;
            this.problemOptions.option_1 = this.problemOptions[0].content;
            this.problemOptions.option_2 = this.problemOptions[1].content;
            this.problemOptions.option_3 = this.problemOptions[2].content;
            this.problemOptions.option_4 = this.problemOptions[3].content;
        },
        closePopup() {
           this.isValid = true;
            if (this.isEdit) {
                this.restoreOriginData();
            }
            this.$emit("close");
        },
    },
    mounted() {
        this.getProblemOptions();
        const csrfToken = document.head.querySelector(
            'meta[name="_token"]'
        ).content;
        this.csrf = csrfToken;
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

.edit-box {
    width: 100%;
    background-color: #f1f5f5;
    border: 1px solid #d9d9d9;
    padding: 7px 10px;
    font-size: 12px !important;
    font-style: normal;
    font-weight: 500;
    line-height: 28px;
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

.d-flex {
    display: flex;
}

.text-box {
    width: 98vh;
}

input[type="radio"] {
    transform: scale(1.2);
    margin-left: 10px;
    /* You can adjust the scale value as needed */
}
</style>

