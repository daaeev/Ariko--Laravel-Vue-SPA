export default {

    // НАСТРОЙКА МЕНЮШКИ
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

    // ННАСТРОЙКА ССЫЛОК НА СОЦ. СЕТИ
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

    // БЛОК 'ВОЗМОЖНОСТИ' НА СТРАНИЦЕ 'О НАС'
    capabilities: [
        'Mobile development',
        'WordPress development',
        'Logo design',
        'Branding',
    ],

    // КОЛИЧЕСТВО ФОТОГРАФИЙ НА '1 СТРАНИЦЕ'
    photosPerPage: 6,

    // КОЛИЧЕСТВО ВИДЕО НА '1 СТРАНИЦЕ'
    videosPerPage: 3,

    // КОЛИЧЕСТВО ПОСТОВ НА '1 СТРАНИЦЕ'
    blogPerPage: 10,

    // КОЛИЧЕСТВО ЕЛЕМЕНТОВ В АДМИН ПАНЕЛИ НА '1 СТРАНИЦЕ'
    adminCatalog: 10,

    // АВТОРСКИЕ ПРАВА 
    copyright: '© 2018 PxlSolutions Media, Inc',

    // ДОМЕН ДЛЯ АПИ
    // OS: http://ariko.vue
    // docker: http://localhost
    api_domain: 'http://localhost',

    // URI для API
    api_uri: '/api/v1',

    // ПОЧТА ДЛЯ СОТРУДНИЧЕСТВА
    email: 'ariko@ariko.vue',
};
