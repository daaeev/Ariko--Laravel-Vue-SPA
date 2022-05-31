<template>
    <form @submit.prevent="submitForm" id="add-tag-to-post-form">
    <div class="form-group">
      <p>Tag</p>
      <input
        type="text"
        required
        name="tag"
        class="form-control"
        placeholder="Tag name"
        v-model="tag"
        list="tags"
        autocomplete="off"
      />
      <div class="text-danger help-block tag-errors"></div>
      <datalist id="tags">
        <option v-for="item in tagsList" :key="item.id">{{item.name}}</option>
      </datalist>
    </div>
    <div class="form-group mt-3">
      <label>Post id</label>
      <input
        type="numeric"
        class="form-control"
        placeholder="Post id"
        required
        name="post_id"
        v-model="post_id"
      />
      <div class="text-danger help-block post_id-errors"></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
import TagsApi from '../../../logic/api/admin/Tag';

export default {
    data() {
        return {
            post_id: '',
            tag: '',
            tagsList: [],
        };
    },

    async created() {
      TagsApi.fetchTags(
        axiosRes => this.tagsList = axiosRes.data
      );
    },

    emits: ['submit'],

    methods: {
        submitForm() {
            let hasErrors = false;

            document.querySelector('.post_id-errors').innerHTML = '';
            document.querySelector('.tag-errors').innerHTML = '';

            if (!this.post_id) {
                document.querySelector('.post_id-errors').append('Post id is required');
                hasErrors = true;
            }

            if (!this.tag) {
                document.querySelector('.tag-errors').append('Tag is required');
                hasErrors = true;
            } else if (this.tag.length > 255) {
                document.querySelector('.tag-errors').append('Tag length must be less then 255');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const formData = new FormData(document.querySelector('#add-tag-to-post-form'));

            this.$emit('submit', formData);

            this.tag = '';
        }
    },
}
</script>

<style>

</style>
