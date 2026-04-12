<template>
  <SellerCard title="Pesanan Saya">
    <div class="mt-4 space-y-4">
      <BaseTabs
        v-model="formFilter.last_status"
        :items="tabs"
        :content="false"
        :ui="{
          wrapper: 'border-b border-gray-100',
          list: {
            width: 'w-[unset] border-none',
            tab: {
              base: 'font-normal',
            },
          },
        }"
        @update:model-value="handleResetFilter"
      />
      <form class="flex gap-4 items-center" @submit.prevent="handleFilter">
        <UInput
          v-model="formFilter.search"
          class="flex-1"
          placeholder="Masukkan no. pesanan"
        />
        <UButton label="Terapkan" size="xs" variant="outline" type="submit" />
        <UButton
          label="Reset"
          size="xs"
          color="white"
          @click="handleResetFilter"
        />
      </form>
      <p v-if="status !== 'pending'" class="text-lg font-medium">
        {{ data?.total || 0 }} Pesanan
      </p>
      <BaseDataTable
        v-model:page="pagination.page"
        :total="data?.total || 0"
        :per-page="pagination.per_page"
        :columns="columns"
        :rows="orders"
        :loading="status === 'pending'"
        @update:page="execute"
      >
        <template #[`last_status.status-data`]="{ row }">
          {{ STATUS_ORDER?.[row.last_status.status] }}
        </template>
        <template #total-data="{ row }">
          Rp{{ formatNumber(row.total) }}
        </template>
        <template #action-data="{ row }">
          <UButton
            variant="link"
            color="blue"
            :padded="false"
            label="Lihat Detail"
            :to="`/seller/order/${row.uuid}`"
          />
        </template>
      </BaseDataTable>
    </div>
  </SellerCard>
</template>

<script setup>
import { STATUS_ORDER } from "~/constant/status";

definePageMeta({
  breadcrumb: [
    {
      label: "Pesanan Saya",
    },
  ],
});
const tabs = [
  {
    label: "Semua",
    key: "all",
  },
  {
    label: "Belum Dibayar",
    key: "pending_payment",
  },
  {
    label: "Sudah Dibayar",
    key: "paid",
  },
  {
    label: "Sedang Diproses",
    key: "on_processing",
  },
  {
    label: "Dikirim",
    key: "on_delivery",
  },
  {
    label: "Selesai",
    key: "done",
  },
  {
    label: "Dibatalkan",
    key: "failed",
  },
];

const columns = [
  {
    key: "invoice_number",
    label: "No. Pesanan",
  },
  {
    key: "total",
    label: "Harga Total",
  },
  {
    key: "last_status.status",
    label: "Status",
    rowClass: "capitalize",
  },
  {
    key: "action",
    label: "Aksi",
  },
];

const formFilter = ref({
  search: undefined,
  last_status: 0,
});
const pagination = ref({
  page: 1,
  per_page: 10,
});

const { data, status, execute } = useApi("/server/api/seller-dashboard/order", {
  key: "order-seller",
  params: computed(() => {
    return {
      ...pagination.value,
      ...formFilter.value,
      last_status: formFilter.value.last_status
        ? tabs[formFilter.value.last_status].key
        : undefined,
    };
  }),
  transform(response) {
    return response?.data || {};
  },
  watch: false,
});

const orders = computed(() => data.value?.data || []);

function handleFilter() {
  pagination.value.page = 1;
  execute();
}

function handleResetFilter() {
  pagination.value.page = 1;
  formFilter.value.search = undefined;
  execute();
}
</script>

<style scoped></style>
