<template>
  <q-page class="q-pa-md">
    <q-tabs aligned="left" class="text-teal">
      <q-route-tab to="/manage-reviews" label="Messages to the Players" />
      <q-route-tab to="/manage-item-grant-requests" label="Items Grants" />
    </q-tabs>
    <div class="q-mb-md">
      <q-input class="filter-input top-filter-input" v-model="filter.initiator" label="Search by Initiator" dense />
      <q-input class="filter-input top-filter-input" v-model="filter.description" label="Search by Description" dense />
      <q-input class="filter-input top-filter-input" v-model="filter.playerId" label="Search by Player ID" dense type="number" />
    </div>
    <div class="q-mb-md">
      <q-input
        filled
        v-model="formattedDateRange"
        :label="labelForDateRange"
        readonly
        class="cursor-pointer filter-input date-filter"
      >
        <template v-slot:append>
          <q-icon name="event" class="cursor-pointer"  @click="toggleDateMenu"/>
        </template>
      </q-input>

      <q-menu v-model="dateMenuOpen" >
        <q-date v-model="filter.dateRange" range @input="formatDateRange">
          <div class="row justify-end">
            <q-btn flat label="Close" color="primary" v-close-popup />
          </div>
        </q-date>
      </q-menu>
    </div>

    <div class="q-mb-md">
      <q-btn @click="applyFilters" label="Apply Filters" color="primary" />
      <q-btn @click="resetFilters" label="Reset Filters" color="negative" class="q-ml-md" />
    </div>
    <q-table
      :title="pageTitle"
      :rows="filteredActions"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:body-cell-description="props">
        <q-td :props="props" class="description-column">
          {{ ('item' in props.row) ? props.row.item.map((obj) => obj.Name).join(', ') : `Message: ${props.row.message}` }}
        </q-td>
      </template>
      <template v-slot:body-cell-approve="props">
        <q-td :props="props">
          <q-btn icon="done" color="green" @click="updateStatus(props.row, 'Approved')" />
        </q-td>
      </template>
      <template v-slot:body-cell-reject="props">
        <q-td :props="props">
          <q-btn icon="close" color="red" @click="updateStatus(props.row, 'Rejected')" />
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useQuasar } from 'quasar';
import { getPendingPlayerMessages, updatePlayerMessageStatus } from 'src/api/playerMessages';
import { getPendingItemsGrants, updateItemGrantStatus } from 'src/api/itemGrant';
import { useActionsCountStore } from 'src/stores/actionsCount';
import dayjs from 'dayjs';

const store = useActionsCountStore();
const route = useRoute();
const $q = useQuasar();
const actions = ref([]);
const dateMenuOpen = ref(false);
const formattedDateRange = ref('');
const pageTitle = ref('');
const apiFunctionRequests = ref({
  getPendingActions: null,
  updateActionStatus: null
});
const filter = ref({
  initiator: '',
  description: '',
  playerId: null,
  dateRange: { from: null, to: null }
});

const columns = [
  {
    name: 'id',
    required: true,
    label: 'Action ID',
    align: 'left',
    field: 'id',
    sortable: true,
  },
  {
    name: 'initiator',
    align: 'left',
    label: 'Initiator',
    field: (row) => row.initiator.username,
    sortable: true,
  },
  {
    name: 'description',
    align: 'left',
    label: 'Description',
    field: (row) => `Message: ${row.message}`,
    className: 'description-column',
  },
  {
    name: 'playerId',
    label: 'Player ID',
    field: 'playerId',
    align: 'left',
    sortable: true,
  },
  {
    name: 'date',
    align: 'left',
    label: 'Created At',
    field: (row) => dayjs(row.createdAt).format('D MMMM YYYY г., HH:mm:ss'),
    sortable: true
  },
  {
    name: 'approve',
    label: 'Approve',
    field: 'approve',
    align: 'left',
    sortable: false,
  },
  {
    name: 'reject',
    label: 'Reject',
    field: 'reject',
    align: 'left',
    sortable: false,
  },
];

