import Index from "../pages/Index.vue";
import Test from "../pages/Test.vue";
import SinglePhoto from "../pages/SinglePhoto";
import Error from "../pages/Error";
import Login from "../pages/admin/login";
import Contact from "../pages/Contact";

export default [

    // SITE ROUTES
    {
        path: '/',
        component: Index,
    },
    {
        path: '/test',
        component: Test,
    },
    {
        path: '/works/photos/:id',
        component: SinglePhoto
    },
    {
        path: '/contact',
        component: Contact
    },

    // ADMIN PANEL
    {
        path: '/admin/login',
        component: Login
    },


    // ERROR PAGE
    {
        path: '/error',
        component: Error,
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/error'
    },
];
