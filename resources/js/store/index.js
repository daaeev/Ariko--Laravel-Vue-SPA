import {createStore} from "vuex";
import app from "./App.js";
import photos from "./Photos.js";

export default createStore({
    modules: {
        app,
        photos
    },
});
