import { boot } from 'quasar/wrappers';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

export default boot(({ app, router }) => {
  axios.defaults.baseURL = 'http://localhost:8080/api';

  axios.interceptors.request.use((config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`;
    }
    return config;
  });

  axios.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response && error.response.status === 401) {
        const authStore = useAuthStore();
        authStore.logout();
        router.push('/login');
      }
      return Promise.reject(error);
    },
  );

  app.config.globalProperties.$axios = axios;
});
