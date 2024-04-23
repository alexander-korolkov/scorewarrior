<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-btn v-if="store.isEditor">
        <router-link to="/create-user">Create new User</router-link>
      </q-btn>
    </div>
    <div class="q-mb-md">
      <q-input filled v-model="search" placeholder="Search by username or email..." />
    </div>
    <q-table
      title="List of all users"
      :rows="filteredUsers"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:body-cell-avatar="props">
        <q-td :props="props">
          <img
            :src="props.row.avatar || 'https://icons.iconarchive.com/icons/iconarchive/incognito-animals/256/Cat-Avatar-icon.png'"
            alt="User avatar"
            width="50px"
            height="50px"
            onError="this.onerror=null;"
          >
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useQuasar } from 'quasar';
import { userList } from 'src/api/user';
import { useAuthStore } from 'src/stores/auth';

const $q = useQuasar();
const users = ref([]);
const search = ref('');
const store = useAuthStore();

const columns = ref([
  {
    name: 'id',
    required: true,
    label: 'ID',
    align: 'left',
    field: 'id',
    sortable: true,
  },
  {
    name: 'avatar',
    align: 'left',
    label: 'Avatar',
    field: 'avatar',
    sortable: true,
  },
  {
    name: 'username',
    align: 'left',
    label: 'Username',
    field: 'username',
    sortable: true,
  },
  {
    name: 'email',
    align: 'left',
    label: 'Email',
    field: 'email',
    sortable: true,
  },
  {
    name: 'roles',
    align: 'left',
    label: 'Roles',
    field: (row) => (('ROLE_EDITOR' === row.roles[0]) ? 'EDITOR' : 'USER'),
    sortable: true,
  },
]);

const filteredUsers = computed(() => {
  if (!search.value) {
    return users.value;
  }
  return users.value.filter((user) => user.username.toLowerCase().includes(search.value.toLowerCase())
    || user.email.toLowerCase().includes(search.value.toLowerCase()));
});

onMounted(async () => {
  await fetchUsers();
});

async function fetchUsers() {
  try {
    const response = await userList();
    users.value = response.data;
  } catch (error) {
    $q.notify({
      color: 'negative',
      message: 'Error during request',
    });
  }
}
</script>
