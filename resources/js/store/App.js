export default {
    state: {
        overlay_menu: [
            {
                title: 'Works',
                url: '/',
                submenu: [
                    {
                        title: 'Videos',
                        url: '#',
                    },
                    {
                        title: 'Photos',
                        url: '/',
                    },
                ],
            },
            {
                title: 'About',
                url: '#',
                submenu: []
            },
            {
                title: 'Journal',
                url: '#',
                submenu: []
            },
            {
                title: 'Contact',
                url: '#',
                submenu: []
            },
        ],

        social_links: [
            {
                url: '#',
                icon_class: 'fab fa-dribbble',
            },
            {
                url: '#',
                icon_class: 'fab fa-twitter',
            },
            {
                url: '#',
                icon_class: 'fab fa-instagram',
            },
            {
                url: '#',
                icon_class: 'fab fa-linkedin-in',
            },
        ],

        copyright: '© 2018 PxlSolutions Media, Inc',
        router: null,
    },

    getters: {
        social_links(state) {
            return state.social_links;
        },

        overlay_menu(state) {
            return state.overlay_menu;
        },

        copyright(state) {
            return state.copyright;
        },

        router(state) {
            return state.router;
        }
    },

    mutations: {
        setRouter(state, value) {
            if (!state.router) {
                state.router = value;
            }
        }
    },

    actions: {
        /**
         * Редирект на страницу с ошибкой
         *
         * @param getters
         */
        errorPage({getters})
        {
            getters.router.push('/error');
        }
    },

    namespaced: true,
};
