<template>
  <div class="col-md-6 mt-7 ml-auto mr-auto text-center p-5 form-block">
    <alert :type="'danger'" v-if="failedLoginMessage">{{failedLoginMessage}}</alert>
    <login-form @submit="submitForm"></login-form>
  </div>
</template>

<script>
import LoginForm from "../../components/admin/login/loginForm.vue";
import { mapActions } from "vuex";
import Alert from "../../components/UI/Alert.vue";

export default {
  components: { LoginForm, Alert },

  data() {
      return {
          failedLoginMessage: '',
      };
  },

  methods: {
    ...mapActions("auth", ["login"]),

    submitForm(authObject) {
      this.failedLoginMessage = '';
      
      this.login(authObject)
        .catch((error) => {
            if (error.response.status == 429) {
                this.failedLoginMessage =
                    "Login attempt limit exceeded. Wait " +
                    error.response.headers["retry-after"] +
                    " seconds";
            } else {
                this.failedLoginMessage = "Login failed";
            }
        });
    },
  },
};
</script>

<style scoped>
.form-block {
  background: #efefef;
  border-radius: 10px;
}
</style>
