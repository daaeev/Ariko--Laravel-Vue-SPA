import axios from "./axios";
import store from "../store/index";

const token = store.getters['auth/token']
    ? store.getters['auth/token']
    : localStorage.getItem('token');

axios.defaults.headers.common['Authorization'] = token;

export default axios;
