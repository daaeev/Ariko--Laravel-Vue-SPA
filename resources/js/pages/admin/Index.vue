<template>
    <div class="container container-padding mt-10">
        <div class="row">
            <div class="col-md-2">
                <dialog-button v-model:show="createUserShow">Create user</dialog-button>
            </div>
        </div>
    </div>

    <!-- Dialogs -->
    <my-dialog v-model:show="createUserShow">
        <alert v-if="createUserSuccess" :type="'success'">{{createUserSuccess}}</alert>
        <alert v-if="createUserFailed" :type="'danger'">{{createUserFailed}}</alert>
        <create-user-form @submit="submitCreateUserForm"></create-user-form>
    </my-dialog>
</template>

<script>
import DialogButton from '../../components/admin/UI/DialogButton.vue'
import MyDialog from '../../components/admin/UI/Dialog.vue'
import CreateUserForm from '../../components/admin/crudforms/CreateUserForm.vue'
import Alert from '../../components/UI/Alert.vue';
import axiosAPI from '../../logic/api/crud/User';

export default {
    components: {DialogButton, MyDialog, CreateUserForm, Alert},

    data() {
        return {
            createUserShow: false,
            createUserSuccess: '',
            createUserFailed: '',
        };
    },

    methods: {
        async submitCreateUserForm(user)
        {
            this.createUserSuccess = '';
            this.createUserFailed = '';

            await axiosAPI.createUser(
                user.email,
                user.password,
                axiosRes => this.createUserSuccess = 'User create success',
                axiosError => this.createUserFailed = axiosError.response?.data.message ?? 'User create failed',
            );

            setTimeout(() => {
                this.createUserSuccess = '';
                this.createUserFailed = '';
            }, 5000);
        }
    },
}
</script>

<style>

</style>
