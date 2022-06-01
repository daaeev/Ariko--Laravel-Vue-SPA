export default {
    overlay_menu: [
        {
            title: 'Works',
            url: 'works.photos',
            submenu: [
                {
                    title: 'Videos',
                    url: 'works.videos',
                },
                {
                    title: 'Photos',
                    url: 'works.photos',
                },
            ],
        },
        {
            title: 'About',
            url: 'about',
            submenu: []
        },
        {
            title: 'Journal',
            url: 'blog',
            submenu: []
        },
        {
            title: 'Contact',
            url: 'contact',
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

    capabilities: [
        'Mobile development',
        'WordPress development',
        'Logo design',
        'Branding',
    ],

    photosPerPage: 6,
    videosPerPage: 3,
    blogPerPage: 10,
    adminMessagesPerPage: 10,
    adminCommentsPerPage: 10,

    copyright: '© 2018 PxlSolutions Media, Inc',
    api_domain: 'http://ariko.vue',
    email: 'ariko@ariko.vue',
};
