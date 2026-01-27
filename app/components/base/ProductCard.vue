<template>
    <NuxtLink
        class="bg-white border relative hover:border-primary"
        :to="`/products/${slug}`"
    >
        <div
            v-if="discount"
            class="absolute right-0 bg-primary-50 px-1 py-0.5 text-primary text-xs font-normal"
        >
            -{{ formattedDiscount }}%
        </div>
        <img :src="image" class="aspect-square object-cover" />
        <div class="p-2">
            <p class="text-sm font-normal text-black/80 line-clamp-2">
                {{ title }}
            </p>
            <div class="mt-8 flex justify-between gap-2 items-center">
                <div class="product-price">
                    <p class="text-primary font-medium text-base">
                        <span class="text-xs">Rp</span>{{ formattedPrice }}
                    </p>
                </div>
                <div v-if="sale !== undefined" class="product-sale">
                    <p class="text-xs font-normal">
                        {{ formattedSale }} Terjual
                    </p>
                </div>
            </div>
        </div>
    </NuxtLink>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    image: {
        type: String,
        default: "",
    },
    price: {
        type: Number,
        default: 0,
    },
    sale: {
        type: Number,
        default: undefined,
    },
    discount: {
        type: Number,
        default: undefined,
    },
    slug: {
        type: String,
        default: "",
    },
});
const formattedPrice = computed(() => formatNumber(props.price));
const formattedSale = computed(() => formatNumber(props.sale));
const formattedDiscount = computed(() => formatNumber(props.discount));
</script>

<style scoped></style>
