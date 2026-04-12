<template>
  <div class="space-y-9">
    <div class="flex justify-between items-center gap-2">
      <h3 class="font-medium text-xl">Produk Saya</h3>
      <UButton
        icon="i-heroicons:plus"
        label="Tambah Produk Baru"
        to="/seller/product/add"
      />
    </div>
    <UCard>
      <div class="space-y-6">
        <div class="flex gap-4 items-center">
          <UInput
            v-model="formFilter.search"
            leading-icon="i-heroicons:magnifying-glass"
            class="flex-1"
            placeholder="Cari Nama Produk, SKU Induk, Kode Variasi, ID Produk"
          />
          <USelectMenu
            v-model="formFilter.category"
            :options="optionsCategories"
            placeholder="Cari berdasarkan kategori"
            class="flex-1"
            :loading="statusCategory === 'pending'"
            :disabled="statusCategory === 'pending'"
            value-attribute="slug"
            option-attribute="name"
          />
          <UButton
            size="xs"
            label="Terapkan"
            variant="outline"
            @click="applyFilter"
          />
          <UButton
            size="xs"
            label="Atur Ulang"
            color="white"
            @click="resetFilter"
          />
        </div>
        <p v-if="status !== 'pending'" class="text-lg font-medium">
          {{ data?.total || 0 }} Produk
        </p>
        <BaseDataTable
          v-model:page="pagination.page"
          :columns="columns"
          :rows="products"
          empty-text="Produk Tidak Ditemukan"
          :loading="status === 'pending'"
          :total="data?.total"
          :per-page="pagination.per_page"
          @update:page="execute"
        >
          <template #name-data="{ row }">
            <div class="flex gap-2">
              <img :src="row.images?.[0]" class="w-14 h-14 object-cover" />
              <div class="flex flex-col items-start gap-0.5">
                <span class="text-sm font-medium">{{ row.name }}</span>
                <span class="text-sm text-black/65">
                  ID Produk: {{ row.uuid }}
                </span>
              </div>
            </div>
          </template>
          <template #price-data="{ row }">
            Rp{{ formatNumber(row.price) }}
          </template>
          <template #action-data="{ row }">
            <div class="flex flex-col gap-1">
              <UButton
                variant="link"
                :padded="false"
                color="blue"
                label="Ubah"
                :disabled="statusDelete === 'pending'"
                @click="handleEdit(row)"
              />
              <UButton
                variant="link"
                :padded="false"
                color="blue"
                label="Hapus"
                :loading="statusDelete === 'pending' && deleteId === row.uuid"
                @click="handleDelete(row)"
              />
            </div>
          </template>
        </BaseDataTable>
      </div>
    </UCard>
  </div>
</template>

<script setup>
const nuxtApp = useNuxtApp();

const router = useRouter();

const formFilter = ref({
  search: undefined,
  category: undefined,
});
const pagination = ref({
  page: 1,
  per_page: 10,
});

const deleteId = ref();

const columns = [
  {
    key: "name",
    label: "Produk",
  },
  {
    key: "sale_count",
    label: "Penjualan",
  },
  {
    key: "price",
    label: "Harga",
  },
  {
    key: "stock",
    label: "Stok",
  },
  {
    key: "action",
    label: "Aksi",
  },
];

const { data: categories, status: statusCategory } = useApi(
  "/server/api/category",
  {
    key: "category-list",
    transform(response) {
      return (response?.data || []).reduce((result, parent) => {
        result.push(
          ...parent.childs.map((child) => ({
            ...child,
            icon: parent.icon,
            name: `${parent.name} - ${child.name}`,
          }))
        );
        return result;
      }, []);
    },
    getCachedData() {
      return (
        nuxtApp.payload.data?.["category-list"] ||
        nuxtApp.static.data?.["category-list"]
      );
    },
  }
);

const { data, status, execute } = useApi(
  "/server/api/seller-dashboard/product",
  {
    key: "product-seller",
    params: computed(() => {
      return {
        ...pagination.value,
        ...formFilter.value,
      };
    }),
    transform(response) {
      return response?.data || {};
    },
    watch: false,
  }
);

const { execute: deleteProduct, status: statusDelete } = useSubmit(
  computed(() => `/server/api/seller-dashboard/product/${deleteId.value}`),
  {
    method: "DELETE",
    onResponse({ response }) {
      if (response.ok) {
        refreshNuxtData("product-seller");
        deleteId.value = null;
      }
    },
  }
);

const optionsCategories = computed(() =>
  categories.value?.map((item) => ({ ...item, icon: undefined }))
);

const products = computed(() => data.value?.data || []);
function handleEdit(row) {
  router.push({
    path: `/seller/product/edit/${row.uuid}`,
    state: { product: JSON.stringify(row) },
  });
}

function handleDelete(row) {
  const confirm = window.confirm("Anda yakin untuk menghapus produk ini?");
  if (!confirm) return;
  // hit api
  deleteId.value = row.uuid;
  deleteProduct();
}

function applyFilter() {
  // reset pagination 1
  pagination.value.page = 1;
  // hit api
  execute();
}

function resetFilter() {
  // reset pagination 1
  pagination.value.page = 1;
  // reset formFilter default value
  formFilter.value = {
    search: undefined,
    category: undefined,
  };
  // hit api
  execute();
}
</script>

<style lang="scss" scoped></style>
