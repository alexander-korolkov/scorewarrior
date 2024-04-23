import axios from 'axios';

export function actionsCount() {
  return axios.get(`${axios.defaults.baseURL}/actions/count`);
}

export function actionsLog() {
  return axios.get(`${axios.defaults.baseURL}/actions/log`);
}

export function actionsByUser() {
  return axios.get(`${axios.defaults.baseURL}/user-actions`);
}
