<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex flex-center">
        <q-card>
          <q-card-section>
            <q-form @submit.prevent="onLogin">
              <q-input v-model="username" label="Username" />
              <q-input v-model="password" type="password" label="Password" />
              <q-btn label="Login" type="submit" color="primary" />
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useAuthStore } from 'src/stores/auth';
import { useRouter } from 'vue-router';
import { Notify } from 'quasar';

const username = ref('');
const password = ref('');
const authStore = useAuthStore();
const router = useRouter();

async function onLogin() {
  try {
    await authStore.login({ username: username.value, password: password.value });
  } catch (error) {
    Notify.create({
      type: 'negative',
      message: `Login failed: ${error.response ? error.response.data.message : error.message}`,
    });
  }
}

authStore.$subscribe((mutation, state) => {
  if (state.token) {
    router.push('/');
  } else {
    router.push('/login');
  }
});

onMounted(() => {
  authStore.logout();
});
</script>

<style scoped>
.q-card {
  min-width: 300px;
}
button.q-btn {
  margin-top: 20px;
  width: 100%;
}
</style>
