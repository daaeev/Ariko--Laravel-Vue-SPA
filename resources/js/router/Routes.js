import Index from "../pages/Index.vue";
import Test from "../pages/Test.vue";
import SinglePhoto from "../pages/SinglePhoto";
import Error from "../pages/Error";

export default [
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
        path: '/error',
        component: Error,
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/error'
    },
];
