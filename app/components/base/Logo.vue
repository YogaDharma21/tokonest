<template>
    <UButton
        variant="link"
        :color="color"
        :padded="false"
        to="/"
        class="text-4xl! font-normal! no-underline!"
    >
        Syopo
    </UButton>
</template>

<script setup>
defineProps({
    color: {
        type: String,
        default: "white",
        validator: (propsValue) => ["white", "orange"].includes(propsValue),
    },
});
</script>

<style></style>
