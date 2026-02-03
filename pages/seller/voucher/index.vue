<template>
  <SellerCard title="Daftar Voucher">
    <template #header-append>
      <UButton icon="i-heroicons:plus" to="/seller/voucher/add"
        >Tambah Voucher</UButton
      >
    </template>
    <BaseTabs
      v-model="formFilter.status"
      :items="tabs"
      :ui="{
        wrapper: 'border-b border-gray-100',
        list: {
          width: 'w-[unset] border-none',
          tab: {
            font: 'font-normal',
          },
        },
      }"
      :content="false"
      @update:model-value="handleFilter"
    />
    <div class="mt-8 space-y-4 text-sm">
      <form class="flex gap-3 items-center" @submit.prevent="handleFilter">
        <span>Cari</span>
        <UInput v-model="formFilter.search" placeholder="Input" class="w-80" />
        <UButton type="submit" variant="outline" size="xs"> Cari </UButton>
      </form>
      <BaseDataTable
        v-model:page="pagination.page"
        :total="data?.total || 0"
        :per-page="pagination.per_page"
        :rows="items"
        :columns="columns"
        :loading="status === 'pending'"
        @update:page="execute"
      >
        <template #name-data="{ row }">
          <div class="flex gap-2">
            <img
              class="w-14 h-14 object-cover"
              :src="
                row.voucher_type === 'discount'
                  ? image[row.discount_cashback_type]
                  : image['coin']
              "
            />
            <div class="flex flex-col items-start gap-0.5">
              <UBadge
                v-if="checkExpired(row.end_date)"
                label="Telah Berakhir"
                color="gray"
                size="xs"
                variant="soft"
                :ui="{
                  variant: {
                    soft: 'bg-gray-100',
                  },
                }"
              />
              <span class="text-sm font-medium">
                {{ row.name }}
              </span>
              <span class="text-sm">KODE:{{ row.code }}</span>
            </div>
          </div>
        </template>
        <template #end_date-data="{ row }">
          {{ formatDate(row.start_date) }} - {{ formatDate(row.end_date) }}
        </template>
        <template #discount-data="{ row }">
          <p>
            {{ row.discount_cashback_type === "fixed" ? "Rp" : ""
            }}{{ formatNumber(row.discount_cashback_value)
            }}{{ row.discount_cashback_type === "percentage" ? "%" : "" }}
          </p>
          <div
            v-if="row.voucher_type === 'cashback'"
            class="flex gap-2 items-center text-sm text-black/55"
          >
            <IconCoin class="w-4 h-4" /> Cashback Koin
          </div>
        </template>
        <template #action-data="{ row }">
          <div class="flex flex-col gap-2">
            <UButton
              label="Ubah"
              variant="link"
              color="blue"
              :padded="false"
              :disabled="statusDelete === 'pending'"
              @click="handleEdit(row)"
            />
            <UButton
              label="Hapus"
              variant="link"
              color="blue"
              :padded="false"
              :loading="statusDelete === 'pending' && deleteId === row.uuid"
              @click="handleDelete(row)"
            />
          </div>
        </template>
      </BaseDataTable>
    </div>
  </SellerCard>
</template>

<script setup>
import VoucherCoin from "~/assets/images/voucher-cashback-coin.png";
import VoucherPercentage from "~/assets/images/voucher-percentage.png";
import VoucherFixed from "~/assets/images/voucher-fixed.png";
import { format, isPast } from "date-fns";

const router = useRouter();

const image = {
  coin: VoucherCoin,
  percentage: VoucherPercentage,
  fixed: VoucherFixed,
};

const tabs = [
  {
    label: "Semua",
    key: "all",
  },
  {
    label: "Berjalan",
    key: "ongoing",
  },
  {
    label: "Akan Datang",
    key: "coming_soon",
  },
  {
    label: "Expired",
    key: "expired",
  },
];

const columns = [
  {
    key: "name",
    label: "Nama Voucher | Kode",
  },
  {
    key: "discount",
    label: "Diskon",
  },
  {
    key: "end_date",
    label: "Batas Pemakaian",
  },
  {
    key: "used_count",
    label: "Digunakan",
  },
  {
    key: "action",
    label: "Aksi",
  },
];

const formFilter = ref({
  search: undefined,
  status: 0,
});
const pagination = ref({
  page: 1,
  per_page: 10,
});
const deleteId = ref();

const { data, status, execute } = useApi(
  "/server/api/seller-dashboard/voucher",
  {
    key: "voucher-seller",
    params: computed(() => {
      return {
        ...pagination.value,
        ...formFilter.value,
        status: formFilter.value.status
          ? tabs[formFilter.value.status].key
          : undefined,
      };
    }),
    transform(response) {
      return response?.data || {};
    },
    watch: false,
  }
);

const { execute: deleteVoucher, status: statusDelete } = useSubmit(
  computed(() => `/server/api/seller-dashboard/voucher/${deleteId.value}`),
  {
    method: "DELETE",
    onResponse({ response }) {
      if (response.ok) {
        refreshNuxtData("voucher-seller");
        deleteId.value = null;
      }
    },
  }
);

const items = computed(() => data.value?.data || []);

function handleEdit(row) {
  router.push({
    path: `/seller/voucher/edit/${row.uuid}`,
    state: { voucher: JSON.stringify(row) },
  });
}

function handleFilter() {
  pagination.value.page = 1;
  execute();
}

function handleDelete(row) {
  const confirm = window.confirm("Anda yakin untuk menghapus voucher ini?");
  if (!confirm) return;
  // hit api
  deleteId.value = row.uuid;
  deleteVoucher();
}

function formatDate(dateString) {
  return format(new Date(dateString), "dd MMM yyyy");
}
function checkExpired(dateString) {
  return isPast(new Date(dateString));
}
</script>

<style lang="scss" scoped></style>
