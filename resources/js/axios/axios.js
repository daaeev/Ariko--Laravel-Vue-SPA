import axios from "axios";
import SiteSettings from "../SiteSettings";

const instance = axios.create({
    baseURL: SiteSettings.api_domain + '/api'
});

export default instance;