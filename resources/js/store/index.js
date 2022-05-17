import {createStore} from "vuex";
import app from "./App.js";
import photos from "./Photos.js";
import contact from "./Contact";
import posts from './Posts';

export default createStore({
    modules: {
        app,
        photos,
        contact,
        posts,
    },
});
