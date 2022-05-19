import {createRouter, createWebHistory} from "vue-router";
import routes from "./Routes";
import store from "../store/index";

const router = createRouter({
    routes,
    history: createWebHistory(process.env.BASE_URL),
});

router.beforeEach(async (to, from, next) => {
    // Если имя роута не имеет тип "admin.{...}"
    if ((to.name.split('.', 1))[0] != 'admin') {
        next();
        return;
    }

    await store.dispatch('auth/checkAuth')
        .then(auth => {
            if (to.meta.requiresAuth && (!auth)) {   
                next({name: 'admin.login'});
                return;
            }    
            
            if (to.name === 'admin.login' && auth) {
                next({name: 'admin.index'});
                return;
            }

            next();
        });
});

export default router;