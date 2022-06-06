import contactAPI from '../../api/ContactMessage';

export default {
    actions: {
        /**
         * Создание письма
         * 
         * @param formData 
         */
        async createMessage({}, formData) {
            await contactAPI.saveMessage(
                formData,
                null,
                axiosError => {throw axiosError}
            );
        }
    },
};