<template>
    <!-- Contact Form -->
    <form id="contact-form" class="contact-form mt-6" @submit.prevent="onSubmit">

        <div class="messages"></div>

        <div class="row">
            <div class="column col-md-6">
                <!-- Name input -->
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="InputName" placeholder="Your name" required="required" v-model="name" maxlength="50">
                    <div class="help-block with-errors name-errors"></div>
                </div>
            </div>

            <div class="column col-md-6">
                <!-- Email input -->
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="InputEmail" placeholder="Email address" required="required" v-model="email" maxlength="255">
                    <div class="help-block with-errors email-errors"></div>
                </div>
            </div>

            <div class="column col-md-12">
                <!-- Message textarea -->
                <div class="form-group">
                    <textarea name="InputMessage" id="message" class="form-control" rows="5" placeholder="Message" required="required" v-model="message"></textarea>
                    <div class="help-block with-errors message-errors"></div>
                </div>
            </div>
        </div>

        <!-- Send Button -->
        <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-default btn-lg btn-full">Submit</button>

    </form>
    <!-- Contact Form end -->
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            message: '',
        };
    },

    methods: {
        onSubmit() {
            let hasErrors = false;

            document.querySelector('.name-errors').innerHTML = '';
            document.querySelector('.email-errors').innerHTML = '';
            document.querySelector('.message-errors').innerHTML = '';

            if (!this.name) {
                document.querySelector('.name-errors').append('Name is required');
                hasErrors = true;
            } else if (this.name.length > 50) {
                document.querySelector('.name-errors').append('Name length must not exceed 50');
                hasErrors = true;
            }

            if (!this.email) {
                document.querySelector('.email-errors').append('Email is required');
                hasErrors = true;
            } else if (this.email.length > 255) {
                document.querySelector('.email-errors').append('Email length must not exceed 255');
                hasErrors = true;  
            }

            if (!this.message) {
                document.querySelector('.message-errors').append('Message is required');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            const messageObject = {
                name: this.name,
                email: this.email,
                message: this.message,
            };

            this.$emit('submit', messageObject)

            this.name = '';
            this.email = '';
            this.message = '';
        },
    },

    emits: ['submit'],
}
</script>

<style scoped>

</style>
