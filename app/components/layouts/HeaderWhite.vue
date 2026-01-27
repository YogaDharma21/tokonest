<template>
    <header class="header-white" :class="route.meta?.header?.class">
        <LayoutsHeaderProfile v-if="route.meta?.header?.showProfile" />
        <div class="header-bottom">
            <UContainer class="flex justify-between items-center gap-10 py-7">
                <div class="flex gap-4 items-center">
                    <BaseLogo color="orange" />
                    <template v-if="route.meta.header?.title">
                        <hr class="w-px h-5 bg-primary" />
                        <p class="text-xl">{{ route.meta.header.title }}</p>
                    </template>
                </div>
                <LayoutsSearchBar
                    v-if="route.meta?.header?.showSearch"
                    class="w-1/2"
                    :padded="false"
                />
            </UContainer>
        </div>
    </header>
</template>

<script setup>
const route = useRoute();

// const meta = {
//     showProfile: false,
//     title: 'Keranjang',
//     showSearch: false
// }
</script>

<style scoped></style>
