<template>
  <form class="space-y-4">
    <SellerCard title="Informasi Produk">
      <div class="mt-4 space-y-6">
        <SellerFormGroup
          label="Foto Produk"
          required
          :error="v$.images.$errors?.[0]?.$message"
        >
          <FeatureSellerProductMedia v-model="form.images" :max="9" />
        </SellerFormGroup>
        <SellerFormGroup
          label="Video Produk"
          :error="v$.video.$errors?.[0]?.$message"
        >
          <div class="flex gap-6 items-center">
            <FeatureSellerProductMedia
              v-model="form.video"
              :max="1"
              type="video"
              icon="i-heroicons:video-camera-solid"
            />
            <div class="text-xs text-black/50">
              <p>
                File video maks. harus 30Mb dengan resolusi tidak melebihi 1280
                x 1280px.
              </p>
              <p>Durasi: 10-60detik</p>
              <p>Format: MP4</p>
              <p>
                Catatan: Kamu dapat menampilkan produk saat video sedang
                diproses. Video akan muncul setelah berhasil diproses.
              </p>
            </div>
          </div>
        </SellerFormGroup>
        <SellerFormGroup
          label="Nama Produk"
          :error="v$.name.$errors?.[0]?.$message"
        >
          <UInput v-model="form.name" size="lg" />
        </SellerFormGroup>
        <SellerFormGroup
          label="Kategori"
          required
          :error="v$.category_slug.$errors?.[0]?.$message"
        >
          <USelectMenu
            v-model="form.category_slug"
            :options="optionsCategories"
            size="lg"
            :loading="statusCategory === 'pending'"
            :disabled="statusCategory === 'pending'"
            value-attribute="slug"
            option-attribute="name"
          />
        </SellerFormGroup>
        <SellerFormGroup
          label="Deskripsi Produk"
          required
          :help="`${form.description.length}/3000`"
          :error="v$.description.$errors?.[0]?.$message"
        >
          <UTextarea v-model="form.description" size="lg" />
        </SellerFormGroup>
      </div>
    </SellerCard>
    <SellerCard title="Informasi Penjualan">
      <div class="mt-4 space-y-6">
        <div class="grid grid-cols-2 gap-6">
          <SellerFormGroup
            label="Harga"
            :error="v$.price.$errors?.[0]?.$message"
            required
          >
            <UInput v-model="form.price" size="lg" type="number">
              <template #leading>Rp</template>
            </UInput>
          </SellerFormGroup>
          <SellerFormGroup
            label="Harga Diskon"
            :error="v$.price_sale.$errors?.[0]?.$message"
          >
            <UInput v-model="form.price_sale" size="lg" type="number">
              <template #leading>Rp</template>
            </UInput>
          </SellerFormGroup>
        </div>
        <SellerFormGroup
          label="Stok"
          :error="v$.stock.$errors?.[0]?.$message"
          required
        >
          <UInput v-model="form.stock" size="lg" type="number" />
        </SellerFormGroup>
        <SellerFormGroup label="Variasi">
          <div class="space-y-4">
            <FeatureSellerProductVariant
              v-for="(variant, index) in form.variations"
              :key="`variations-${index}`"
              v-model="variant.name"
              v-model:value="variant.value"
              @delete="handleDeleteVariants(index)"
            />

            <div class="p-4 bg-gray-100 rounded-sm">
              <UButton
                color="white"
                class="text-primary"
                size="xs"
                icon="i-heroicons:plus"
                @click="handleAddVariations"
              >
                Tambah Variasi {{ form.variations.length + 1 }}
              </UButton>
            </div>
          </div>
        </SellerFormGroup>
      </div>
    </SellerCard>
    <SellerCard title="Pengiriman">
      <div class="mt-4 space-y-6">
        <SellerFormGroup
          label="Berat"
          required
          :error="v$.weight.$errors?.[0]?.$message"
        >
          <div class="flex">
            <UInput v-model="form.weight" size="lg" type="number">
              <template #trailing>gr</template>
            </UInput>
          </div>
        </SellerFormGroup>
        <SellerFormGroup
          label="Ukuran Paket"
          :error="
            v$.length.$errors?.[0]?.$message ||
            v$.width.$errors?.[0]?.$message ||
            v$.height.$errors?.[0]?.$message
          "
        >
          <div class="flex gap-2 items-center">
            <UInput
              v-model="form.length"
              size="lg"
              type="number"
              placeholder="L"
            >
              <template #trailing>cm</template>
            </UInput>
            <UIcon name="i-heroicons:x-mark" class="text-black/20" />
            <UInput v-model="form.width" size="lg" type="number">
              <template #trailing>cm</template>
            </UInput>
            <UIcon name="i-heroicons:x-mark" class="text-black/20" />
            <UInput v-model="form.height" size="lg" type="number">
              <template #trailing>cm</template>
            </UInput>
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
          to="/seller/product"
        >
          Kembali
        </UButton>
        <UButton
          color="primary"
          size="xs"
          :loading="statusAdd === 'pending' || statusUpdate === 'pending'"
          @click="handleSubmit"
        >
          Simpan
        </UButton>
      </div>
    </SellerCard>
  </form>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
