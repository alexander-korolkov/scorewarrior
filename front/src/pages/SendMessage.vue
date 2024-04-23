<template>
  <q-page class="q-pa-md">
    <q-form @submit.prevent="onSubmit" class="q-gutter-md">
      <q-input
        v-model="messageData.playerId"
        label="Player ID"
        type="number"
        :rules="[val => val > 0 || 'Player ID must be positive']"
        required
      />
      <q-input
        v-model="messageData.message"
        label="Message"
        type="textarea"
        autogrow
        :rules="[val => val && val.length > 0 || 'Message is required']"
        required
      />
      <q-btn label="Send Message" type="submit" color="primary" />
    </q-form>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { createMessage } from 'src/api/playerMessages';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const messageData = ref({
  playerId: null,
  message: '',
});

async function onSubmit() {
  try {
    await createMessage(messageData.value);
    $q.notify({
      color: 'positive',
      message: 'Message sent successfully'
    });
    messageData.value = { playerId: null, message: '' };
  } catch (error) {
    console.error('Failed to send message:', error);
    $q.notify({
      color: 'negative',
      message: 'Failed to send message'
    });
  }
}
</script>
