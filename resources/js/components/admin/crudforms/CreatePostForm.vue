<template>
  <form @submit.prevent="submit" id="create-post-form">
    <div class="form-group">
      <label>Title</label>
      <input
        type="text"
        class="form-control"
        name="title"
        placeholder="Title"
        required
        v-model="title"
      />
      <div class="text-danger help-block title-errors"></div>
    </div>
    <div class="form-group mt-3">
      <label>Content</label>
      <textarea
        class="form-control"
        name="content"
        placeholder="Content"
        required
        v-model="content"
      ></textarea>
      <div class="text-danger help-block content-errors"></div>
    </div>
    <div class="form-group mt-3">
      <p>Main image</p>
      <input
        type="file"
        required
        name="main_image"
      />
    </div>
    <div class="form-group mt-3">
      <p>Preview image</p>
      <input
        type="file"
        name="preview_image"
      />
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
export default {
    data() {
        return {
            title: '',
            content: '',
        };
    },

    emits: ['submit'],

    methods: {
        submit() {
            let hasErrors = false;

            document.querySelector('.title-errors').innerHTML = '';
            document.querySelector('.content-errors').innerHTML = '';

            if (!this.title) {
                document.querySelector('.title-errors').append('Title is required');
                hasErrors = true;
            } else if (this.title.length > 255) {
                document.querySelector('.title-errors').append('Title length must be less then 255');
                hasErrors = true;
            }

            if (!this.content) {
                document.querySelector('.content-errors').append('Content is required');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const formData = new FormData(document.querySelector('#create-post-form'));

            this.$emit('submit', formData);

            this.title = '';
            this.content = '';
        }
    },
};
</script>

<style>
</style>