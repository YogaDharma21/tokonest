<template>
  <form class="space-y-5 py-5" @submit.prevent="handleSubmit">
    <SellerCard title="Rincian Dasar">
      <div class="space-y-4 mt-4">
        <SellerFormGroup
          label="Nama Voucher"
          help="Nama Voucher tidak ditampilkan ke Pembeli"
          :error="v$.name.$errors?.[0]?.$message"
        >
          <UInput v-model="form.name" size="lg" maxlength="100">
            <template #trailing>
              <span class="text-sm text-black/55">{{
                `${form.name.length}/100`
              }}</span>
            </template>
          </UInput>
        </SellerFormGroup>
        <SellerFormGroup
          label="Kode Voucher"
          :error="v$.code.$errors?.[0]?.$message"
        >
          <UInput v-model="form.code" size="lg" />
        </SellerFormGroup>
        <SellerFormGroup
          :label="' '"
          :error="v$.is_public.$errors?.[0]?.$message"
        >
          <UCheckbox v-model="form.is_public" label="Hanya Pelanggan Setia" />
        </SellerFormGroup>
      </div>
    </SellerCard>
    <SellerCard title="Pengaturan Bonus">
      <div class="space-y-4 mt-4">
        <SellerFormGroup
          label="Tipe Voucher"
          :ui="{
            wrapper: 'flex items-center gap-4',
            label: {
              wrapper: 'pt-0',
            },
          }"
          :error="v$.voucher_type.$errors?.[0]?.$message"
        >
          <URadioGroup
            v-model="form.voucher_type"
            :options="['discount', 'cashback']"
            :ui="{
              fieldset: 'flex gap-3',
            }"
            :ui-radio="{
              label: 'capitalize',
            }"
          />
        </SellerFormGroup>
        <SellerFormGroup
          label="Tipe Diskon | Diskon"
          :error="
            v$.discount_cashback_type.$errors?.[0]?.$message ||
            v$.discount_cashback_value.$errors?.[0]?.$message
          "
        >
          <div class="flex border rounded-sm divide-x">
            <USelectMenu
              v-model="form.discount_cashback_type"
              :options="[
                {
                  label: 'Nominal',
                  value: 'fixed',
                },
                {
                  label: 'Diskon',
                  value: 'percentage',
                },
              ]"
              :disabled="form.voucher_type === 'cashback'"
              value-attribute="value"
              option-attribute="label"
              variant="none"
            />
            <UInput
              v-model="form.discount_cashback_value"
              class="flex-1"
              :padded="false"
              type="number"
              variant="none"
              :ui="{
                base: 'h-full',
              }"
            >
              <template #leading>
                {{ form.discount_cashback_type === "fixed" ? "Rp" : "%" }}
              </template>
            </UInput>
          </div>
        </SellerFormGroup>
        <SellerFormGroup
          v-if="
            form.discount_cashback_type !== 'fixed' &&
            form.voucher_type === 'discount'
          "
          label="Diskon/Cashback Maksimal"
          :error="v$.discount_cashback_max.$errors?.[0]?.$message"
        >
          <UInput v-model="form.discount_cashback_max" size="lg" type="number">
            <template #leading> Rp </template>
          </UInput>
        </SellerFormGroup>
        <SellerFormGroup
          label="Tanggal Voucher Bisa Digunakan"
          :error="
            v$.start_date.$errors?.[0]?.$message ||
            v$.end_date.$errors?.[0]?.$message
          "
        >
          <div class="grid grid-cols-2 gap-4">
            <BaseDatePicker v-model="form.start_date" />
            <BaseDatePicker v-model="form.end_date" />
          </div>
        </SellerFormGroup>
      </div>
    </SellerCard>

    <SellerCard class="sticky bottom-4">
      <div class="flex justify-end gap-4">
        <UButton
          color="white"
          size="xs"
          :disabled="statusAdd === 'pending' || statusUpdate === 'pending'"
          to="/seller/voucher"
        >
          Kembali
        </UButton>
        <UButton
          type="submit"
          color="primary"
          size="xs"
          :loading="statusAdd === 'pending' || statusUpdate === 'pending'"
        >
          Simpan
        </UButton>
      </div>
    </SellerCard>
  </form>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import { format } from "date-fns";

