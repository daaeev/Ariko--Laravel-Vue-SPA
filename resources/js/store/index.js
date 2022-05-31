import {createStore} from "vuex";
import app from "./App.js";
import photos from "./Photos.js";
import posts from './Posts';
import auth from './Auth';
import videos from "./Videos.js";
import messages from "./Messages.js";

export default createStore({
    modules: {
        app,
        photos,
        posts,
        auth,
        videos,
        messages,
    },
});
