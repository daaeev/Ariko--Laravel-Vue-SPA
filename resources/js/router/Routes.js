import Index from "../pages/Index.vue";
import Test from "../pages/Test.vue";
import SinglePhoto from "../pages/SinglePhoto";
import Error from "../pages/Error";
import Login from "../pages/admin/login";
import Contact from "../pages/Contact";
import About from "../pages/About";
import Blog from '../pages/Blog';
import SinglePost from '../pages/SinglePost';

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
    {
        path: '/about',
        component: About,
    },
    {
        path: '/blog',
        component: Blog,
    },
    {
        path: '/blog/:id',
        component: SinglePost,
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