import {
  maxLength,
  maxValue,
  minLength,
  minValue,
  required,
} from "@vuelidate/validators";

const route = useRoute();
definePageMeta({
  validate: ({ params }) => {
    if (params.action === "add") {
      return !params.productId;
    }
    return params.action === "edit" && !!params.productId;
  },
});

route.meta.breadcrumb =
  route.params.action === "add"
    ? [
        {
          label: "Produk Saya",
          to: "/seller/product",
        },
        {
          label: "Tambah Produk Baru",
        },
      ]
    : [
        {
          label: "Produk Saya",
          to: "/seller/product",
        },
        {
          label: "Ubah Produk",
        },
      ];

const form = ref({
  name: "",
  price: null,
  price_sale: null,
  stock: null,
  category_slug: "",
  description: "",
  weight: null,
  length: null,
  width: null,
  height: null,
  video: [],
  images: [],
  variations: [
    {
      name: "",
      value: [{ name: "" }],
    },
  ],
});

const rules = computed(() => ({
  name: { required },
  price: { required, minValue: minValue(100) },
  price_sale: {
    minValue: minValue(100),
    maxValue: maxValue(computed(() => form.value.price)),
  },
  stock: {
    required,
    minValue: minValue(1),
  },
  category_slug: { required },
  description: { required, maxLength: maxLength(3000) },
  weight: { required },
  length: { required },
  width: { required },
  height: { required },
  images: {
    required,
    minLength: minLength(1),
  },
  video: {
    required,
    minLength: minLength(1),
  },
}));

const v$ = useVuelidate(rules, form, {
  $autoDirty: true,
  $scope: "product",
});

const toast = useToast();
const nuxtApp = useNuxtApp();
const router = useRouter();

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

const { execute: addProduct, status: statusAdd } = useSubmit(
  "/server/api/seller-dashboard/product",
  {
    onResponse({ response }) {
      if (response.ok) {
        toast.add({
          color: "green",
          title: "Berhasil menambahkan produk",
        });
        router.push("/seller/product");
      }
    },
  }
);

const { execute: updateProduct, status: statusUpdate } = useSubmit(
  computed(
    () => `/server/api/seller-dashboard/product/${route.params.productId}`
  ),
  {
    onResponse({ response }) {
      if (response.ok) {
        toast.add({
          color: "green",
          title: "Berhasil mengubah produk",
        });
        router.push("/seller/product");
      }
    },
  }
);

const optionsCategories = computed(() =>
  categories.value?.map((item) => ({ ...item, icon: undefined }))
);

function handleAddVariations() {
  form.value.variations.push({
    name: "",
    value: [{ name: "" }],
  });
}
function handleDeleteVariants(index) {
  form.value.variations.splice(index, 1);
}

async function handleSubmit() {
  const isValid = await v$.value.$validate();
  if (!isValid) return;

  const formData = new FormData();
  formData.append("name", form.value.name);
  formData.append("price", form.value.price);
  if (form.value.price_sale) {
    formData.append("price_sale", form.value.price_sale);
  }
  formData.append("stock", form.value.stock);
  formData.append("category_slug", form.value.category_slug);
  formData.append("description", form.value.description);
  formData.append("weight", form.value.weight);
  formData.append("length", form.value.length);
  formData.append("width", form.value.width);
  formData.append("height", form.value.height);
  if (form.value.video[0] && typeof form.value.video[0] !== "string") {
    formData.append("video", form.value.video[0]);
  }
  form.value.images.forEach((img) => {
    formData.append(typeof img === "string" ? "old_images[]" : "images[]", img);
  });
  form.value.variations.forEach((variant, index) => {
    formData.append(`variations[${index}][name]`, variant.name);
    variant.value.forEach((value, idx) => {
      formData.append(`variations[${index}][values][${idx}]`, value.name);
    });
  });

  if (route.params.productId) {
    formData.append("_method", "PATCH");
    updateProduct(formData);
    return;
  }

  addProduct(formData);
}

onMounted(() => {
  if (route.params.action === "edit") {
    const defaultData = router.options.history.state?.product;
    if (!defaultData) {
      router.replace("/seller/product");
      return;
    }
    const _defaultData = JSON.parse(defaultData);

    form.value = {
      name: _defaultData.name,
      price: _defaultData.price,
      price_sale: _defaultData.price_sale,
      stock: _defaultData.stock,
      category_slug: _defaultData.category.slug,
      description: _defaultData.description,
      weight: _defaultData.weight,
      length: _defaultData.length,
      width: _defaultData.width,
      height: _defaultData.height,
      video: [_defaultData.video_url],
      images: _defaultData.images,
      variations: _defaultData.variations.map((variant) => ({
        name: variant.name,
        value: variant.values.map((value) => ({ name: value })),
      })),
    };
  }
});
</script>

<style lang="scss" scoped></style>