const filteredActions = computed(() => {
  return actions.value.filter((action) => {
    const initiatorMatch = action.initiator.username.toLowerCase().includes(filter.value.initiator.toLowerCase());
    const playerIdMatch = filter.value.playerId ? action.playerId === filter.value.playerId : true;
    const dateInRange = filter.value.dateRange && filter.value.dateRange.from && filter.value.dateRange.to
      ? dayjs(action.createdAt).isAfter(dayjs(filter.value.dateRange.from)) && dayjs(action.createdAt).isBefore(dayjs(filter.value.dateRange.to))
      : true;

    let descriptionMatch;
    if (action.message) {
      descriptionMatch = action.message.toLowerCase().includes(filter.value.description.toLowerCase());
    } else {
      const description = action.item.map((obj) => obj.Name).join(', ');
      descriptionMatch = description.toLowerCase().includes(filter.value.description.toLowerCase());
    }

    return initiatorMatch && descriptionMatch && playerIdMatch && dateInRange;
  });
});

// Ran changes related with route change
watch(() => route.path, (newPath) => {
  pathRelatedChanges(newPath);
  fetchActions();
});

function pathRelatedChanges(path) {
  switch (path) {
    case '/manage-reviews':
      pageTitle.value = 'Messages to the Players to Approve';
      apiFunctionRequests.value.getPendingActions = getPendingPlayerMessages;
      apiFunctionRequests.value.updateActionStatus = updatePlayerMessageStatus;
      break;
    case '/manage-item-grant-requests':
      pageTitle.value = 'Requests to grant items to the players';
      apiFunctionRequests.value.getPendingActions = getPendingItemsGrants;
      apiFunctionRequests.value.updateActionStatus = updateItemGrantStatus;
      break;
    default:
      pageTitle.value = 'Messages to the Players to Approve';
      apiFunctionRequests.value.getPendingActions = getPendingPlayerMessages;
      apiFunctionRequests.value.updateActionStatus = updatePlayerMessageStatus;
  }
}

function toggleDateMenu() {
  dateMenuOpen.value = !dateMenuOpen.value;
}

function formatDateRange() {
  if (filter.value.dateRange.from && filter.value.dateRange.to) {
    formattedDateRange.value = `${dayjs(filter.value.dateRange.from).format('YYYY-MM-DD')} to ${dayjs(filter.value.dateRange.to).format('YYYY-MM-DD')}`;
  } else {
    formattedDateRange.value = '';
  }
}

const labelForDateRange = computed(() => {
  if (filter.value.dateRange && filter.value.dateRange.from) {
    return `${filter.value.dateRange.from} - ${filter.value.dateRange.to}`;
  }

  return 'Date Range';
});

async function fetchActions() {
  try {
    const response = await apiFunctionRequests.value.getPendingActions();
    actions.value = response.data;
  } catch (error) {
    console.error('Failed to load actions:', error);
    $q.notify({
      color: 'negative',
      message: 'Failed to load actions'
    });
  }
}

async function updateStatus(action, newStatus) {
  try {
    await updatePlayerMessageStatus({ id: action.id, status: newStatus });
    $q.notify({
      color: 'positive',
      message: `Action ${newStatus.toLowerCase()} successfully`
    });
    // Refresh after changing the status
    await fetchActions();
    store.refreshActionsCount();
  } catch (error) {
    $q.notify({
      color: 'negative',
      message: `Failed to set ${newStatus.toLowerCase()} status for the action`
    });
  }
}

async function applyFilters() {
  await fetchActions();
}

async function resetFilters() {
  filter.value.initiator = '';
  filter.value.description = '';
  filter.value.playerId = null;
  filter.value.dateRange = { from: null, to: null };
  await fetchActions();
}

onMounted(() => {
  pathRelatedChanges(route.path);
  fetchActions();
});
</script>

<style scoped>
.description-column {
  max-width: 300px;
  white-space: normal;
  overflow: visible;
}
/*
.description-column {
  max-width: 250px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
*/
.top-filter-input {
  float: left;
  margin-right: 3%;
  margin-bottom: 20px;
}

.filter-input {
  width: 30%;
  min-width: 200px;
  max-width: 300px;
}

@media (max-width: 800px) {
  .filter-input {
    float: none;
    margin: auto;
    width: 100%;
    min-width: 100%;
    max-width: 100%;
  }
}
</style>
