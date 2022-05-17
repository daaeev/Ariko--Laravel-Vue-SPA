export default {
    methods: {
        dateFormat(date) {
            const options = {
                month: 'long',
            };

            const date_format = new Date(date);
            return date_format.getDate() + ' '+ new Date(date_format.getMonth()).toLocaleString('en-US', options) + ' ' + date_format.getUTCFullYear();
        }
    },
};