<template>
    <form @submit.prevent="submitForm" id="add-images-to-work-form">
    <div class="form-group">
      <p>Images</p>
      <input
        type="file"
        required
        name="images[]"
        multiple
      />
    </div>
    <div class="form-group mt-3">
      <label>Work id</label>
      <input
        type="numeric"
        class="form-control"
        placeholder="Work id"
        required
        name="work_id"
        v-model="work_id"
      />
      <div class="text-danger help-block work_id-errors"></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
export default {
    data() {
        return {
            work_id: '',
        };
    },

    emits: ['submit'],

    methods: {
        submitForm() {
            let hasErrors = false;

            document.querySelector('.work_id-errors').innerHTML = '';

            if (!this.work_id) {
                document.querySelector('.work_id-errors').append('Work id is required');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const formData = new FormData(document.querySelector('#add-images-to-work-form'));

            this.$emit('submit', formData);

            this.work_id = '';
        }
    },
}
</script>

<style>

</style>
