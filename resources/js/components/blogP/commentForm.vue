<template>
  <form id="comment-form" class="comment-form mt-6" @submit.prevent="submitForm">
    <div class="row">
      <div class="column col-md-6">
        <!-- Name input -->
        <div class="form-group">
          <input
            type="text"
            class="form-control"
            name="InputName"
            id="InputName"
            placeholder="Your name"
            required="required"
            v-model="name"
          />
          <div class="help-block with-errors name-errors"></div>
        </div>
      </div>

      <div class="column col-md-6">
        <!-- Email input -->
        <div class="form-group">
          <input
            type="email"
            class="form-control"
            id="InputEmail"
            name="InputEmail"
            placeholder="Email address"
            required="required"
            v-model="email"
          />
          <div class="help-block with-errors email-errors"></div>
        </div>
      </div>

      <div class="column col-md-12">
        <!-- Comment textarea -->
        <div class="form-group">
          <textarea
            name="InputComment"
            id="InputComment"
            class="form-control"
            rows="5"
            placeholder="Comment"
            required="required"
            v-model="comment"
          ></textarea>
          <div class="help-block with-errors comment-errors"></div>
        </div>
      </div>
    </div>

    <button
      type="submit"
      name="submit"
      id="submit"
      value="Submit"
      class="btn btn-default btn-lg btn-full"
    >
      Add comment</button
    ><!-- Send Button -->
  </form>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            comment: '',
        };
    },

    emits: ['submit'],

    methods: {
        submitForm() {
            let hasErrors = false;

            if (!this.name) {
                document.querySelector('.name-errors').append('Name is required');
                hasErrors = true;
            }

            if (!this.email) {
                document.querySelector('.email-errors').append('Email is required');
                hasErrors = true;
            }

            if (!this.comment) {
                document.querySelector('.comment-errors').append('Comment is required');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const commentObject = {
                name: this.name,
                email: this.email,
                comment: this.comment,
            };

            this.$emit('submit', commentObject)

            this.name = '';
            this.email = '';
            this.comment = '';
        }
    },
};
</script>

<style>
</style>