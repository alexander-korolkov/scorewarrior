<template>
  <q-page class="q-pa-md">
    <q-form @submit.prevent="onSubmit" class="q-gutter-md">
      <q-input
        v-model="grantData.playerId"
        label="Player ID"
        type="number"
        :rules="[val => val > 0 || 'Player ID must be positive']"
        required
      />

      <q-select
        v-model="grantData.items"
        label="Select Item"
        :options="items"
        option-label="name"
        :rules="[val => !!val || 'Item is required']"
        required
        emit-value
      />

      <q-btn label="Grant Item" type="submit" color="primary" />
    </q-form>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { createItemGrant, getListOfItems } from 'src/api/itemGrant';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const grantData = ref({
  playerId: null,
  items: null,
});
const items = ref([]);

async function fetchItems() {
  try {
    const response = await getListOfItems();
    items.value = response.data.map(
      (obj) => {
        const value = `${obj.Name} (${obj.Description})`;

        return { id: obj.id, name: value };
      },
    );
  } catch (error) {
    console.error('Failed to load items:', error);
    $q.notify({
      color: 'negative',
      message: 'Failed to load items',
    });
  }
}

async function onSubmit() {
  try {
    await createItemGrant({
      playerId: grantData.value.playerId,
      items: [grantData.value.items.id],
    });
    $q.notify({
      color: 'positive',
      message: 'Item granted successfully',
    });
    grantData.value = { playerId: null, items: null };
  } catch (error) {
    console.error('Failed to grant item:', error);
    $q.notify({
      color: 'negative',
      message: 'Failed to grant item',
    });
  }
}

onMounted(fetchItems);
</script>
