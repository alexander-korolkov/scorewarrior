<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-select
        v-model="filterStatus"
        :options="['All', 'Approved', 'Rejected', 'Pending']"
        label="Filter by Status"
        emit-value
        map-options
      />
    </div>
    <q-table
      title="Your recent Actions"
      :rows="filteredActions"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:body-cell-description="props">
        <q-td :props="props" class="description-column">
          {{ ('item' in props.row) ? props.row.item.map((obj) => obj.Name).join(', ') : `Message: ${props.row.message}` }}
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { actionsByUser } from 'src/api/actions';
import dayjs from 'dayjs';

const actions = ref([]);
const filterStatus = ref('All');
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
    name: 'type',
    align: 'left',
    label: 'Type',
    field: (row) => { return ('item' in row) ? 'ItemGrant' : 'Message' },
    sortable: true,
  },
  {
    name: 'status',
    align: 'left',
    label: 'Status',
    field: 'status',
    sortable: true
  },
  {
    name: 'playerId',
    align: 'left',
    label: 'Player ID',
    field: 'playerId',
    sortable: true
  },
  {
    name: 'description',
    align: 'left',
    label: 'Description',
    field: (row) => {
      return ('item' in row)
        ? row.item.map((obj) => obj.Name).join(', ')
        : `Message: ${row.message}`
    },
  },
  {
    name: 'date',
    align: 'left',
    label: 'Created At',
    field: (row) => dayjs(row.createdAt).format('D MMMM YYYY г., HH:mm:ss'),
    sortable: true
  },
];

const filteredActions = computed(() => {
  if (filterStatus.value === 'All') {
    return actions.value;
  }
  return actions.value.filter((action) => action.status === filterStatus.value);
});

async function fetchActionsLog() {
  try {
    const response = await actionsByUser();
    actions.value = response.data;
  } catch (error) {
    console.error('Error fetching actions log:', error);
  }
}

onMounted(fetchActionsLog);
</script>

<style scoped>
.description-column {
  max-width: 300px;
  white-space: normal;
  overflow: visible;
}
</style>
