import axios from 'axios';

export function createItemGrant(itemGrant) {
  return axios.post(`${axios.defaults.baseURL}/item-grants`, itemGrant);
}

export function getListOfItems() {
  return axios.get(`${axios.defaults.baseURL}/items`);
}

export function getPendingItemsGrants() {
  return axios.get(`${axios.defaults.baseURL}/item-grants/pending`);
}

export function updateItemGrantStatus(data) {
  return axios.patch(`${axios.defaults.baseURL}/item-grants/${data.id}/status`, data);
}
