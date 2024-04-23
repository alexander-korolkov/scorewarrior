import axios from 'axios';

export function createMessage(message) {
  return axios.post(`${axios.defaults.baseURL}/player-messages`, message);
}

export function getPendingPlayerMessages() {
  return axios.get(`${axios.defaults.baseURL}/player-messages/pending`);
}

export function updatePlayerMessageStatus(data) {
  return axios.patch(`${axios.defaults.baseURL}/player-messages/${data.id}/status`, data);
}
