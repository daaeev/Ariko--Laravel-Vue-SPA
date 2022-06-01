<template>
    <div class="container container-padding">
        <alert :type="'danger'" v-if="APIfailed" class="mt-5">{{APIfailed}}</alert>

        <div class="comments-block">
            <comments-list :comments="comments" v-if="comments.length >= 1" @delete="onDeleteButtonClick" @checked="onCheckedButtonClick"></comments-list>

            <div v-else class="text-center">
                <h3>Empty</h3>
            </div>

            <numeric-pagination
                :pagPage="pagPage"
                :totalPagesCount="totalPagesCount"
                @changePage="fetchComments"
                v-if="comments.length >= 1"
            ></numeric-pagination>
        </div>
    </div>
</template>

<script>
import CommentsList from "../../components/admin/comments/CommentsList";
import {mapGetters, mapActions} from "vuex";
import NumericPagination from "../../components/pagination/NumericPagination";
import Alert from '../../components/UI/Alert';
export default {
    components: {NumericPagination, CommentsList, Alert},

    data() {
        return {
            APIfailed: '',
        };
    },

    computed: {
        ...mapGetters('comments', ['comments', 'pagPage', 'totalPagesCount'])
    },

    methods: {
        ...mapActions('comments', ['fetchComments', 'deleteComment', 'setCheckedStatus']),

        async onDeleteButtonClick(id) {
            this.APIfailed = '';

            await this.deleteComment(id)
                .catch((axiosError) => {
                    this.APIfailed = axiosError.response?.data.message ?? 'Comment delete failed';

                    setTimeout(async () => this.APIfailed = '', 7000);
                });
        },

        async onCheckedButtonClick(id) {
            this.APIfailed = '';

            await this.setCheckedStatus(id)
                .catch((axiosError) => {
                    this.APIfailed = axiosError.response?.data.message ?? 'Comment set checked status failed';

                    setTimeout(async () => this.APIfailed = '', 7000);
                });
        }
    },

    created() {
        this.fetchComments();
    }
};
</script>

<style scoped>
.comments-block {
    width: 100%;
    padding: 20px 100px 20px 100px;
    margin-top: 70px;
    border: 1px solid rgba(0, 0, 0, 0.363);
    border-radius: 5px;
}
</style>
