<template>
  <BaseLoading v-if="status === 'pending'" />
  <div v-else class="text-sm text-black/80">
    <div class="bg-white divide-y divide-dashed">
      <div class="flex justify-between gap-4 px-6 py-5">
        <UButton
          label="Kembali"
          to="/seller/order"
          icon="i-heroicons:chevron-left"
          :padded="false"
          variant="link"
          color="gray"
        />
        <div class="divide-x">
          <span class="px-4">NO. PESANAN. {{ data?.invoice_number }}</span>
          <span class="px-4 uppercase text-primary">
            {{ lastStatus?.description }}
          </span>
        </div>
      </div>
      <div class="px-6 py-10">
        <BaseTimelineHorizontal
          :items="timelineStatus"
          class="grid grid-cols-5"
        />
      </div>
      <div
        v-if="
          ['paid', 'on_processing', 'on_delivery'].includes(lastStatus?.status)
        "
        class="bg-yellow-50/30 flex justify-end px-6 py-3 gap-2"
      >
        <UButton
          v-if="lastStatus?.status === 'paid'"
          class="min-w-56 justify-center"
          label="Proses Order ini"
          @click="handleProcessOrder"
        />
        <UButton
          v-if="['on_processing', 'on_delivery'].includes(lastStatus?.status)"
          class="min-w-56 justify-center"
          label="Update Status Pengiriman"
          @click="openModalUpdateStatus('on_delivery')"
        />

        <UModal v-model="modalUpdateStatus.open">
          <UCard>
            <template #header> Update Status Order </template>
            <form @submit.prevent="handleUpdateStatus">
              <UFormGroup label="Deskripsi" :error="v$.$errors?.[0]?.$message">
                <UTextarea v-model="modalUpdateStatus.form.description" />
              </UFormGroup>
              <div class="flex justify-end gap-4 mt-6">
                <UButton
                  color="gray"
                  variant="link"
                  label="Nanti Saja"
                  :disabled="statusUpdate === 'pending'"
                  @click="closeModalUpdateStatus"
                />
                <UButton
                  type="submit"
                  label="Simpan"
                  :loading="statusUpdate === 'pending'"
                />
              </div>
            </form>
          </UCard>
        </UModal>
      </div>

      <div class="px-6 py-5">
        <div class="flex justify-between items-center">
          <p class="text-xl">Alamat Pengiriman</p>
          <div class="text-right text-xs text-black/55">
            <p class="uppercase">
              {{ data?.courier }} - {{ data?.courier_type }}
            </p>
            <p>XXXXXXXXX</p>
          </div>
        </div>
        <div class="flex divide-x mt-3">
          <div class="w-80 py-2 pr-3">
            <p>{{ data?.address?.receiver_name }}</p>
            <p class="text-black/55 mt-2">
              {{ data?.address?.receiver_phone }}
            </p>
            <p class="text-black/55">
              {{ data?.address?.detail_address }}
              {{ data?.address?.district }}, {{ data?.address?.city?.name }},
              {{ data?.address?.city?.province?.name }},
              {{ data?.address?.postal_code }}
              {{ data?.address?.address_note }}
            </p>
          </div>
          <div class="pl-3">
            <BaseTimelineVertical :items="orderHistory" />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 divide-y">
      <div class="px-6 py-3">
        <div class="divide-y">
          <FeatureProfileOrderCardProduct
            v-for="item in data?.items"
            :key="`product-${item.uuid}`"
            size="sm"
            class="py-3"
            :item="item"
          />
        </div>
      </div>
      <div>
        <table class="order-price">
          <tbody>
            <tr v-for="price in priceItem" :key="price.label">
              <td>
                <template v-if="price.label === 'Metode Pembayaran'">
                  <div class="flex items-center gap-2 justify-end">
                    <img src="~/assets/images/garansi.png" class="h-4 w-4" />
                    <span>{{ price.label }}</span>
                  </div>
                </template>
                <template v-else>{{ price.label }}</template>
              </td>
              <td>{{ price.value }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  IconOrder,
  IconPaid,
  IconTruckOutline,
  IconInbox,
  IconStars,
} from "#components";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
definePageMeta({
  breadcrumb: [
    {
      label: "Pesanan Saya",
      to: "/seller/order",
    },
    {
      label: "Detail Pesanan",
    },
  ],
});
const modalUpdateStatus = reactive({
  open: false,
  status: "",
  form: {
    description: "",
  },
});
const rules = {
  description: { required },
};

const v$ = useVuelidate(
  rules,
  computed(() => ({ description: modalUpdateStatus.form.description })),
  { $autoDirty: true }
);

