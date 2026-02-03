<template>
  <div class="space-y-5 mt-8">
    <div
      class="border border-yellow-500 bg-yellow-50 rounded-sm py-2 px-3 flex gap-2"
    >
      <IconCoinSolid />
      <span class="font-medium">Beri penilaian & dapatkan 25 Koin!</span>
    </div>
    <FeatureProfileOrderCardProduct
      hide-price
      hide-description
      size="sm"
      :item="item"
    />
    <div class="flex gap-2 items-center">
      <span class="w-44">Kualitas Produk</span>
      <BaseRating v-model="formRating.rating" size="lg" color="yellow" />
      <span
        :class="{
          'text-yellow-500': formRating.rating > 3,
          'text-black/55': formRating.rating <= 3,
        }"
        >{{ reviewMessages[formRating.rating - 1] }}</span
      >
    </div>
    <div v-if="formRating.rating">
      <div class="bg-gray-100 px-6 py-3 rounded-sm space-y-3">
        <UFormGroup :error="v$.description.$errors?.[0]?.$message">
          <UTextarea
            v-model="formRating.description"
            placeholder="Bagikan penilaianmu tentang produk ini untuk membantu Pembeli lain."
          />
        </UFormGroup>
        <div class="flex gap-2 items-start">
          <FeatureSellerProductMedia
            v-model="formRating.photo"
            :max="2"
            class="items-start !gap-2 flex-row-reverse"
          >
            <template #activator="{ onChooseFile }">
              <UButton
                size="xs"
                variant="soft"
                icon="i-heroicons:camera-solid"
                label="Tambah Foto"
                @click="onChooseFile"
              />
            </template>
          </FeatureSellerProductMedia>

          <FeatureSellerProductMedia
            v-model="formRating.video"
            :max="1"
            class="items-start !gap-2 flex-row-reverse"
            type="video"
          >
            <template #activator="{ onChooseFile }">
              <UButton
                size="xs"
                variant="soft"
                icon="i-heroicons:video-camera-solid"
                label="Tambahkan video"
                @click="onChooseFile"
              />
            </template>
          </FeatureSellerProductMedia>
        </div>
        <p class="text-right text-black/55">
          Tambahkan 50 karakter dengan 1 foto untuk mendapatkan 25 Koin!
        </p>
      </div>
    </div>
    <UCheckbox
      v-model="formRating.showUsername"
      label="Tampilkan username pada penilaian"
      :help="captionReviewUsername"
    />
    <div class="space-y-4">
      <p class="text-base">Tentang Layanan</p>
      <div class="flex gap-2 items-center">
        <span class="w-44">Kecepatan Jasa Kirim</span>
        <BaseRating
          v-model="formRating.courierRating"
          size="lg"
          color="yellow"
        />
        <span
          :class="{
            'text-yellow-500': formRating.courierRating > 3,
            'text-black/55': formRating.courierRating <= 3,
          }"
          >{{ reviewMessages[formRating.courierRating - 1] }}</span
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
import { minLength, minValue, required } from "@vuelidate/validators";

defineProps({
  item: {
    type: Object,
    default: () => ({}),
  },
});

const formRating = defineModel({
  type: Object,
  default: () => ({
    rating: 0,
    courierRating: 0,
    description: "",
    photo: [],
    video: [],
    showUsername: false,
  }),
});

const rules = {
  rating: { minValue: minValue(1), required },
  courierRating: { minValue: minValue(1), required },
  description: { minLength: minLength(50), required },
  photo: {},
  video: {},
};
const v$ = useVuelidate(rules, formRating, {
  $autoDirty: true,
  $scope: "review",
});

const { maskText } = useMasking();
const session = useSession();

const captionReviewUsername = computed(
  () =>
    `Username yang akan ditampilkan adalah ${maskText(
      session.profile.username,
      1,
      1
    )}`
);

const reviewMessages = [
  "Sangat Buruk",
  "Buruk",
  "Biasa",
  "Baik",
  "Sangat Baik",
];
</script>

<style lang="scss" scoped></style>
