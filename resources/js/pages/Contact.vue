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
import Alert from "../components/UI/Alert";
import ContactAPI from '../logic/api/ContactMessage';
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
        async form_submit(formData) {
            this.submitSuccessMessage = '';
            this.submitFailedMessage = '';

            await ContactAPI.saveMessage(
                formData,
                () => this.submitSuccessMessage = 'Your message has been sent successfully',
                (error) => {
                    if (error.response.status == 429) {
                        this.submitFailedMessage = 'Message sending limit exceeded. Wait ' + error.response.headers['retry-after'] + ' seconds';
                    } else {
                        this.submitFailedMessage = 'Oops, something wrong';
                    }
                }
            )
        },
    },
}
</script>

<style scoped>

</style>
