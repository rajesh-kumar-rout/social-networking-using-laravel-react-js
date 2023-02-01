import axios from "axios"
import { BASE_URL } from "./constants"

axios.defaults.timeout = 100000
axios.defaults.baseURL = BASE_URL

axios.interceptors.request.use(config => {

    if (localStorage.getItem("token")) {

        config.headers.authorization = localStorage.getItem("token")
    }

    if (config.method !== "get" || config.method !== "post") {

        if (!config.url.includes("_method")) {

            config.url = config.url.includes("?") ? `${config.url}&_method=${config.method}`
                : `${config.url}?_method=${config.method}`

            config.method = "post"
        }
    }
    console.log(config);
    return config
})

axios.interceptors.response.use(response => response, error => {

    if (error.response.status === 401) {

        localStorage.removeItem("token")

        window.location.reload()
    }

    return Promise.reject(error)
})

export default axios