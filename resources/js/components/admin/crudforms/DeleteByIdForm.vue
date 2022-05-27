<template>
    <form @submit.prevent="submitForm" id="delete-form">
    <div class="form-group mt-3">
      <label>Id</label>
      <input
        type="numeric"
        class="form-control"
        placeholder="ID"
        required
        name="id"
        v-model="id"
      />
      <div class="text-danger help-block id-errors"></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
export default {
    data() {
        return {
            id: '',
        };
    },

    emits: ['submit'],

    methods: {
        submitForm() {
            let hasErrors = false;

            document.querySelector('.id-errors').innerHTML = '';

            if (!this.id) {
                document.querySelector('.id-errors').append('ID field is required');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const formData = new FormData(document.querySelector('#delete-form'));

            this.$emit('submit', formData);

            this.id = '';
        }
    },
}
</script>

<style>

</style>
