import {createRouter, createWebHistory} from "vue-router";
import routes from "./Routes";

const router = createRouter({
    routes,
    history: createWebHistory(process.env.BASE_URL),
});

export default router;