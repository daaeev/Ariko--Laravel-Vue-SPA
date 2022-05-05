export default {
    state: {
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

        overlay_menu: [
            {
                title: 'Works',
                url: '#',
                submenu: [
                    {
                        title: 'Videos',
                        url: '#',
                    },
                    {
                        title: 'Photos',
                        url: '#',
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

        copyright: '© 2018 PxlSolutions Media, Inc',
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
    },

    namespaced: true,
};