const route = useRoute();
const nuxtApp = useNuxtApp();
const { data, status, refresh } = useApi(
  `/server/api/seller-dashboard/order/${route.params.orderId}`,
  {
    key: `order-seller-${route.params.orderId}`,
    getCachedData() {
      return (
        nuxtApp.payload.data?.[`order-seller-${route.params.orderId}`] ||
        nuxtApp.static.data?.[`order-seller-${route.params.orderId}`]
      );
    },
    transform(response) {
      return response?.data || {};
    },
  }
);
const { execute, status: statusUpdate } = useSubmit(
  computed(
    () => `/server/api/seller-dashboard/order/${route.params.orderId}/status`
  ),
  {
    onResponse({ response }) {
      if (response.ok) {
        modalUpdateStatus.open = false;
        modalUpdateStatus.status = "";
        modalUpdateStatus.form.description = "";
        v$.value.$reset();
        refresh();
      }
    },
  }
);

const lastStatus = computed(() => data.value?.last_status || {});

const timelineStatus = computed(() => {
  const pendingStatus = data.value?.status?.find(
    (item) => item.status === "pending_payment"
  );
  const paidStatus = data.value?.status?.find((item) => item.status === "paid");
  const processStatus = data.value?.status?.find(
    (item) => item.status === "on_processing"
  );
  const deliveryStatus = data.value?.status?.find(
    (item) => item.status === "on_delivery"
  );
  const doneStatus = data.value?.status?.find((item) => item.status === "done");
  return [
    {
      title: "Pesanan Dibuat",
      time: pendingStatus?.created_at,
      passed:
        !["pending_payment", "failed"].includes(lastStatus.value?.status) &&
        pendingStatus,
      active: lastStatus.value?.status === "pending_payment",
      icon: IconOrder,
    },
    {
      title: `Pesanan${paidStatus ? "" : " Belum"} Dibayarkan (Rp${formatNumber(
        data.value?.total
      )})`,
      time: paidStatus?.created_at,
      passed: lastStatus.value?.status !== "paid" && paidStatus,
      active: lastStatus.value?.status === "paid",
      icon: IconPaid,
    },
    {
      title: "Sedang Dikemas",
      time: processStatus?.created_at,
      icon: IconTruckOutline,
      passed: lastStatus.value?.status !== "on_processing" && processStatus,
      active: lastStatus.value?.status === "on_processing",
    },
    {
      title: "Dikirim",
      time: deliveryStatus?.created_at,
      icon: IconInbox,
      passed: lastStatus.value?.status !== "on_delivery" && deliveryStatus,
      active: lastStatus.value?.status === "on_delivery",
    },
    {
      title: data.value?.items?.[0]?.is_reviewed ? "Selesai" : "Belum Dinilai",
      time: data.value?.items?.[0]?.is_reviewed ? doneStatus?.created_at : "",
      icon: IconStars,
      passed: lastStatus.value?.status !== "done" && doneStatus,
      active: lastStatus.value?.status === "done",
    },
  ];
});

const orderHistory = computed(() => {
  return data.value?.status
    ?.map((item, index, arr) => ({
      title: item?.description,
      time: item?.created_at,
      active: index === arr.length - 1,
    }))
    .sort((a, b) => new Date(b.time) - new Date(a.time));
});

// [
//   {
//     title: "Pesanan Dibayar",
//     time: "11-08-2024 16:27",
//     active: true,
//   },
//   {
//     title: "Pesanan Dibuat",
//     time: "11-08-2024 16:26",
//   },
// ]

const subtotalProduct = computed(() => {
  return data.value?.items?.reduce((result, current) => {
    result += current.total;
    return result;
  }, 0);
});

const priceItem = computed(() => [
  {
    label: "Subtotal Produk",
    value: `Rp ${formatNumber(subtotalProduct.value)}`,
  },
  {
    label: "Total Pesanan",
    value: `Rp ${formatNumber(data.value?.total)}`,
    class: "text-primary text-2xl",
  },
]);

function openModalUpdateStatus(updateStatus) {
  modalUpdateStatus.open = true;
  modalUpdateStatus.status = updateStatus;
  modalUpdateStatus.form.description = "";
}

function closeModalUpdateStatus() {
  modalUpdateStatus.open = false;
  modalUpdateStatus.form.description = "";
  v$.value.$reset();
}

function handleProcessOrder() {
  const confirm = window.confirm("Anda yakin untuk memproses order ini?");
  if (!confirm) return;
  execute({
    status: "on_processing",
  });
}

function handleUpdateStatus() {
  execute({
    status: modalUpdateStatus.status,
    delivery_note: modalUpdateStatus.form.description,
  });
}
</script>

<style scoped>
table.order-price {
  @apply w-full;
}

.order-price tr td {
  @apply text-right;
  @apply border border-black/10 border-dashed;
}

.order-price tr td:first-child {
  @apply p-3;
  @apply text-black/55 text-xs;
  border-left: none;
}
.order-price tr td:last-child {
  @apply px-6 py-3;
  border-right: none;
}

.order-price tr:first-child td {
  border-top: none;
  width: 67%;
}
.order-price tr:last-child td {
  border-bottom: none;
}
</style>
