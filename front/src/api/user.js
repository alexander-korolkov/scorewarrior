import axios from 'axios';

export function userLogin(credentials) {
  return axios.post(`${axios.defaults.baseURL}/login`, credentials);
}

export function userList() {
  return axios.get(`${axios.defaults.baseURL}/users`);
}

export function userCreate($user) {
  return axios.post(`${axios.defaults.baseURL}/users`, $user);
}
