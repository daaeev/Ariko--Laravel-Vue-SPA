<template>
    <div class="container container-padding mt-10">
        <div class="row">
            <div class="col-md-2">
                <dialog-button v-model:show="createUserShow">Create user</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="createPhotoWorkShow">Create photo work</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="addImagesToWorkShow">Add photos to work</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="createPostShow">Create post</dialog-button>
            </div>
        </div>
    </div>

    <!-- Dialogs -->
        <my-dialog v-model:show="createUserShow">
            <alert v-if="createUserSuccess" :type="'success'">{{createUserSuccess}}</alert>
            <alert v-if="createUserFailed" :type="'danger'">{{createUserFailed}}</alert>
            <create-user-form @submit="submitCreateUserForm"></create-user-form>
        </my-dialog>

        <my-dialog v-model:show="createPhotoWorkShow">
            <alert v-if="createPhotoWorkSuccess" :type="'success'">{{createPhotoWorkSuccess}}</alert>
            <alert v-if="createPhotoWorkFailed" :type="'danger'">{{createPhotoWorkFailed}}</alert>
            <create-photo-work-form @submit="submitCreatePhotoWorkForm"></create-photo-work-form>
        </my-dialog>

        <my-dialog v-model:show="addImagesToWorkShow">
            <alert v-if="addImagesSuccess" :type="'success'">{{addImagesSuccess}}</alert>
            <alert v-if="addImagesFailed" :type="'danger'">{{addImagesFailed}}</alert>
            <add-images-to-work-form @submit="submitAddImagesToWorkForm"></add-images-to-work-form>
        </my-dialog>

        <my-dialog v-model:show="createPostShow">
            <alert v-if="createPostSuccess" :type="'success'">{{createPostSuccess}}</alert>
            <alert v-if="createPostFailed" :type="'danger'">{{createPostFailed}}</alert>
            <create-post-form @submit="submitCreatePostForm"></create-post-form>
        </my-dialog>
    <!-- Dialogs -->
</template>

<script>
import DialogButton from '../../components/admin/UI/DialogButton.vue'
import MyDialog from '../../components/admin/UI/Dialog.vue'
import CreateUserForm from '../../components/admin/crudforms/CreateUserForm.vue'
import Alert from '../../components/UI/Alert.vue';
import axiosUserAPI from '../../logic/api/crud/User';
import axiosPostAPI from '../../logic/api/crud/Post';
import axiosPhotoWorkAPI from '../../logic/api/crud/PhotoWork';
import CreatePhotoWorkForm from '../../components/admin/crudforms/CreatePhotoWorkForm.vue';
import AddImagesToWorkForm from '../../components/admin/crudforms/AddImagesToWorkForm.vue';
import CreatePostForm from '../../components/admin/crudforms/CreatePostForm.vue'

export default {
    components: {
        DialogButton, 
        MyDialog, 
        CreateUserForm, 
        Alert, 
        CreatePhotoWorkForm, 
        AddImagesToWorkForm,
        CreatePostForm
    },

    data() {
        return {
            // CREATE USER FORM
            createUserShow: false,
            createUserSuccess: '',
            createUserFailed: '',

            // CREATE PHOTO WORK FORM
            createPhotoWorkShow: false,
            createPhotoWorkSuccess: '',
            createPhotoWorkFailed: '',

            // ADD IMAGES TO WORK FORM
            addImagesToWorkShow: false,
            addImagesSuccess: '',
            addImagesFailed: '',

            // CREATE POST FORM
            createPostShow: false,
            createPostSuccess: '',
            createPostFailed: '',
        };
    },

    methods: {
        // Форма создание пользователя
        async submitCreateUserForm(formData)
        {
            this.createUserSuccess = '';
            this.createUserFailed = '';

            await axiosUserAPI.createUser(
                formData,
                () => this.createUserSuccess = 'User create success',
                axiosError => this.createUserFailed = axiosError.response?.data.message ?? 'User create failed',
            );

            setTimeout(() => {
                this.createUserSuccess = '';
                this.createUserFailed = '';
            }, 5000);
        },

        // Форма создания работы (фотографии)
        async submitCreatePhotoWorkForm(formData)
        {
            this.createPhotoWorkSuccess = '';
            this.createPhotoWorkFailed = '';

            await axiosPhotoWorkAPI.createPhotoWork(
                formData,
                axiosRes => this.createPhotoWorkSuccess = 'Work create success (id: ' + axiosRes.data.id + ')',
                axiosError => this.createPhotoWorkFailed = axiosError.response?.data.message ?? 'Work create failed',
            );

            setTimeout(() => {
                this.createPhotoWorkSuccess = '';
                this.createPhotoWorkFailed = '';
            }, 5000);
        },

        // Форма для добавления фотографий к работе
        async submitAddImagesToWorkForm(formData)
        {
            this.addImagesSuccess = '';
            this.addImagesFailed = '';

            await axiosPhotoWorkAPI.addImagesToWork(
                formData,
                () => this.addImagesSuccess = 'Photos added success',
                axiosError => this.addImagesFailed = axiosError.response?.data.message ?? 'Photos add failed',
            );

            setTimeout(() => {
                this.addImagesSuccess = '';
                this.addImagesFailed = '';
            }, 5000);
        },

        // Форма создания поста
        async submitCreatePostForm(formData)
        {
            this.createPostSuccess = '';
            this.createPostFailed = '';

            await axiosPostAPI.createPost(
                formData,
                () => this.createPostSuccess = 'Photos added success',
                axiosError => this.createPostFailed = axiosError.response?.data.message ?? 'Photos add failed',
            );

            setTimeout(() => {
                this.createPostSuccess = '';
                this.createPostFailed = '';
            }, 5000);
        }
    },
}
</script>

<style scoped>

</style>
