<template>
    <form @submit.prevent="submitForm" id="create-photo-work-form">
        <div class="form-group">
            <label>Name</label>
            <input
                type="text"
                class="form-control"
                placeholder="Work name"
                maxlength="30"
                v-model="name"
                required
                name='name'
            />
            <div class="text-danger help-block name-errors"></div>
        </div>
        <div class="form-group mt-3">
            <label>Subject</label>
            <input
                type="text"
                class="form-control"
                placeholder="Work subject"
                maxlength="50"
                v-model="subject"
                required
                name='subject'
            />
            <div class="text-danger help-block subject-errors"></div>
        </div>
        <div class="form-group mt-3">
            <label>Year</label>
            <input
                type="text"
                class="form-control"
                placeholder="Year of creation"
                maxlength="10"
                v-model="year"
                required
                name="year"
            />
            <div class="text-danger help-block year-errors"></div>
        </div>

        <div class="form-group mt-3">
            <label>Client</label>
            <input
                type="text"
                class="form-control"
                placeholder="Client name"
                maxlength="50"
                v-model="client"
                name="client"
            />
            <div class="text-danger help-block client-errors"></div>
        </div>

        <div class="form-group mt-3">
            <label>Website</label>
            <input
                type="text"
                class="form-control"
                placeholder="Work done for"
                maxlength="255"
                v-model="website"
                name="website"
            />
            <div class="text-danger help-block website-errors"></div>
        </div>

        <div class="form-group mt-3">
            <label>Title</label>
            <input
                type="text"
                class="form-control"
                placeholder="Title (about)"
                maxlength="255"
                v-model="title"
                name="title"
            />
            <div class="text-danger help-block title-errors"></div>
        </div>

        <div class="form-group mt-3">
            <label>Description</label>
            <textarea
                class="form-control"
                placeholder="Description (about)"
                v-model="description"
                name="description"
            ></textarea>
            <div class="text-danger help-block description-errors"></div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</template>

<script>

export default {
    data() {
        return {
            name: "",
            subject: "",
            year: String((new Date()).getFullYear()),
            client: "",
            website: "",
            title: "",
            description: "",
        };
    },

    emits: ["submit"],

    methods: {
        submitForm() {
            let hasErrors = false;

            document.querySelector(".name-errors").innerHTML = "";
            document.querySelector(".subject-errors").innerHTML = "";
            document.querySelector(".year-errors").innerHTML = "";
            document.querySelector(".client-errors").innerHTML = "";
            document.querySelector(".website-errors").innerHTML = "";
            document.querySelector(".title-errors").innerHTML = "";
            document.querySelector(".description-errors").innerHTML = "";

            if (!this.name) {
                document.querySelector(".name-errors").append("Name is required");
                hasErrors = true;
            } else if (this.name.length > 30) {
                document
                    .querySelector(".name-errors")
                    .append("Name length must not exceed 30");
                hasErrors = true;
            }

            if (!this.subject) {
                document.querySelector(".subject-errors").append("Subject is required");
                hasErrors = true;
            } else if (this.subject.length > 50) {
                document
                    .querySelector(".subject-errors")
                    .append("Subject length must not exceed 50");
                hasErrors = true;
            }

            if (!this.year) {
                document.querySelector(".year-errors").append("Year is required");
                hasErrors = true;
            } else if (this.year.length > 10) {
                document
                    .querySelector(".year-errors")
                    .append("Year length must not exceed 10");
                hasErrors = true;
            }

            if (this.client.length > 50) {
                document
                    .querySelector(".client-errors")
                    .append("Client length must not exceed 50");
                hasErrors = true;
            }

            if (this.website.length > 255) {
                document
                    .querySelector(".website-errors")
                    .append("Website length must not exceed 255");
                hasErrors = true;
            }

            if (this.title.length > 255) {
                document
                    .querySelector(".title-errors")
                    .append("Title length must not exceed 255");
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const result = new FormData(document.querySelector('#create-photo-work-form'));

            this.$emit("submit", result);

            this.name = '';
            this.subject = '';
            this.client = '';
            this.website = '';
            this.title = '';
            this.description = '';
        },
    },
};
</script>

<style>
</style>
