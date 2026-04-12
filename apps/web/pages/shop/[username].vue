<template>
    <div class="text-sm text-black/80">
        <div class="bg-white">
            <UContainer class="py-5 flex gap-6 items-start">
                <div class="backdrop-seller">
                    <UAvatar
                        :src="seller?.photo_url"
                        size="3xl"
                        :alt="seller?.store_name"
                        img-class="object-cover"
                    />
                    <span class="text-xl text-white font-medium">{{
                        seller?.store_name || "Loading..."
                    }}</span>
                </div>
                <div class="grid grid-cols-2 py-4 gap-4 flex-1">
                    <div class="flex gap-2 items-center">
                        <IconShop />
                        <span>Produk:</span>
                        <span class="text-primary">{{
                            seller?.product_count || 0
                        }}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <UIcon name="i-heroicons:star" />
                        <span>Penilaian:</span>
                        <span class="text-primary">{{
                            seller?.rating_count || 0
                        }}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <IconUserCheck />
                        <span>Bergabung:</span>
                        <span class="text-primary">{{
                            seller?.join_date || "-"
                        }}</span>
                    </div>
                </div>
            </UContainer>
        </div>
        <UContainer class="py-8 space-y-8">
            <UCard>
                <p class="uppercase font-medium text-base">Tentang Toko</p>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <BaseCarousel :items="items" aspect-ratio="16/9" />
                    <div class="flex gap-6 justify-center flex-col">
                        <p class="text-center">FAQ about Packing & Orders :</p>
                        <p class="text-center">
                            Order akan di proses di hari Senin-Sabtu | Jam 10:00
                            - 16:00. Jam di luar operasional akan di hitung
                            besok. Orderan hari sabtu, yang melewati jam
                            packing, akan di kirim hari Senin.
                        </p>
                    </div>
                </div>
            </UCard>
            <div
                v-if="statusProducts === 'pending'"
                class="grid grid-cols-6 gap-5"
            >
                <div v-for="i in 6" :key="`skeleton-${i}`" class="space-y-2">
                    <USkeleton class="aspect-[1/1] w-full" />
                    <USkeleton class="h-4 w-full" />
                    <USkeleton class="h-4 w-1/2" />
                </div>
            </div>
            <div v-else class="grid grid-cols-6 gap-5">
                <BaseProductCard
                    v-for="product in productsData?.data"
                    :key="`product-${product.uuid}`"
                    :title="product.name"
                    :price="product.price_sale || product.price"
                    :image="product.image_url"
                    :slug="product.slug"
                    :discount="product.price_discount_percentage"
                />
            </div>
            <div
                v-if="productsData?.total > 0"
                class="flex justify-center mt-8"
            >
                <BasePagination v-model="page" :total="productsData.total" />
            </div>
        </UContainer>
    </div>
</template>

<script setup>
import BgShop from "@/assets/images/bg-shop.png";
const background = ref(`url('${BgShop}')`);

const route = useRoute();
const page = ref(1);

const { data: seller } = useApi(
    computed(() => `/server/api/seller/${route.params.username}`),
    {
        transform: (res) => res?.data || {},
    },
);

const { data: productsData, status: statusProducts } = useApi(
    "/server/api/product",
    {
        params: computed(() => ({
            seller: route.params.username,
            page: page.value,
            per_page: 12,
        })),
        transform: (res) => res?.data || { data: [], total: 0 },
    },
);

const items = [
    "https://picsum.photos/1920/1080?random=1",
    "https://picsum.photos/1920/1080?random=2",
    "https://picsum.photos/1920/1080?random=3",
    "https://picsum.photos/1920/1080?random=4",
    "https://picsum.photos/1920/1080?random=5",
    "https://picsum.photos/1920/1080?random=6",
];
</script>

<style scoped>
.backdrop-seller {
    @apply px-5 py-7 flex items-center gap-4 w-96 rounded;
    background:
        linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
        v-bind(background);
}
</style>
