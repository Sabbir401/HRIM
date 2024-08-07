import router from "./router";

const api = axios.create({ baseURL: "http://localhost:8000/api/" });

api.interceptors.request.use(
    (config) => {
        const token = store.getters.getToken || localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
            // api.defaults.headers.common['Authorization'] = `Bearer ${token}`
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            store.dispatch("removeToken");
            router.push({ name: "Login" });
        }
        return Promise.reject(error);
    }
);

export default api;
