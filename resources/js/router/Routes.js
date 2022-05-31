import Index from "../pages/Index.vue";
import Test from "../pages/Test.vue";
import SinglePhoto from "../pages/SinglePhoto";
import Error from "../pages/Error";
import Login from "../pages/admin/Login";
import Contact from "../pages/Contact";
import About from "../pages/About";
import Blog from '../pages/Blog';
import SinglePost from '../pages/SinglePost';
import AdminIndex from '../pages/admin/Index.vue'
import AdminMessages from '../pages/admin/Messages.vue'
import Videos from "../pages/Videos";
import SingleVideo from "../pages/SingleVideo";

export default [

    // SITE ROUTES
    {
        path: '/',
        component: Index,
        name: 'works.photos'
    },
    {
        path: '/videos',
        component: Videos,
        name: 'works.videos'
    },
    {
        path: '/works/videos/:id',
        component: SingleVideo,
        name: 'works.videos.single'
    },
    {
        path: '/test',
        component: Test,
        name: 'test'
    },
    {
        path: '/works/photos/:id',
        component: SinglePhoto,
        name: 'works.photos.single'
    },
    {
        path: '/contact',
        component: Contact,
        name: 'contact'
    },
    {
        path: '/about',
        component: About,
        name: 'about',
    },
    {
        path: '/blog',
        component: Blog,
        name: 'blog',
    },
    {
        path: '/blog/by/:tag',
        component: Blog,
        name: 'blog.by-tag',
    },
    {
        path: '/blog/:id',
        component: SinglePost,
        name: 'blog.single'
    },

    // ADMIN PANEL
    {
        path: '/admin/login',
        component: Login,
        name: 'admin.login',
    },
    {
        path: '/admin',
        component: AdminIndex,
        name: 'admin.index',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/admin/messages',
        component: AdminMessages,
        name: 'admin.messages',
        meta: {
            requiresAuth: true
        }
    },

    // ERROR PAGE
    {
        path: '/error',
        component: Error,
        name: 'error',
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: {name: 'error'},
    },
];
