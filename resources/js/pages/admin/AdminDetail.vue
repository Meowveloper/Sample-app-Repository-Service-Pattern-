<template>
    <div @click="closePopupOnClickOutside">
        <div class="fixed inset-0 bg-black opacity-50 z-50"></div>
        <div class="fixed inset-0 flex items-center justify-end z-50">
            <div id="shopUserDetailCard" class="bg-white p-8 rounded-lg shadow-md z-10">
                <!-- Card content goes here -->
                <div>
                    <div class="flex justify-between">
                        <h2 class="card-title">ADMIN DETAIL</h2>
                        <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closePopup"></iconify-icon>
                    </div>
                    <hr class="border-main">
                    <div class="detail-container">
                        <div class="flex justify-end" v-if="!isEdit">
                            <!-- Edit button -->
                            <button @click.stop="editAdmin" class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Edit
                            </button>
                        </div>
                        <div class="flex justify-end" v-if="isEdit">
                            <!-- Close button -->
                            <button @click.stop="cancelEdit" class="text-main border border-main w-70px h-8 rounded">
                                Cancel
                            </button>
                            <button @click.stop="updateAdmin" class="text-white bg-main border border-main w-70px h-8 rounded ml-3">
                                Update
                            </button>
                        </div>
                        <div class="shop-user-info-container" v-if="!isEdit">
                            <div class="mb-5">
                                <h5> NAME</h5>
                                <div class="info-box">
                                    {{ adminDetail.name }}
                                </div>
                            </div>
                            <div class="mb-5">
                                <h5>EMAIL</h5>
                                <div class="info-box">
                                    {{ adminDetail.email }}
                                </div>
                            </div>
                        </div>

                        <div class="shop-user-info-edit-container" v-if="isEdit">
                            <div class="form-group">
                                <label for="name">NAME</label>
                                <DxTextBox
                                    class="form-input mr-8"
                                    id="name"
                                    v-model="adminDetail.name"
                                    ref="name"
                                />
                                <span v-if="isValid" class="validation-error mt-1">
                                    {{ validationErrors && validationErrors.name ? validationErrors.name[0] : "" }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="email">EMAIL</label>
                                <DxTextBox
                                    class="form-input mr-8"
                                    id="name"
                                    v-model="adminDetail.email"
                                    ref="email"
                                />
                                <span v-if="isValid" class="validation-error mt-1">
                                    {{ validationErrors && validationErrors.email ? validationErrors.email[0] : "" }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">PASSWORD</label>
                                <DxTextBox
                                    class="form-input mr-8"
                                    id="name"
                                    v-model="password"
                                    ref="password"
                                />
                                <span v-if="isValid" class="validation-error mt-1">
                                    {{ password_error }}
                                </span>
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
import DxTextBox from 'devextreme-vue/text-box';
import { DxPopup } from 'devextreme-vue/data-grid';
import LoaderVue from '../../components/general/Loader.vue';
import SuccessPopUp from '../../components/general/SuccessPopUp.vue';

export default {
    components: {
        DxTextBox,
        DxPopup,
        LoaderVue,
        SuccessPopUp
    },
    props: ['adminDetail'],
    data() {
        return {
            originData: null,
            isEdit: false,
            validationErrors: {
                name : null,
            },
            password : "",
            password_confirmation : "",
            isValid : true,
            validationErrors : [],
            password_error : "",
            isLoading: false,
            isSuccess: false,
        }
    },
    methods: {
        restoreOriginData() {
            this.adminDetail.id = this.originData.id
            this.adminDetail.name = this.originData.name;
            this.adminDetail.email = this.originData.email;
            this.adminDetail.password = this.originData.password;
        },
        cancelEdit() {
            this.isEdit = false;
            this.restoreOriginData();
        },
        editAdmin() {
            this.isEdit = true;
            if(!this.originData) {
                this.originData = {...this.adminDetail};
            }
        },
        closePopupOnClickOutside(event) {
            // Check if the click event occurred outside of the popup
            if (!this.$el.querySelector("#shopUserDetailCard").contains(event.target)) {
                // Close the popup if user clicked outside
                if(this.isEdit) {
                    this.restoreOriginData();
                }
                this.closePopup();
            }
        },
        async updateAdmin() {
            this.isLoading = true;
            const apiUrl = `/api/update-admin-data/${this.adminDetail.id}`;
            const requestData = {
                id : this.adminDetail.id,
                name: this.adminDetail.name,
                email : this.adminDetail.email,
                password : this.password,
            };

            const csrfToken = document.head.querySelector('meta[name="_token"]').content;

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });

            const encodedData = await response.json();

            if(response.status === 'error'){
                this.isLoading = false;
                this.password_error = encodedData.message;
            }

            if(!response.ok){
                this.isLoading = false;
                this.validationErrors = encodedData.errors;
                console.log(this.validationErrors);
            }

            setTimeout(() => {
                this.isLoading = false;
                if (encodedData.status.toLowerCase() === 'success') {
                    this.isSuccess = true;
                    setTimeout(() => {
                        this.isSuccess = false;
                        this.closePopup();
                    }, 1000);
                }
            }, 1000);

            this.$emit('refreshAdminData');
        },
        closePopup() {
            this.isValid = true;
            if(this.isEdit) {
                this.restoreOriginData();
            }
            this.$emit('close');
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
    border: 1px solid #D9D9D9 !important;
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
    background-color: #F1F5F5;
    border: 1px solid #D9D9D9;
    padding: 7px 10px;
    font-size: 12px !important;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
}

.shop-user-info-container h5, .shop-user-info-edit-container label {
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 18px;
    color: #000000;
}

.validation-error{
    font-size: 15px;
}

</style>
