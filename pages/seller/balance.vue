<template>
  <SellerCard title="Informasi Saldo">
    <div class="space-y-6 mt-4">
      <div class="border rounded p-4 shadow space-y-4">
        <p class="text-black/65">Saldo</p>
        <div class="flex items-center gap-4">
          <span class="text-3xl font-medium">Rp{{ balance }}</span>
          <UButton @click="openWithdraw = true">Tarik Dana</UButton>
        </div>
      </div>
      <p v-if="status !== 'pending'" class="text-lg font-medium">
        {{ data?.total || 0 }} Transaksi
      </p>
      <BaseDataTable
        v-model:page="pagination.page"
        :columns="columns"
        :rows="transaction"
        :loading="status === 'pending'"
        :total="data?.total"
        :per-page="pagination.per_page"
        @update:page="execute"
      >
        <template #amount-data="{ row }">
          Rp{{ formatNumber(row.amount) }}
        </template>
      </BaseDataTable>
    </div>

    <UModal v-model="openWithdraw">
      <UCard>
        <div class="flex justify-between items-center">
          <span>Tarik Saldo</span>
          <UButton
            icon="i-heroicons:x-mark"
            variant="ghost"
            color="gray"
            @click="openWithdraw = false"
          />
        </div>
        <form class="space-y-4 mt-4" @submit.prevent="handleSubmitWithdraw">
          <UFormGroup label="Bank" :error="v$.bank_code.$errors?.[0]?.$message">
            <USelectMenu
              v-model="form.bank_code"
              :options="bankList"
              size="lg"
              :loading="statusBank === 'pending'"
              :disabled="statusBank === 'pending'"
              option-attribute="name"
              value-attribute="code"
            />
          </UFormGroup>
          <UFormGroup
            label="No. Rekening"
            :error="v$.bank_account_number.$errors?.[0]?.$message"
          >
            <UInput v-model="form.bank_account_number" size="lg" />
          </UFormGroup>
          <UFormGroup
            label="Nama Pemilik Rekening"
            :error="v$.bank_account_holder.$errors?.[0]?.$message"
          >
            <UInput v-model="form.bank_account_holder" size="lg" />
          </UFormGroup>
          <UFormGroup
            label="Jumlah Penarikan"
            :error="v$.amount.$errors?.[0]?.$message"
          >
            <UInput v-model="form.amount" type="number" size="lg" />
          </UFormGroup>
          <UFormGroup
            label="Deskripsi"
            :error="v$.description.$errors?.[0]?.$message"
          >
            <UTextarea v-model="form.description" size="lg" />
          </UFormGroup>
          <div class="flex gap-2 items-center justify-end pt-4">
            <UButton
              color="white"
              :disabled="statusWithdraw === 'pending'"
              @click="handleCloseWithdraw"
            >
              Tutup
            </UButton>
            <UButton type="submit" :loading="statusWithdraw === 'pending'">
              Konfirmasi
            </UButton>
          </div>
        </form>
      </UCard>
    </UModal>
  </SellerCard>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
import { maxValue, minValue, required } from "@vuelidate/validators";

definePageMeta({
  breadcrumb: [{ label: "Saldo Penjual" }],
});
const nuxtApp = useNuxtApp();
const session = useSession();

const openWithdraw = ref(false);

const pagination = ref({
  page: 1,
  per_page: 10,
});
const form = ref({
  amount: 0,
  description: "",
  bank_code: "",
  bank_account_number: "",
  bank_account_holder: "",
});

const rules = {
  amount: {
    required,
    minValue: minValue(10000),
    maxValue: maxValue(computed(() => session.profile.balance)),
  },
  description: { required },
  bank_code: { required },
  bank_account_number: { required },
  bank_account_holder: { required },
};

const v$ = useVuelidate(rules, form, {
  $autoDirty: true,
});

const balance = computed(() => formatNumber(session.profile.balance || 0));

const { data, status, execute, refresh } = useApi(
  "/server/api/seller-dashboard/wallet-transaction",
  {
    key: "balance-seller",
    params: computed(() => {
      return {
        ...pagination.value,
      };
    }),
    transform(response) {
      return response?.data || {};
    },
    watch: false,
  }
);

const { data: bankList, status: statusBank } = useApi(
  "/server/api/seller-dashboard/list-bank",
  {
    key: "bank-list",
    transform(response) {
      return response?.data || [];
    },
    getCacheData() {
      return (
        nuxtApp.payload.data?.["bank-list"] ||
        nuxtApp.static.data?.["bank-list"]
      );
    },
  }
);

const { execute: withdrawBalance, status: statusWithdraw } = useSubmit(
  "/server/api/seller-dashboard/withdraw",
  {
    onResponse({ response }) {
      if (response.ok) {
        handleCloseWithdraw();
        refresh();
        refreshNuxtData("profile");
      }
    },
  }
);

const columns = [
  {
    label: "Transaksi Tipe",
    key: "type",
    rowClass: "capitalize",
  },
  {
    label: "Jumlah",
    key: "amount",
    rowClass: "font-bold",
  },
  {
    label: "Deskripsi",
    key: "description",
  },
  {
    label: "Waktu",
    key: "time",
  },
];

const transaction = computed(() => data.value?.data || []);

async function handleSubmitWithdraw() {
  const isValid = await v$.value.$validate();
  if (!isValid) return;
  withdrawBalance(form.value);
}
function handleCloseWithdraw() {
  form.value = {
    amount: 0,
    description: "",
    bank_code: "",
    bank_account_number: "",
    bank_account_holder: "",
  };
  v$.value.$reset();
  openWithdraw.value = false;
}
</script>

<style lang="scss" scoped></style>
