import axios from "axios";
import store from '../store';

export default {
    pageSize: 2,
    pagPage: null,
    totalPagesCount: null,
    pagination: {
        next_id: null,
        prev_id: null,
    },

    /**
     * Загрузить работы при помощи АПИ
     *
     * @returns {Promise<void>}
     */
    async fetchWorks() {
        store.commit('photos/setIsWorksLoading', true);

        const res = await axios.get(
            store.getters['app/api_domain'] + "/api/works/photos",
            {
                params: {
                    _limit: this.pageSize,
                    page: this.pagPage ?? 1,
                },
            }
        );

        if (this.totalPagesCount === null) {
            this.totalPagesCount = res.data.last_page;
        }

        store.commit('photos/addWorks', res.data.data);

        if (this.pagPage === null) {
            this.pagPage = 1;
        } else {
            this.pagPage++;
        }

        store.commit('photos/setIsWorksLoading', false);
    },

    /**
     * Несколько операций для получения работы с идентификатором work_id
     * 1) Загружена ли раннее запрашиваемая работа (state.single)
     *
     * 2) Проверяется наличие работы в массиве загруженных работ (state.works)
     *
     * 3) Работа запрашивается у АПИ (actions.fetchWorkById)
     * Если работа не найдена -> редирект на страницу с ошибкой (actions.${app/errorPage})
     *
     * @param work_id
     */
    async facadeFetchWorkById(work_id) {
        if (work_id == store.getters['photos/single'].id) {
            return;
        }

        let work = store.getters['photos/singleFromWorksState'](work_id);

        if (work) {
            if (!work.images) {
                await this.fetchImagesByWorkId(work_id).then(data => {
                    work.images = data;
                });
            }

            await this.fetchNextPrevIds(work.id).then(data => {
                this.pagination.next_id = data.next ? data.next.id : null;
                this.pagination.prev_id = (data.prev ? data.prev.id : null);
            });

            store.commit('photos/setSingle', work);

            return;
        }

        this.fetchWorkById(work_id);
    },

    /**
     * Получить работу с идентификатором work_id
     * Если работа не найдена -> редирект на страницу с ошибкой (actions.${app/errorPage})
     *
     * @param work_id
     * @returns {Promise<void>}
     */
    async fetchWorkById(work_id) {
        const res = await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/${work_id}`
        );

        const work = res.data;

        if (Object.keys(work) == 0) {
            store.dispatch('app/errorPage', null, {root: true});
            return;
        }

        await this.fetchNextPrevIds(work.id).then(data => {
            this.pagination.next_id = data.next ? data.next.id : null;
            this.pagination.prev_id = data.prev ? data.prev.id : null;
        });

        store.commit('photos/setSingle', work);
    },

    /**
     * Получение всех фотографий работы с идентификатором work_id
     * Если фотографии не найдены -> редирект на страницу с ошибкой (actions.${app/errorPage})
     *
     * @param work_id
     * @returns {Promise<void>}
     */
    async fetchImagesByWorkId(work_id) {
        const res = await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/images/${work_id}`
        );

        if (res.data.length == 0) {
            store.dispatch('app/errorPage', null, {root: true});
            return;
        }

        return res.data;
    },

    /**
     * Получить идентификаторы 'следующей' и 'предыдущей' работы
     * @param work_id
     * @returns {Promise<void>}
     */
    async fetchNextPrevIds(work_id) {
        const res = await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/next/prev/${work_id}`
        );

        return res.data;
    }
};
