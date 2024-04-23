<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <router-link class="header-link" to="/">Aggregator of Approves</router-link>
        </q-toolbar-title>

        <ActionCounts :key="actionsCountStore.actionsCountKey" />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
          Essential Links
        </q-item-label>

        <EssentialLink
          v-for="link in filteredLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import EssentialLink from 'components/EssentialLink.vue';
import ActionCounts from 'components/ActionCounts.vue';
import { useActionsCountStore } from 'src/stores/actionsCount';
import { useAuthStore } from 'src/stores/auth';

const actionsCountStore = useActionsCountStore();
const authStore = useAuthStore();

defineOptions({
  name: 'MainLayout',
});

const linksList = [
  {
    title: 'Home',
    caption: 'Dashboard',
    icon: 'home',
    link: '/',
    forEditor: false,
  },
  {
    title: 'Manage Reviews',
    caption: 'Approve or Reject pending actions',
    icon: 'reviews',
    link: '/manage-reviews',
    forEditor: true,
  },
  {
    title: 'List of Users',
    caption: 'List of all users',
    icon: 'people',
    link: '/users',
    forEditor: true,
  },
  {
    title: 'Send Message',
    caption: 'Send message to any Player here',
    icon: 'message',
    link: '/send-message',
    forEditor: false,
  },
  {
    title: 'Grant Item',
    caption: 'Give artifacts to any Player',
    icon: 'spa',
    link: '/grant-item',
    forEditor: false,
  },
  {
    title: 'Action\'s Log',
    caption: 'All Approved and Rejected actions',
    icon: 'list',
    link: '/actions-log',
    forEditor: true,
  },
  {
    title: 'Logout',
    caption: 'Exit from the system',
    icon: 'logout',
    link: '/login',
    forEditor: false,
  },
];

const leftDrawerOpen = ref(false);

const filteredLinks = computed(() => {
  return linksList.filter((link) => ((link.forEditor) ? authStore.isEditor : true));
});

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>

<style>
@media (max-width: 699px) {
  .q-toolbar__title {
    display: none;
  }
}
.header-link {
  color: white;
  text-decoration: none;
}
</style>
