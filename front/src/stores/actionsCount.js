import { defineStore } from 'pinia';

export const useActionsCountStore = defineStore('actionsCount', {
  state: () => ({
    actionsCountKey: 0
  }),
  actions: {
    refreshActionsCount() {
      this.actionsCountKey++;
    }
  }
});
