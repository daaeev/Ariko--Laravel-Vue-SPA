<template>
    <!-- content section -->
    <section class="content">

        <div class="container container-padding">
            <h2 class="mt-10 mb-3">What are you looking for?</h2>
            <p class="mb-0">Drop us a line</p>

            <div class="mt-3">
                <alert :type="'success'" v-if="submitSuccessMessage">{{ submitSuccessMessage }}</alert>
                <alert :type="'danger'" v-if="submitFailedMessage">{{ submitFailedMessage }}</alert>
            </div>

            <contact-form @submit="form_submit"></contact-form>
        </div>

    </section>

    <email-section :email="email"></email-section>
</template>

<script>
import EmailSection from "../components/global/divided/EmailSection";
import ContactForm from "../components/contactP/ContactForm";
import siteSettings from "../SiteSettings";
import {mapActions} from "vuex";
import Alert from "../components/UI/Alert";
export default {
    components: {Alert, EmailSection, ContactForm},

    data() {
        return {
            submitSuccessMessage: '',
            submitFailedMessage: '',
        };
    },

    computed: {
        email() {
            return siteSettings.email;
        }
    },

    methods: {
        ...mapActions('contact', ['sendMessage']),

        form_submit(messageObject) {
            this.submitSuccessMessage = '';
            this.submitFailedMessage = '';

            this.sendMessage(messageObject)
                .then(() => {this.submitSuccessMessage = 'Your message has been sent successfully'})
                .catch((error) => {
                    if (error.response.status == 429) {
                        this.submitFailedMessage = 'Message sending limit exceeded';
                    } else {
                        this.submitFailedMessage = 'Oops, something wrong';
                    }
                });
        }
    },
}
</script>

<style scoped>

</style>
