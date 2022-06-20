import axios from "axios";
import SiteSettings from "../SiteSettings";

const instance = axios.create({
    baseURL: SiteSettings.api_domain + SiteSettings.api_uri
});

export default instance;