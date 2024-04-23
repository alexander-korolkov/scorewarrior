<template>
  <div :key="store.actionsCountKey">
    <router-link class="header-link" to="/manage-item-grant-requests">Requests for items: {{ itemGrantsCount }},</router-link>&nbsp;
    <router-link class="header-link" to="/manage-reviews">Messages awaiting approval: {{ playerMessagesCount }},</router-link>&nbsp;
    <span>Total: {{ total }}</span>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Notify } from 'quasar';
import { actionsCount } from 'src/api/actions';
import { useActionsCountStore } from 'src/stores/actionsCount';

const store = useActionsCountStore();
const itemGrantsCount = ref(0);
const playerMessagesCount = ref(0);
const total = ref(0);

async function updateActionsCounter() {
  try {
    const response = await actionsCount();
    itemGrantsCount.value = response.data.itemGrantsCount;
    playerMessagesCount.value = response.data.playerMessagesCount;
    total.value = itemGrantsCount.value + playerMessagesCount.value;
  } catch (error) {
    Notify.create({
      type: 'negative',
      message: `Error loading counter data: ${error.response ? error.response.data.message : error.message}`,
    });
  }
}

onMounted(updateActionsCounter);
</script>
