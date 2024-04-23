import { defineStore } from 'pinia';
import { Notify } from 'quasar';
import { userLogin } from 'src/api/user';
import { jwtDecode } from 'jwt-decode';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('auth-token') || '',
  }),
  getters: {
    isAuthenticated: (state) => !!state.token && !isTokenExpired(state.token),
    roles: (state) => {
      try {
        const decoded = jwtDecode(state.token);
        return decoded.roles;
      } catch (error) {
        console.error('Error decoding token:', error);
        return null;
      }
    },
    isEditor: (state) => state.roles.includes('ROLE_EDITOR'),
  },
  actions: {
    async login(credentials) {
      try {
        const response = await userLogin(credentials);
        this.token = response.data.token;
        localStorage.setItem('auth-token', this.token);
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: `Login failed: ${error.response ? error.response.data.message : error.message}`,
        });
        // throw error;
      }
    },
    logout() {
      this.token = '';
      localStorage.removeItem('auth-token');
    },
  },
});

function isTokenExpired(token) {
  try {
    const decoded = jwtDecode(token);
    const now = Date.now() / 1000;
    return decoded.exp < now;
  } catch (error) {
    console.error('Error decoding token:, error');
    return false;
  }
}
