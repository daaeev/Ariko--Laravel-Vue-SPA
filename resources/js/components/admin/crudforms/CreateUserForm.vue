<template>
    <form @submit.prevent="submitForm">
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input
        type="email"
        class="form-control"
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
        placeholder="Password"
        v-model="password"
      />
      <div class="text-danger help-block password-errors"></div>
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
            }

            if (hasErrors) {
                return;
            }

            const user = {
                email: this.email,
                password: this.password,
            };

            this.$emit('submit', user);

            this.email = '';
            this.password = '';
        }
    },
}
</script>

<style>

</style>