definePageMeta({
  validate: ({ params }) => {
    if (params.action === "add") {
      return !params.voucherId;
    }
    return params.action === "edit" && !!params.voucherId;
  },
});

const toast = useToast();
const route = useRoute();
const router = useRouter();

route.meta.breadcrumb =
  route.params.action === "add"
    ? [
        {
          label: "Voucher Toko Saya",
          to: "/seller/voucher",
        },
        {
          label: "Buat Voucher Baru",
        },
      ]
    : [
        {
          label: "Voucher Saya",
          to: "/seller/voucher",
        },
        {
          label: "Ubah Voucher",
        },
      ];

const form = ref({
  name: "",
  code: "",
  voucher_type: "discount",
  is_public: false,
  discount_cashback_type: "fixed",
  discount_cashback_value: 0,
  discount_cashback_max: 0,
  start_date: null,
  end_date: null,
});

const rules = {
  name: { required },
  code: { required },
  voucher_type: { required },
  is_public: { required },
  discount_cashback_type: { required },
  discount_cashback_value: {
    required,
    maxValue: helpers.withMessage(
      "Percentage maximal value is 100",
      (value, otherValue) => {
        if (otherValue.discount_cashback_type === "percentage")
          return Number(value) <= 100;
        return true;
      }
    ),
  },
  discount_cashback_max: {},
  start_date: { required },
  end_date: { required },
};

const v$ = useVuelidate(rules, form, {
  $autoDirty: true,
});

watch(
  () => form.value.voucher_type,
  (newValue) => {
    if (newValue === "cashback") {
      form.value.discount_cashback_type = "fixed";
      form.value.discount_cashback_max = 0;
    }
  }
);

const { execute: addVoucher, status: statusAdd } = useSubmit(
  `/server/api/seller-dashboard/voucher`,
  {
    onResponse({ response }) {
      if (response.ok) {
        toast.add({
          color: "green",
          title: "Berhasil menambah voucher",
        });
        router.push("/seller/voucher");
      }
    },
  }
);

const { execute: updateVoucher, status: statusUpdate } = useSubmit(
  `/server/api/seller-dashboard/voucher/${route.params.voucherId}`,
  {
    onResponse({ response }) {
      if (response.ok) {
        toast.add({
          color: "green",
          title: "Berhasil mengubah voucher",
        });
        router.push("/seller/voucher");
      }
    },
  }
);

async function handleSubmit() {
  const isValid = await v$.value.$validate();
  if (!isValid) return;
  const newData = {
    ...form.value,
    is_public: form.value.is_public ? 1 : 0,
    start_date: form.value.start_date
      ? format(form.value.start_date, "yyyy-MM-dd HH:mm:ss")
      : undefined,
    end_date: form.value.end_date
      ? format(form.value.end_date, "yyyy-MM-dd HH:mm:ss")
      : undefined,
  };

  if (route.params.voucherId && route.params.action === "edit") {
    updateVoucher({ ...newData, _method: "PATCH" });
    return;
  }

  addVoucher(newData);
}

onMounted(() => {
  if (route.params.action === "edit") {
    const defaultData = router.options.history.state?.voucher;
    if (!defaultData) {
      router.replace("/seller/voucher");
      return;
    }
    const _defaultData = JSON.parse(defaultData);

    form.value = {
      ...form.value,
      ..._defaultData,
      start_date: _defaultData.start_date
        ? new Date(_defaultData.start_date)
        : null,
      end_date: _defaultData.end_date ? new Date(_defaultData.end_date) : null,
    };
  }
});
</script>

<style lang="scss" scoped></style>
