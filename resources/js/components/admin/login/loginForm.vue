<template>
  <form @submit.prevent="submitForm">
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input
        type="email"
        class="form-control"
        id="exampleInputEmail1"
        aria-describedby="emailHelp"
        placeholder="Enter email"
        maxlength="255"
        v-model="email"
      />
      <div class="text-danger help-block email-errors"></div>
    </div>
    <div class="form-group mt-3">
      <label for="exampleInputPassword1">Password</label>
      <input
        type="password"
        class="form-control"
        id="exampleInputPassword1"
        placeholder="Password"
        maxlength="100"
        v-model="password"
      />
      <div class="text-danger help-block password-errors"></div>
    </div>
    <div class="form-check mt-3">
      <input type="checkbox" class="form-check-input" id="exampleCheck1" v-model="save"/>
      <label class="form-check-label" for="exampleCheck1"
        >Save state after exit</label
      >
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            save: false,
        };
    },

    emits: ['submit'],

    methods: {
        submitForm() {
            let hasErrors = false;

            document.querySelector('.email-errors').innerHTML = '';
            document.querySelector('.password-errors').innerHTML = '';

            if (!this.email) {
                document.querySelector('.email-errors').append('Email is required');
                hasErrors = true;
            } else if (this.email.length > 255) {
                document.querySelector('.email-errors').append('Email length must not exceed 255');
                hasErrors = true; 
            }

            if (!this.password) {
                document.querySelector('.password-errors').append('Password is required');
                hasErrors = true;
            } else if (this.password.length > 100) {
                document.querySelector('.password-errors').append('Password length must not exceed 100');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const authObject = {
                email: this.email,
                password: this.password,
                save: this.save
            };

            this.$emit('submit', authObject);

            this.email = '';
            this.password = '';
            this.save = false;
        }
    }
};
</script>

<style>
</style>