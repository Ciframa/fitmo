import axios from "axios";

export default axios.create({
    baseURL: process.env.VUE_APP_FITMO_BACKEND_URL,
    headers: {
        "Content-Type": "application/json",
        Authorization: localStorage.getItem("token"),
    },
    xsrfCookieName: "csrftoken",
    xsrfHeaderName: "X-CSRFToken",
    withCredentials: true,
});
