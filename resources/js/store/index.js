import {createStore} from "vuex";
import app from "./App.js";
import photos from "./Photos.js";
import contact from "./Contact";
import posts from './Posts';
import auth from './Auth';
import videos from "./Videos.js";

export default createStore({
    modules: {
        app,
        photos,
        contact,
        posts,
        auth,
        videos,
    },
});
