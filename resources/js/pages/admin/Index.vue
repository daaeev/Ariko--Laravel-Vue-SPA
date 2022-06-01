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
                <dialog-button v-model:show="addTagShow">Add tag to post</dialog-button>
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
            <router-link :to="{name: 'admin.messages'}">Go to messages page -></router-link><br>
            <router-link :to="{name: 'admin.comments'}">Go to comments page -></router-link><br>
        </div>
    </div>

    <!-- Dialogs -->
        <my-dialog v-model:show="createUserShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <create-user-form @submit="submitCreateUserForm"></create-user-form>
        </my-dialog>

        <my-dialog v-model:show="createPhotoWorkShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <create-photo-work-form @submit="submitCreatePhotoWorkForm"></create-photo-work-form>
        </my-dialog>

        <my-dialog v-model:show="addImagesToWorkShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <add-images-to-work-form @submit="submitAddImagesToWorkForm"></add-images-to-work-form>
        </my-dialog>

        <my-dialog v-model:show="createPostShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <create-post-form @submit="submitCreatePostForm"></create-post-form>
        </my-dialog>

        <my-dialog v-model:show="createVideoWorkShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <create-video-work-form @submit="submitCreateVideoWorkForm"></create-video-work-form>
        </my-dialog>

        <my-dialog v-model:show="deletePhotoWorkShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <delete-form @submit="submitDeletePhotoWorkForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deletePostShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <delete-form @submit="submitDeletePostForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deleteVideoWorkShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <delete-form @submit="submitDeleteVideoWorkForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="deleteUserShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <delete-form @submit="submitDeleteUserForm"></delete-form>
        </my-dialog>

        <my-dialog v-model:show="addTagShow">
            <alert v-if="APIsuccess" :type="'success'">{{APIsuccess}}</alert>
            <alert v-if="APIfailed" :type="'danger'">{{APIfailed}}</alert>
            <tag-to-post-form @submit="submitAddTagForm"></tag-to-post-form>
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
import axiosTagAPI from '../../logic/api/admin/Tag';
import axiosPhotoWorkAPI from '../../logic/api/admin/PhotoWork';
import CreatePhotoWorkForm from '../../components/admin/crudforms/CreatePhotoWorkForm.vue';
import AddImagesToWorkForm from '../../components/admin/crudforms/AddImagesToWorkForm.vue';
import CreatePostForm from '../../components/admin/crudforms/CreatePostForm.vue'
import axiosVideoWorkAPI from '../../logic/api/admin/VideoWork';
import CreateVideoWorkForm from '../../components/admin/crudforms/CreateVideoWorkForm.vue';
import DeleteForm from '../../components/admin/crudforms/DeleteByIdForm.vue';
import TagToPostForm from '../../components/admin/crudforms/AddTagToPost.vue';

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
        DeleteForm,
        TagToPostForm
    },

    data() {
        return {
            APIfailed: '',
            APIsuccess: '',

            // CREATE USER FORM
            createUserShow: false,

            // CREATE PHOTO WORK FORM
            createPhotoWorkShow: false,

            // ADD IMAGES TO WORK FORM
            addImagesToWorkShow: false,

            // CREATE POST FORM
            createPostShow: false,

            // CREATE VIDEO WORK FORM
            createVideoWorkShow: false,

            // DELETE PHOTO WORK FORM
            deletePhotoWorkShow: false,

            // DELETE POST FORM
            deletePostShow: false,

            // DELETE VIDEO WORK FORM
            deleteVideoWorkShow: false,

            // DELETE USER FORM
            deleteUserShow: false,

            // ADD TAG TO POST FORM
            addTagShow: false,
        };
    },

    methods: {
        // Форма добавления тега посту
        async submitAddTagForm(formData) {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosTagAPI.addTagToPost(
                formData,
                () => this.APIsuccess = 'Tag added success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Tag deleted failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма удаления пользователя
        async submitDeleteUserForm(formData) {
            this.APIfailed = '';
            this.APIsuccess = '';

            const user_id = formData.get('id');

            await axiosUserAPI.deleteUser(
                user_id,
                () => this.APIsuccess = 'User deleted success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'User deleted failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма удаления поста
        async submitDeletePostForm(formData) {
            this.APIfailed = '';
            this.APIsuccess = '';

            const post_id = formData.get('id');

            await axiosPostAPI.deletePost(
                post_id,
                () => this.APIsuccess = 'Post deleted success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Post deleted failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма удаления работы (видео)
        async submitDeleteVideoWorkForm(formData) {
            this.APIfailed = '';
            this.APIsuccess = '';

            const work_id = formData.get('id');

            await axiosVideoWorkAPI.deleteWork(
                work_id,
                () => this.APIsuccess = 'Work deleted success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Work deleted failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма удаления работы (фотографии)
        async submitDeletePhotoWorkForm(formData) {
            this.APIfailed = '';
            this.APIsuccess = '';

            const work_id = formData.get('id');

            await axiosPhotoWorkAPI.deleteWork(
                work_id,
                () => this.APIsuccess = 'Work deleted success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Work deleted failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма создание пользователя
        async submitCreateUserForm(formData)
        {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosUserAPI.createUser(
                formData,
                axiosRes => this.APIsuccess = 'User create success (ID:' + axiosRes.data.id + ')',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'User create failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма создания работы (фотографии)
        async submitCreatePhotoWorkForm(formData)
        {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosPhotoWorkAPI.createPhotoWork(
                formData,
                axiosRes => this.APIsuccess = 'Work create success (id: ' + axiosRes.data.id + ')',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Work create failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма для добавления фотографий к работе
        async submitAddImagesToWorkForm(formData)
        {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosPhotoWorkAPI.addImagesToWork(
                formData,
                () => this.APIsuccess = 'Photos added success',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Photos add failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма создания поста
        async submitCreatePostForm(formData)
        {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosPostAPI.createPost(
                formData,
                axiosRes => this.APIsuccess = 'Post create success (ID:' + axiosRes.data.id + ')',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Post create failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },

        // Форма создания работы (видео)
        async submitCreateVideoWorkForm(formData)
        {
            this.APIfailed = '';
            this.APIsuccess = '';

            await axiosVideoWorkAPI.createVideoWork(
                formData,
                axiosRes => this.APIsuccess = 'Work create success (ID:' + axiosRes.data.id + ')',
                axiosError => this.APIfailed = axiosError.response?.data.message ?? 'Work create failed',
            );

            setTimeout(() => {
                this.APIfailed = '';
                this.APIsuccess = '';
            }, 5000);
        },
    },
}
</script>

<style scoped>

</style>
