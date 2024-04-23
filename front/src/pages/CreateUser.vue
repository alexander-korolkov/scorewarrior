<template>
  <q-page class="q-pa-md">
    <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
      <q-input v-model="user.username" label="Username" :rules="[val => val && val.length > 0 || 'Name is required']" />
      <q-input v-model="user.email" type="email" label="Email" :rules="[val => val && val.length > 0 && /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(val) || 'Please enter a valid email']" />
      <q-input v-model="user.password" type="password" label="Password" :rules="[val => val && val.length >= 6 || 'Password must be at least 6 characters']" />
      <q-input v-model="user.avatar" type="url" hint="Please enter an URL to external image" label="Avatar URL" :rules="[val => !val || /^(https?:\/\/).+/.test(val) || 'Enter a valid URL']" />

      <q-select
        v-model="user.roles"
        label="Role"
        :options="roleOptions"
        option-value="value"
        option-label="label"
        emit-value
      />

      <q-btn label="Submit" type="submit" color="primary" class="q-mt-md" />
      <q-btn label="Reset" type="reset" color="secondary" class="q-mt-md" />
    </q-form>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { useQuasar } from 'quasar';
import { userCreate } from 'src/api/user';

const $q = useQuasar();
const user = ref({
  username: '',
  email: '',
  password: '',
  avatar: '',
  roles: '',
});

const roleOptions = [
  { label: 'Editor', value: 'ROLE_EDITOR' },
  { label: 'User', value: 'ROLE_USER' }
];

async function onSubmit() {
  try {
    await userCreate(user.value);
    $q.notify({
      type: 'positive',
      message: 'User has been created successfully'
    });
    user.value = { username: '', email: '', password: '', avatar: '', roles: '' };
  } catch (error) {
    console.error('Failed to create user:', error);
    $q.notify({
      type: 'negative',
      message: 'Failed to create user'
    });
  }
}

function onReset() {
  user.value = { username: '', email: '', password: '', avatar: '', roles: '' };
}
</script>
