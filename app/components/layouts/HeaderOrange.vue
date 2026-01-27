<template>
    <header class="text-white flex flex-col">
        <LayoutsHeaderProfile v-if="defaultMeta.showProfile" />
        <div class="bg-linear-to-b from-primary to-[#FF6633] flex-1 py-6">
            <UContainer class="flex justify-between items-center gap-12 h-full">
                <BaseLogo />
                <LayoutsSearchBar
                    v-if="defaultMeta.showSearch"
                    class="flex-1"
                />
                <UButton v-if="defaultMeta.showCart" variant="link" to="/cart">
                    <IconCart />
                </UButton>
            </UContainer>
        </div>
    </header>
</template>

<script setup>
const route = useRoute();

// const meta = {
//     showProfile: false,
//     showSearch: false
//     showCart: false
// }

const defaultMeta = computed(() => {
    return {
        showProfile:
            route.meta?.header && "showProfile" in route.meta.header
                ? route.meta.header.showProfile
                : true,
        showSearch:
            route.meta?.header && "showSearch" in route.meta.header
                ? route.meta.header.showSearch
                : true,
        showCart:
            route.meta?.header && "showCart" in route.meta.header
                ? route.meta.header.showCart
                : true,
    };
});
</script>

<style scoped></style>
