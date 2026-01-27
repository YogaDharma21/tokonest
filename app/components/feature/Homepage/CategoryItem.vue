<template>
    <NuxtLink
        class="p-2 border-b border-r border-black/5 hover:shadow hover:border-black/15"
        to="/"
    >
        <img :src="image" class="aspect-square w-full" />
        <p class="text-sm text-center text-black/80">{{ title }}</p>
    </NuxtLink>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: "",
    },
    image: {
        type: String,
        default: "",
    },
});
</script>

<style scoped></style>
