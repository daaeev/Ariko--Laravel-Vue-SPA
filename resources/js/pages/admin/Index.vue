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

            <div class="col-md-2">
                <dialog-button v-model:show="createVideoWorkShow">Create video work</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="deletePhotoWorkShow">Delete photo work</dialog-button>
            </div>

        </div>

        <div class="row mt-5">
            <div class="col-md-2">
                <dialog-button v-model:show="deleteVideoWorkShow">Delete video work</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="deletePostShow">Delete post</dialog-button>
            </div>

            <div class="col-md-2">
                <dialog-button v-model:show="deleteUserShow">Delete user</dialog-button>
            </div>
        </div>

        <div class="text-center mt-10">
            <router-link :to="{name: 'admin.messages'}">Go to messages page -></router-link>
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

        <my-dialog v-model:show="createVideoWorkShow">
            <alert v-if="createVideoWorkSuccess" :type="'success'">{{createVideoWorkSuccess}}</alert>
            <alert v-if="createVideoWorkFailed" :type="'danger'">{{createVideoWorkFailed}}</alert>
            <create-video-work-form @submit="submitCreateVideoWorkForm"></create-video-work-form>
        </my-dialog>

        <my-dialog v-model:show="deletePhotoWorkShow">
            <alert v-if="deletePhotoWorkSuccess" :type="'success'">{{deletePhotoWorkSuccess}}</alert>
            <alert v-if="deletePhotoWorkFailed" :type="'danger'">{{deletePhotoWorkFailed}}</alert>
            <delete-form @submit="submitDeletePhotoWorkForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deletePostShow">
            <alert v-if="deletePostSuccess" :type="'success'">{{deletePostSuccess}}</alert>
            <alert v-if="deletePostFailed" :type="'danger'">{{deletePostFailed}}</alert>
            <delete-form @submit="submitDeletePostForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deleteVideoWorkShow">
            <alert v-if="deleteVideoWorkSuccess" :type="'success'">{{deleteVideoWorkSuccess}}</alert>
            <alert v-if="deleteVideoWorkFailed" :type="'danger'">{{deleteVideoWorkFailed}}</alert>
            <delete-form @submit="submitDeleteVideoWorkForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deleteUserShow">
            <alert v-if="deleteUserSuccess" :type="'success'">{{deleteUserSuccess}}</alert>
            <alert v-if="deleteUserFailed" :type="'danger'">{{deleteUserFailed}}</alert>
            <delete-form @submit="submitDeleteUserForm"></delete-form>
        </my-dialog>
    <!-- Dialogs -->
</template>

<script>
import DialogButton from '../../components/admin/UI/DialogButton.vue'
import MyDialog from '../../components/admin/UI/Dialog.vue'
import CreateUserForm from '../../components/admin/crudforms/CreateUserForm.vue'
import Alert from '../../components/UI/Alert.vue';
import axiosUserAPI from '../../logic/api/admin/User';
import axiosPostAPI from '../../logic/api/admin/Post';
import axiosPhotoWorkAPI from '../../logic/api/admin/PhotoWork';
import CreatePhotoWorkForm from '../../components/admin/crudforms/CreatePhotoWorkForm.vue';
import AddImagesToWorkForm from '../../components/admin/crudforms/AddImagesToWorkForm.vue';
import CreatePostForm from '../../components/admin/crudforms/CreatePostForm.vue'
import axiosVideoWorkAPI from '../../logic/api/admin/VideoWork';
import CreateVideoWorkForm from '../../components/admin/crudforms/CreateVideoWorkForm.vue';
import DeleteForm from '../../components/admin/crudforms/DeleteByIdForm.vue';

export default {
    components: {
        DialogButton,
        MyDialog,
        CreateUserForm,
        Alert,
        CreatePhotoWorkForm,
        AddImagesToWorkForm,
        CreatePostForm,
        CreateVideoWorkForm,
        DeleteForm
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

            // CREATE VIDEO WORK FORM
            createVideoWorkShow: false,
            createVideoWorkSuccess: '',
            createVideoWorkFailed: '',

            // DELETE PHOTO WORK FORM
            deletePhotoWorkShow: false,
            deletePhotoWorkSuccess: '',
            deletePhotoWorkFailed: '',

            // DELETE POST FORM
            deletePostShow: false,
            deletePostSuccess: '',
            deletePostFailed: '',

            // DELETE VIDEO WORK FORM
            deleteVideoWorkShow: false,
            deleteVideoWorkSuccess: '',
            deleteVideoWorkFailed: '',

            // DELETE USER FORM
            deleteUserShow: false,
            deleteUserSuccess: '',
            deleteUserFailed: '',
        };
    },

    methods: {
        // Форма удаления пользователя
        async submitDeleteUserForm(formData) {
            this.deleteUserSuccess = '';
            this.deleteUserFailed = '';

            const user_id = formData.get('id');

            await axiosUserAPI.deleteUser(
                user_id,
                () => this.deleteUserSuccess = 'User deleted success',
                axiosError => this.deleteUserFailed = axiosError.response?.data.message ?? 'User deleted failed',
            );

            setTimeout(() => {
                this.deleteUserSuccess = '';
                this.deleteUserFailed = '';
            }, 5000);
        },

        // Форма удаления поста
        async submitDeletePostForm(formData) {
            this.deletePostSuccess = '';
            this.deletePostFailed = '';

            const post_id = formData.get('id');

            await axiosPostAPI.deletePost(
                post_id,
                () => this.deletePostSuccess = 'Post deleted success',
                axiosError => this.deletePostFailed = axiosError.response?.data.message ?? 'Post deleted failed',
            );

            setTimeout(() => {
                this.deletePostSuccess = '';
                this.deletePostFailed = '';
            }, 5000);
        },

        // Форма удаления работы (видео)
        async submitDeleteVideoWorkForm(formData) {
            this.deleteVideoWorkSuccess = '';
            this.deleteVideoWorkFailed = '';

            const work_id = formData.get('id');

            await axiosVideoWorkAPI.deleteWork(
                work_id,
                () => this.deleteVideoWorkSuccess = 'Work deleted success',
                axiosError => this.deleteVideoWorkFailed = axiosError.response?.data.message ?? 'Work deleted failed',
            );

            setTimeout(() => {
                this.deleteVideoWorkSuccess = '';
                this.deleteVideoWorkFailed = '';
            }, 5000);
        },

        // Форма удаления работы (фотографии)
        async submitDeletePhotoWorkForm(formData) {
            this.deletePhotoWorkSuccess = '';
            this.deletePhotoWorkFailed = '';

            const work_id = formData.get('id');

            await axiosPhotoWorkAPI.deleteWork(
                work_id,
                () => this.deletePhotoWorkSuccess = 'Work deleted success',
                axiosError => this.deletePhotoWorkFailed = axiosError.response?.data.message ?? 'Work deleted failed',
            );

            setTimeout(() => {
                this.deletePhotoWorkSuccess = '';
                this.deletePhotoWorkFailed = '';
            }, 5000);
        },

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
        },

        // Форма создания работы (видео)
        async submitCreateVideoWorkForm(formData)
        {
            this.createVideoWorkSuccess = '';
            this.createVideoWorkFailed = '';

            await axiosVideoWorkAPI.createVideoWork(
                formData,
                () => this.createVideoWorkSuccess = 'Work create success',
                axiosError => this.createVideoWorkFailed = axiosError.response?.data.message ?? 'Work create failed',
            );

            setTimeout(() => {
                this.createVideoWorkSuccess = '';
                this.createVideoWorkFailed = '';
            }, 5000);
        },
    },
}
</script>

<style scoped>

</style>
