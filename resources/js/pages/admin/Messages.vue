<template>
  <div class="container container-padding">
    <alert :type="'danger'" v-if="removeFailedMessage" class="mt-5">{{removeFailedMessage}}</alert>
    <div class="messages-block">
      <messages-list
        :messages="messages"
        v-if="messages.length >= 1"
        @delete="onClickDeleteButton"
      ></messages-list>
      <div v-else class="text-center">
        <h3>Empty</h3>
      </div>

      <pagination
        :pagPage="pagPage"
        :totalPagesCount="totalPagesCount"
        @changePage="fetchMessages"
        v-if="messages.length >= 1"
      ></pagination>
    </div>
  </div>
</template>

<script>
import MessagesList from "../../components/admin/messages/MessagesList.vue";
import { mapGetters, mapActions } from "vuex";
import pagination from "../../components/pagination/NumericPagination.vue";
import messageAPI from '../../logic/api/admin/Messages';
import Alert from '../../components/UI/Alert.vue';

export default {
  components: {
    MessagesList,
    pagination,
    Alert
  },

  data() {
    return {
      removeFailedMessage: '',
    };
  },

  computed: {
    ...mapGetters("messages", ["totalPagesCount", "pagPage", "messages"]),
  },

  methods: {
    ...mapActions("messages", ["fetchMessages", 'deleteMessage']),

    onClickDeleteButton(messageID) {
        this.removeFailedMessage = '';

      this.deleteMessage(messageID)
        .catch(axiosError => {
          this.removeFailedMessage = axiosError.response?.data.message ?? 'Message delete failed';

          setTimeout(async () => this.removeFailedMessage = '', 7000);
        });
    }
  },

  created() {
    this.fetchMessages();
  },
};
</script>

<style scoped>
.messages-block {
  width: 100%;
  padding: 20px 100px 20px 100px;
  margin-top: 70px;
  border: 1px solid rgba(0, 0, 0, 0.363);
  border-radius: 5px;
}
</style>
 */